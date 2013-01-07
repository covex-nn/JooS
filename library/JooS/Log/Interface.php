<?php

/**
 * @package JooS
 * @subpackage Log
 */

/**
 * Deploy/PHPackage data logger interface.
 */
interface JooS_Log_Interface
{
  
  /**
   * Write to log
   * 
   * @param string $message Message
   * 
   * @return boolean
   */
  public function write($message);
}
