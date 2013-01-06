<?php

/**
 * @package JooS
 */
require_once "JooS/Config/Adapter/Interface.php";

/**
 * Configuration.
 */
class JooS_Config implements ArrayAccess, Iterator
{

  /**
   * @var array
   */
  private $_data;

  /**
   * @var JooS_Config
   */
  private $_root = null;

  /**
   * @var boolean
   */
  private $_modified = false;

  /**
   * @var array
   */
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
    $key = self::_instanceKey($name);
    if (!isset(self::$_instances[$key])) {

      $dataSource = self::getDataAdapter();
      if (is_null($dataSource)) {
        $data = array();
      } else {
        $data = $dataSource->load($key);
      }
      
      $config = new self($data);
      /* @var $config JooS_Config */
      $config->_root = $config;

      self::$_instances[$key] = $config;
    }
    return self::$_instances[$key];
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
   * Unloads config instance.
   * 
   * @param string $name Config name
   * 
   * @return null
   */
  public static function clearInstance($name)
  {
    $key = self::_instanceKey($name);
    
    if (isset(self::$_instances[$key])) {
      unset(self::$_instances[$key]);
    }
  }
  
  /**
   * Upload all configs data
   * 
   * @return null
   */
  public static function clearAll()
  {
    $names = array_keys(self::$_instances);
    foreach ($names as $name) {
      self::clearInstance($name);
    }
  }
  
  /**
   * Save config instance data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public static function saveInstance($name)
  {
    $adapter = self::getDataAdapter();
    if (!is_null($adapter)) {
      $config = self::getInstance($name);
      $key = self::_instanceKey($name);
      
      $result = $adapter->save($key, $config);
    } else {
      $result = false;
    }
    
    return $result;
  }
  
  /**
   * Delete config instance data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public static function deleteInstance($name)
  {
    $adapter = self::getDataAdapter();
    if (!is_null($adapter)) {
      $key = self::_instanceKey($name);
      
      $result = $adapter->delete($key);
    } else {
      $result = false;
    }
    self::clearInstance($name);
    
    return $result;
  }

  /**
   * @var JooS_Config_Adapter_Interface
   */
  private static $_dataAdapter = null;
  
  /**
   * Set all-config data source
   * 
   * @param JooS_Config_Adapter_Interface $dataAdapter Data source
   * 
   * @return null
   */
  public static function setDataAdapter(JooS_Config_Adapter_Interface $dataAdapter)
  {
    self::$_dataAdapter = $dataAdapter;
  }
  
  /**
   * Return all-config data source
   * 
   * @return JooS_Config_Adapter_Interface
   */
  public static function getDataAdapter()
  {
    return self::$_dataAdapter;
  }
  
  /**
   * Clear all-config data source
   * 
   * @return null
   */
  public static function clearDataAdapter()
  {
    self::$_dataAdapter = null;
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
   * Is config modified?
   * 
   * @return boolean
   */
  public function isModified()
  {
    return !!$this->_root->_modified;
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
      trigger_error(
        "Cannot use a scalar value as an array", E_USER_NOTICE
      );
      return;
    }

    if (is_array($value) || is_scalar($value)) {
      $newValue = $value;
    } elseif (is_object($value) && $value instanceof JooS_Config) {
      $newValue = $value->valueOf();
    } else {
      trigger_error(
        "Type mismatch", E_USER_NOTICE
      );
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
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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

  /**
   * Returns className for config instance.
   * 
   * @param string $name Config name
   * 
   * @return string
   */
  private static function _instanceKey($name)
  {
    return strtolower($name);
  }
  
}

