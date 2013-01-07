<?php

/**
 * @package JooS
 * @subpackage Log
 */
require_once "JooS/Log/Interface.php";

/**
 * Echo log data.
 */
class JooS_Log_Output implements JooS_Log_Interface
{
  
  /**
   * Write to log
   * 
   * @param string $message Message
   * 
   * @return boolean
   */
  public function write($message)
  {
    file_put_contents("php://output", trim($message) . PHP_EOL);
      
    return true;
  }
  
}
