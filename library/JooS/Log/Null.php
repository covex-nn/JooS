<?php

/**
 * @package JooS
 * @subpackage Log
 */
require_once "JooS/Log/Interface.php";

/**
 * Do not write log data.
 */
class JooS_Log_Null implements JooS_Log_Interface
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
