<?php

/**
 * @package JooS
 */
require_once "JooS/Helper/Abstract.php";

/**
 * Options helper
 */
class JooS_Options extends JooS_Helper_Abstract {
  /**
   * @var array
   */
  private $_data;
  
  /**
   * @var array
   */
  private $_defaultData;
  
  /**
   * Constructor
   * 
   * @param type $default
   */
  public function __construct()
  {
    $this->_data = array();
    $this->_defaultData = null;
  }
  
  /**
   * Magic method __get
   * 
   * @param string $name Property name
   * 
   * @return mixed
   */
  public function __get($name)
  {
    if (isset($this->_data[$name])) {
      $value = $this->_data[$name];
    } else {
      $value = $this->getDefault($name);
    }
    return $value;
  }
  
  /**
   * Magic method __isset
   * 
   * @param string $name Property name
   * 
   * @return mixed
   */
  public function __isset($name) {
    if (isset($this->_data[$name])) {
      $isset = true;
    } else {
      $isset = !is_null($this->getDefault($name));
    }
    return $isset;
  }
  
  /**
   * Magic method __unset
   * 
   * @param string $name Property name
   * 
   * @return null
   */
  public function __unset($name) {
    unset($this->_data[$name]);
  }
  
  /**
   * Magic method __set
   * 
   * @param string $name  Property name
   * @param mixed  $value New value
   * 
   * @return null
   */
  public function __set($name, $value)
  {
    $this->_data[$name] = $value;
  }
  
  /**
   * Return default value
   * 
   * @param string $name Property name
   * 
   * @return mixed
   */
  protected function getDefault($name) {
    if (is_null($this->_defaultData)) {
      $subject = $this->getSubject();
      if (!is_null($subject) && $subject instanceof JooS_Options_Subject) {
        $this->_defaultData = $subject->getDefaultOptions();
      } else {
        $this->_defaultData = array();
      }
    }
    
    if (isset($this->_defaultData[$name])) {
      $value = $this->_defaultData[$name];
    } else {
      $value = null;
    }
    
    return $value;
  }
  
}
