<?php

/**
 * @package JooS
 */
namespace JooS;

/**
 * Class Loader.
 */
class Loader
{

  /**
   * Loads class.
   * 
   * @param string $className Class Name
   * 
   * @return bool
   */
  public static function loadClass($className)
  {
    if (!class_exists($className)) {
      $path = self::getPath($className);

      $realPath = stream_resolve_include_path($path);

      if (file_exists($realPath)) {
        require_once($realPath);

        return class_exists($className);
      }
      return false;
    } else {
      return true;
    }
  }

  /**
   * Returns class path.
   * 
   * @param string $className Class Name
   * @param string $ext       Extention
   * 
   * @return string
   */
  public static function getPath($className, $ext = "php")
  {
    if (substr($className, 0, 1) == "\\") {
      $className = substr($className, 1);
    }
    
    $delimiters = array("_", "\\");
    foreach ($delimiters as $symbol) {
      $className = str_replace($symbol, "/", $className);
    }
    return $className . "." . $ext;
  }

  /**
   * Returns className.
   * 
   * @param string $prefix  Prefix
   * @param string $name    Name
   * @param bool   $ucfirst First letter - caps
   * 
   * @return string
   */
  public static function getClassName($prefix, $name, $ucfirst = false)
  {
    if (substr($prefix, -1, 1) == "\\") {
      $className = $prefix;
    } else {
      $className = $prefix . "_";
    }
    $className .= $name;
    
    if ($ucfirst) {
      $result = "";
      
      $nsPath = explode("\\", $className);
      $name = array_pop($nsPath);
      
      if (sizeof($nsPath)) {
        for ($i = 0; $i < sizeof($nsPath); $i++) {
          $nsPath[$i] = ucfirst(strtolower($nsPath[$i]));
        }
        $result .= implode("\\", $nsPath);
      }
      if (strlen($result) && substr($result, -1, 1) != "\\") {
        $result .= "\\";
      }
      
      $dirPath = explode("_", $name);
      for ($i = 0; $i < sizeof($dirPath); $i++) {
        $dirPath[$i] = ucfirst(strtolower($dirPath[$i]));
      }
      $result .= implode("_", $dirPath);
      
    } else {
      $result = $className;
    }

    return $result;
  }
  
}

