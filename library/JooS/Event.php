<?php

  /**
   * @package JooS
   */
  require_once "JooS/Event/Interface.php";

  abstract class JooS_Event implements JooS_Event_Interface
  {

    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var array
     */
    private $_observers;

    /**
     * @var array
     */
    private $_notifyData;

    /**
     * @var array
     */
    private static $_instances = array();

    /**
     * @param string $name
     */
    private function __construct()
    {
      $this->_loadObservers();
    }

    private function _loadObservers()
    {
      require_once "JooS/Config.php";

      $valueOf = JooS_Config::getInstance($this->name())->valueOf();
      $this->_observers = $valueOf ? : array();
    }

    final public function __clone()
    {
      require_once "JooS/Event/Exception.php";
      throw new JooS_Event_Exception("Event object is singleton");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
      $name = $this->_normalizeName($name);
      return isset($this->_notifyData[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
      $name = $this->_normalizeName($name);
      return isset($this->_notifyData[$name]) ? $this->_notifyData[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
      $name = $this->_normalizeName($name);
      $this->_notifyData[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
      $name = $this->_normalizeName($name);
      if (isset($this->_notifyData[$name])) {
        unset($this->_notifyData[$name]);
      }
    }

    /**
     * @param string $name
     * @param mixed $arguments
     * @return JooS_Event
     */
    final public function __call($name, $arguments)
    {
      if (substr($name, 0, 3) === "set" && sizeof($arguments) == 1) {
        $name = substr($name, 3);
        $value = $arguments[0];

        if (is_null($value)) {
          $this->__unset($name);
        } else {
          $this->__set($name, $value);
        }
      }
      return $this;
    }

    protected function initialize()
    {
      $this->_notifyData = array();
    }

    /**
     * @param string $className
     * @return JooS_Event
     */
    final protected static function _getInstance($className)
    {
      require_once "JooS/Loader.php";

      if (!isset(self::$_instances[$className])) {
        if (!JooS_Loader::loadClass($className)) {
          require_once "JooS/Event/Exception.php";

          throw new JooS_Event_Exception("Class $className not found");
        }

        self::$_instances[$className] = new $className();
      }

      self::$_instances[$className]
              ->initialize();

      return self::$_instances[$className];
    }

    /**
     * @param string $className
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
     * @throws JooS_Event_Exception
     */
    public function cancel($message = null, $code = null)
    {
      require_once "JooS/Event/Exception.php";

      throw new JooS_Event_Exception($message, $code);
    }

    /**
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
        }

        if (!is_callable($observer)) {
          continue;
        }

        call_user_func($observer, $this);
      }

      return $this;
    }

    /**
     * @param callback $observer
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
     * @param callback $observer
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
     * @return string
     */
    final public function name()
    {
      return get_class($this);
    }

    /**
     * @return array
     */
    final public function observers()
    {
      return $this->_observers;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    private function _normalizeName($name)
    {
      return strtolower($name);
    }

  }

  