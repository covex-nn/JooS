<?php

/**
 * @package JooS
 * @subpackage Log
 */
namespace JooS\Log;

/**
 * Deploy/PHPackage data logger interface.
 */
interface Log_Interface
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
