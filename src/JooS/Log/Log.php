<?php

/**
 * @package JooS
 * @subpackage Log
 */
namespace JooS\Log;

use JooS\Loader;
use JooS\Config\Config;

/**
 * Log phpackage/deploy messages.
 */
class Log
{
  
  /**
   * Event observer
   * 
   * @param Log_Event $event Event
   * 
   * @return boolean
   */
  public static function observer(Log_Event $event)
  {
    foreach (self::getWriters() as $writer) {
      /* @var $writer JooS_Log_Interface */
      $writer->write($event->message);
    }
    
    return true;
  }

  /**
   * @var array
   */
  private static $_writers = null;
  
  /**
   * Return log writers
   * 
   * @return array
   */
  public static function getWriters()
  {
    if (is_null(self::$_writers)) {
      $writers = Config::getInstance("JooS_Log")->writers;
      /* @var $writers JooS_Config */
      if (!is_null($writers)) {
        foreach ($writers->valueOf() as $name) {
          $name = ucfirst(strtolower($name));
          $className = Loader::getClassName(__NAMESPACE__ . "\\", $name);
          if (Loader::loadClass($className)) {
            $writer = new $className();
            if ($writer instanceof Log_Interface) {
              self::addWriter($writer);
            }
          }
        }
      } else {
        self::$_writers = array();
      }
    }
    return self::$_writers;
  }
  
  /**
   * Add new writer
   * 
   * @param Log_Interface $writer Log writer
   * 
   * @return boolean
   */
  public static function addWriter(Log_Interface $writer)
  {
    if (is_null(self::$_writers)) {
      self::$_writers = array();
    }
    
    $key = get_class($writer);
    if (!isset(self::$_writers[$key])) {
      self::$_writers[$key] = $writer;
      $result = true;
    } else {
      $result = false;
    }
    
    return $result;
  }
  
  /**
   * Clear log writers
   * 
   * @return null
   */
  public static function clearWriters()
  {
    self::$_writers = null;
  }
  
}
