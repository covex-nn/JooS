<?php

/**
 * @package JooS
 * @subpackage Log
 */
namespace JooS\Log;

use JooS\Event\Event;

/**
 * Event "Append data to log"
 * 
 * @property string $message Data
 * 
 * @method Log_Event setMessage(string $value) Set message
 * @method Log_Event getInstance() Return event instance
 */
class Log_Event extends Event
{
  
  /**
   * Validator for 'message' property
   * 
   * @param string $value Message
   * 
   * @return boolean
   */
  public function validateMessage($value)
  {
    if (is_string($value)) {
      $result = true;
    } elseif (is_object($value) && method_exists($value, "__toString")) {
      $result = true;
    } else {
      $result = false;
    }
    
    return $result;
  }
  
}
