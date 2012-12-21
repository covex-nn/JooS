<?php

/**
 * @package JooS
 */
require_once "JooS/Object.php";

require_once "JooS/Event/Interface.php";

/**
 * Event.
 */
abstract class JooS_Event extends JooS_Object implements JooS_Event_Interface
{

  /**
   * @var array
   */
  private $_observers;

  /**
   * @var array
   */
  private static $_instances = array();

  /**
   * Private constructor.
   */
  private function __construct()
  {
    $this->_loadObservers();
  }

  /**
   * Load observers.
   * 
   * @return null
   */
  private function _loadObservers()
  {
    require_once "JooS/Config.php";

    $valueOf = JooS_Config::getInstance($this->name())->valueOf();
    $this->_observers = $valueOf ? : array();
  }

  /**
   * Cloning not allowed.
   * 
   * @throws JooS_Event_Exception
   * @return null
   */
  final public function __clone()
  {
    require_once "JooS/Event/Exception.php";
    
    throw new JooS_Event_Exception("Event object is singleton");
  }

  /**
   * Event Initialize.
   * 
   * @return null
   */
  protected function initialize()
  {
    
  }

  /**
   * Return event instance
   * 
   * @return JooS_Event
   */
  final public static function getInstance()
  {
    $className = get_called_class();
    
    if (!isset(self::$_instances[$className])) {
      self::$_instances[$className] = new static();
    }
    self::$_instances[$className]->initialize();

    return self::$_instances[$className];
  }
  
  /**
   * Unloads event instance.
   * 
   * @param string $className Event name
   * 
   * @return null
   */
  final public static function clearInstance($className)
  {
    if (isset(self::$_instances[$className])) {
      require_once "JooS/Config.php";

      JooS_Config::clearInstance($className);

      self::$_instances[$className]->_loadObservers();
    }
  }

  /**
   * Cancel event execution.
   * 
   * @param string $message Exception message
   * @param int    $code    Exception code
   * 
   * @throws JooS_Event_Exception
   * @return null
   */
  public function cancel($message = null, $code = null)
  {
    require_once "JooS/Event/Exception.php";

    throw new JooS_Event_Exception($message, $code);
  }

  /**
   * Notify observers.
   * 
   * @return JooS_Event
   */
  final public function notify()
  {
    $observers = array_values($this->_observers);
    foreach ($observers as $observer) {
      switch (false) {
        case is_array($observer):
        case isset($observer[0]):
        case isset($observer[1]):
        case is_string($observer[0]):
          break;
        default:
          JooS_Loader::loadClass($observer[0]);
          break;
      }

      if (!is_callable($observer)) {
        continue;
      }

      call_user_func($observer, $this);
    }

    return $this;
  }

  /**
   * Attach new observer.
   * 
   * @param callback $observer Observer
   * 
   * @return JooS_Event
   */
  final public function attach($observer)
  {
    if (!in_array($observer, $this->_observers)) {
      array_push($this->_observers, $observer);
    }
    return $this;
  }

  /**
   * Detach observer.
   * 
   * @param callback $observer Observer
   * 
   * @return JooS_Event 
   */
  final public function detach($observer)
  {
    $key = array_search($observer, $this->_observers);
    if ($key !== false) {
      unset($this->_observers[$key]);
    }
    return $this;
  }

  /**
   * Return event's name.
   * 
   * @return string
   */
  final public function name()
  {
    return get_class($this);
  }

  /**
   * Return list of observers.
   * 
   * @return array
   */
  final public function observers()
  {
    return $this->_observers;
  }

}

