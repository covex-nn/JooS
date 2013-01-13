<?php

/**
 * @package JooS
 * @subpackage Event
 */
namespace JooS\Event;

use JooS\Object;
use JooS\Config\Config;
use JooS\Loader;

require_once "JooS/Object.php";

require_once "JooS/Event/Event/Interface.php";

/**
 * Event.
 */
abstract class Event extends Object implements Event_Interface
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
   * Cloning not allowed.
   * 
   * @return null
   * @throws Exception
   */
  final public function __clone()
  {
    require_once "JooS/Event/Exception.php";
    
    throw new Exception("Event object is singleton");
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
   * @return Event
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
      require_once "JooS/Config/Config.php";

      $name = self::_configName($className);
      Config::clearInstance($name);

      self::$_instances[$className]->_loadObservers();
    }
  }

  /**
   * Cancel event execution.
   * 
   * @param string $message Exception message
   * @param int    $code    Exception code
   * 
   * @return null
   * @throws Exception
   */
  public function cancel($message = null, $code = null)
  {
    require_once "JooS/Event/Exception.php";

    throw new Exception($message, $code);
  }

  /**
   * Notify observers.
   * 
   * @return Event
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
          require_once "JooS/Loader.php";
          
          Loader::loadClass($observer[0]);
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
   * @return Event
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
   * @return Event 
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
  
  /**
   * Save observers
   * 
   * @return boolean
   */
  final public function save()
  {
    $class = $this->name();
    $name = self::_configName($class);
    $observers = $this->observers();
    
    $config = Config::newInstance($name, $observers);
    
    return $config->save();
  }

  /**
   * Load observers.
   * 
   * @return null
   */
  private function _loadObservers()
  {
    require_once "JooS/Config/Config.php";

    $class = $this->name();
    $name = self::_configName($class);
    $config = Config::getInstance($name);
    
    $this->_observers = $config->valueOf();
  }
  
  /**
   * A name of Config with list of observers
   * 
   * @param string $name Class name
   * 
   * @return string
   */
  private static function _configName($name)
  {
    return str_replace("\\", "_", $name);
  }
  
}
