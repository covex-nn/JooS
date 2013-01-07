<?php

/**
 * @package JooS
 * @subpackage Log
 */

/**
 * Log phpackage/deploy messages.
 */
class JooS_Log
{
  
  /**
   * Event observer
   * 
   * @param JooS_Event_Log $event
   * 
   * @return boolean
   */
  public static function observer(JooS_Event_Log $event)
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
      require_once "JooS/Loader.php";
      
      require_once "JooS/Config.php";
      
      $writers = JooS_Config::getInstance("JooS_Log")->writers;
      /* @var $writers JooS_Config */
      
      foreach ($writers->valueOf() as $name)
      {
        $className = JooS_Loader::getClassName(__CLASS__, $name, true);
        if (JooS_Loader::loadClass($className)) {
          $writer = new $className();
          if ($writer instanceof JooS_Log_Interface) {
            self::addWriter($writer);
          }
        }
      }
    }
    return self::$_writers;
  }
  
  /**
   * Add new writer
   * 
   * @param JooS_Log_Interface $writer
   * 
   * @return boolean
   */
  public static function addWriter(JooS_Log_Interface $writer)
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
