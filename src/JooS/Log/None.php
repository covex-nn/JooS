<?php

/**
 * @package JooS
 * @subpackage Log
 */
namespace JooS\Log;

/**
 * Do not write log data.
 */
class None implements Log_Interface
{
  
  /**
   * Write to log
   * 
   * @param string $message Message
   * 
   * @return boolean
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function write($message)
  {
    return true;
  }
  
}
