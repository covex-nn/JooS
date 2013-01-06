<?php

/**
 * @package JooS
 */

/**
 * Interface for config data source adapter
 */
interface JooS_Config_Adapter_Interface
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
   * @param string      $name   Config name
   * @param JooS_Config $config Config data
   * 
   * @return boolean
   */
  public function save($name, JooS_Config $config);
  
  /**
   * Delete config data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public function delete($name);
}
