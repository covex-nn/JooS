<?php

/**
 * @package JooS
 */
class JooS_Loader
{

    /**
     * @param string $className
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
     * @param string $className
     * @param string $ext
     * @return string
     */
    public static function getPath($className, $ext = "php")
    {
        return str_replace("_", DIRECTORY_SEPARATOR, $className) . "." . $ext;
    }

    /**
     * @param string $className
     * @param string $ext
     * @return string
     */
    public static function getRelativePath($className, $ext = "php")
    {
        require_once "JooS/Config.php";

        $library = JooS_Config::PHPackager_Library()->path() ? : "library";
        return $library . DIRECTORY_SEPARATOR . self::getPath($className, $ext);
    }

    /**
     * @param string $className
     * @param string $ext
     * @return string
     */
    public static function getRealPath($className, $ext = "php")
    {
        require_once "JooS/Config.php";

        return implode(
            DIRECTORY_SEPARATOR, array(
              JooS_Config::PHPackager()->path(),
              self::getRelativePath($className, $ext)
            )
        );
    }

    /**
     * @param string $prefix
     * @param string $name
     * @param bool $ucfirst
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

