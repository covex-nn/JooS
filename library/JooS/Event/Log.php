<?php

/**
 * @package JooS
 */
require_once "JooS/Event.php";

/**
 * Event "Append data to log"
 * 
 * @property string $message Data
 * 
 * @method null setMessage(string $value) Set message
 * @method JooS_Event_Log getInstance() Return event instance
 */
class JooS_Event_Log extends JooS_Event
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
