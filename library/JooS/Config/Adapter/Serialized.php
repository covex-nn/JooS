<?php

/**
 * @package JooS
 * @subpackage Deploy
 */
namespace JooS\Config;

require_once "JooS/Config/Adapter/Interface.php";

/**
 * Config adapter "Serialized data".
 */
class Adapter_Serialized implements Adapter_Interface
{
  
  /**
   * @var string
   */
  private $_root;
  
  /**
   * Constructor
   * 
   * @param string $root Path to all config data
   */
  public function __construct($root)
  {
    $this->_root = $root;
  }
  
  /**
   * Return path to config data
   * 
   * @return string
   */
  public function getRoot()
  {
    return $this->_root;
  }
  
  /**
   * Load config data
   * 
   * @param string $name Config name
   * 
   * @return array
   */
  public function load($name)
  {
    $path = $this->_getPath($name);
    
    $data = null;
    if (file_exists($path)) {
      $serialized = file_get_contents($path);
      $data = @unserialize($serialized);
    }
    if (!is_array($data)) {
      $data = array();
    }
    
    return $data;
  }

  /**
   * Save config data
   * 
   * @param string $name   Config name
   * @param Config $config Config data
   * 
   * @return boolean
   */
  public function save($name, Config $config)
  {
    $path = $this->_getPath($name);
    
    $data = $config->valueOf();
    file_put_contents(
      $path, serialize($data)
    );
    
    return true;
  }
  
  /**
   * Delete config data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public function delete($name)
  {
    $path = $this->_getPath($name);
    
    if (file_exists($path)) {
      $result = @unlink($path);
    } else {
      $result = false;
    }
    
    return $result;
  }
  
  /**
   * Return full path to config data
   * 
   * @param string $name Config name
   * 
   * @return string
   */
  private function _getPath($name)
  {
    return $this->getRoot() . "/$name.serialized";
  }
}
