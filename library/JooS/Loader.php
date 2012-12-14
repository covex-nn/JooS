<?php

/**
 * @package JooS
 */

/**
 * Class Loader.
 */
class JooS_Loader
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

      $includePaths = explode(PATH_SEPARATOR, get_include_path());
      for ($i = 0; $i < sizeof($includePaths); $i++) {
        $realPath = $includePaths[$i] . DIRECTORY_SEPARATOR . $path;

        if (file_exists($realPath)) {
          require_once($realPath);

          return class_exists($className);
        }
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
    return str_replace("_", DIRECTORY_SEPARATOR, $className) . "." . $ext;
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
    if ($ucfirst) {
      $path = explode("_", $name);
      for ($i = 0; $i < sizeof($path); $i++) {
        $path[$i] = ucfirst(strtolower($path[$i]));
      }
      $name = implode("_", $path);
    }

    return ($prefix ? $prefix . "_" : "") . $name;
  }

}

