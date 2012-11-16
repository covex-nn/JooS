<?php

/**
 * @package JooS
 */

/**
 * Configuration.
 */
class JooS_Config implements ArrayAccess, Iterator
{

  const CLASS_PREFIX = "JooSX_Config";

  const ERROR_CANNOT_USE_SCALAR = "Cannot use a scalar value as an array";

  const ERROR_TYPE_MISMATCH = "Type mismatch";

  /**
   * @var array
   */
  private $_data;

  private $_className = null;

  private $_root = null;

  private $_modified = null;

  private static $_instances = array();

  /**
   * Protected constructor.
   * 
   * @param array &$data Config Data
   */
  protected function __construct(&$data)
  {
    $this->_data = &$data;
    reset($this);
  }

  /**
   * Returns config instance
   * 
   * @param string $name Config name
   * 
   * @return JooS_Config
   */
  public static function getInstance($name)
  {
    require_once "JooS/Loader.php";

    $className = self::getClassName($name);
    if (!isset(self::$_instances[$className])) {
      if (JooS_Loader::loadClass($className)) {
        $config = new $className();
      } else {
        $data = array();
        $config = new self($data);
      }

      /* @var $config JooS_Config */
      $config->_className = $className;
      $config->_root = $config;

      self::$_instances[$className] = $config;
    }
    return self::$_instances[$className];
  }

  /**
   * Unloads config instance.
   * 
   * @param string $name Config name
   * 
   * @return null
   */
  public static function clearInstance($name)
  {
    $className = self::getClassName($name);
    if (isset(self::$_instances[$className])) {
      unset(self::$_instances[$className]);
    }
  }

  /**
   * Returns className for config instance.
   * 
   * @param string $name Config name
   * 
   * @return string
   */
  public static function getClassName($name)
  {
    require_once "JooS/Loader.php";

    return JooS_Loader::getClassName(self::CLASS_PREFIX, $name, true);
  }

  /**
   * Shortcut for instance creation.
   * 
   * @param string $name Config name
   * @param array  $data Config data
   * 
   * @return JooS_Config
   */
  public static function newInstance($name, $data = null)
  {
    if (!is_array($data)) {
      $data = array();
    }

    $config = self::getInstance($name);
    $config->_data = $data;

    return $config;
  }

  /**
   * Returns config data.
   * 
   * @return array
   */
  public function valueOf()
  {
    return $this->_data;
  }

  /**
   * Returns config class.
   * 
   * @return string
   */
  public function get_class()
  {
    return $this->_className;
  }

  /**
   * Is config modified?
   * 
   * @return bool
   */
  public function is_modified()
  {
    return $this->_root->_modified;
  }

  /**
   * Get value.
   * 
   * @param string $key Key
   * 
   * @return JooS_Config
   */
  public function __get($key)
  {
    if ($this->__isset($key)) {
      $config = new self($this->_data[$key]);
      $config->_root = $this->_root;
    } else {
      $config = null;
    }

    return $config;
  }

  /**
   * Sets value.
   * 
   * @param string $key   Key
   * @param mixed  $value Value
   * 
   * @return null
   */
  public function __set($key, $value)
  {
    if (!is_array($this->_data)) {
      trigger_error(self::ERROR_CANNOT_USE_SCALAR, E_USER_NOTICE);
      return;
    }

    if (is_array($value) || is_scalar($value)) {
      $newValue = $value;
    } elseif (is_object($value) && $value instanceof JooS_Config) {
      $newValue = $value->valueOf();
    } else {
      trigger_error(self::ERROR_TYPE_MISMATCH, E_USER_NOTICE);
      return;
    }

    $this->_data[$key] = $newValue;
    $this->_root->_modified = true;
  }

  /**
   * Is value set ?
   * 
   * @param string $key Key
   * 
   * @return boolean
   */
  public function __isset($key)
  {
    return is_array($this->_data) && isset($this->_data[$key]);
  }

  /**
   * Unsets value.
   * 
   * @param string $key Key
   * 
   * @return null
   */
  public function __unset($key)
  {
    if ($this->__isset($key)) {
      unset($this->_data[$key]);
      $this->_root->_modified = true;
    }
  }

  /**
   * Returns value.
   * 
   * @param string $name      Key
   * @param array  $arguments Argiments (not used)
   * 
   * @SuppressWarnings(PHPMD.UnusedFormatParameter)
   * @return mixed
   */
  public function __call($name, $arguments)
  {
    $config = $this->__get($name);
    return is_null($config) ? null : $config->valueOf();
  }

  /**
   * Return all data.
   * 
   * @return array
   */
  public function __invoke()
  {
    return $this->valueOf();
  }

  /**
   * Returns instance.
   * 
   * @param string $name      Config name
   * @param array  $arguments Arguments (not used)
   * 
   * @SuppressWarnings(PHPMD.UnusedFormatParameter)
   * @return JooS_Config
   */
  public static function __callStatic($name, $arguments)
  {
    return self::getInstance($name);
  }

  /**
   * Get value.
   * 
   * @param string $key Key
   * 
   * @return JooS_Config
   */
  public function offsetGet($key)
  {
    return $this->__get($key);
  }

  /**
   * Sets value.
   * 
   * @param string $key   Key
   * @param mixed  $value Value
   * 
   * @return null
   */
  public function offsetSet($key, $value)
  {
    $this->__set($key, $value);
  }

  /**
   * Is value set ?
   * 
   * @param string $key Key
   * 
   * @return boolean
   */
  public function offsetExists($key)
  {
    return $this->__isset($key);
  }

  /**
   * Unsets value.
   * 
   * @param string $key Key
   * 
   * @return null
   */
  public function offsetUnset($key)
  {
    $this->__unset($key);
  }

  /**
	 * Return the current element.
   * 
   * @return string
   */
  public function current()
  {
    return $this->__get($this->key());
  }

  /**
	 * Return the key of the current element
   * 
   * @return string
   */
  public function key()
  {
    return key($this->_data);
  }

  /**
	 * Move forward to next element
   * 
   * @return string
   */
  public function next()
  {
    each($this->_data);
    return $this->current();
  }

  /**
	 * Rewind the Iterator to the first element
   * 
   * @return null
   */
  public function rewind()
  {
    reset($this->_data);
  }

  /**
	 * Checks if current position is valid
   * 
   * @return boolean
   */
  public function valid()
  {
    $key = $this->key();
    return is_null($key) ? false : isset($this->_data[$key]);
  }

}

