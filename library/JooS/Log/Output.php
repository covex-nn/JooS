<?php

/**
 * @package JooS
 * @subpackage Log
 */
namespace JooS\Log;

require_once "JooS/Log/Log/Interface.php";

/**
 * Echo log data.
 */
class Output implements Log_Interface
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
