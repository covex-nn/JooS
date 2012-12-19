<?php

/**
 * @package JooS
 */
require_once "JooS/Helper/Abstract.php";

/**
 * Options helper
 */
class JooS_Options extends JooS_Helper_Abstract
{
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
  public function __isset($name)
  {
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
  public function __unset($name)
  {
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
   * Load options from filename.json
   * 
   * @param string $filename Path to config file
   * 
   * @return boolean
   */
  public function loadJson($filename)
  {
    $result = false;
    if (file_exists($filename) && is_file($filename)) {
      $json = file_get_contents($filename);
      $data = json_decode($json, true);
      if (is_array($data)) {
        foreach ($data as $name => $value) {
          $this->__set($name, $value);
        }
        $result = true;
      }
    }
    return $result;
  }
  
  /**
   * Load options from CLI $argv array
   * 
   * @param array $argv CLI arguments
   * 
   * @return null
   */
  public function loadCommandLine(array $argv)
  {
    for ($i=0; $i<sizeof($argv); $i++) {
      $arg = $argv[$i];
      
      if (substr($arg, 0, 2) == "--") {
        $arg = substr($arg, 2);
        
        $parts = explode("=", $arg);
        $name = array_shift($parts);
        $value = implode("=", $parts);
        
        $this->{$name} = $value;
      }
    }
  }
  
  /**
   * Return default value
   * 
   * @param string $name Property name
   * 
   * @return mixed
   */
  protected function getDefault($name)
  {
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
