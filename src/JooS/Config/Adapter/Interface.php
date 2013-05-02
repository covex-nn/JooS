<?php

/**
 * @package JooS
 * @subpackage Config
 */
namespace JooS\Config;

/**
 * Interface for config data source adapter
 */
interface Adapter_Interface
{
  /**
   * Load config data
   * 
   * @param string $name Config name
   * 
   * @return array
   */
  public function load($name);
  
  /**
   * Save config data
   * 
   * @param string $name   Config name
   * @param Config $config Config data
   * 
   * @return boolean
   */
  public function save($name, Config $config);
  
  /**
   * Delete config data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public function delete($name);
}
