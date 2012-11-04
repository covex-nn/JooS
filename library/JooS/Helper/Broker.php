<?php

/**
 * @package JooS
 */
final class JooS_Helper_Broker implements ArrayAccess
{

    const HELPER_SUBJECT_INTERFACE = "JooS_Helper_Subject";

    private $_helpers = array();
    private $_subject = null;
    private static $_loadedHelpers = array();
    private static $_paths = null;

    /**
     * @param string $prefix
     */
    public static function addPrefix($prefix)
    {
        $prefix = rtrim($prefix, '_') . "_";
        $path = str_replace('_', DIRECTORY_SEPARATOR, $prefix);

        $newPrefix = array(
            "dir" => $path,
            "prefix" => $prefix
        );

        if (!in_array($newPrefix, self::$_paths))
            self::$_paths[] = $newPrefix;
    }

    /**
     * @return array
     */
    public static function getPrefixes()
    {
        return self::$_paths;
    }

    /**
     * 
     */
    public static function clearPrefixes()
    {
        self::$_paths = array();
        self::addPrefix("JooS");
    }

    /**
     * @param JooS_Helper_Subject $subject
     * @return JooS_Helper_Broker
     */
    public static function newInstance(JooS_Helper_Subject $subject)
    {
        $newInstance = new self();
        $newInstance->_subject = $subject;

        return $newInstance;
    }

    public function __call($helper, $argv)
    {
        self::_getHelperReflection($helper);
    }

    /**
     * @covers JooS_Helper_Broker::offsetGet
     * @param string $helper
     * @return mixed
     */
    public function __get($helper)
    {
        if (!$this->__isset($helper)) {
            require_once "JooS/Helper/Exception.php";

            throw new JooS_Helper_Exception("Helper '$helper' was not found");
        }

        return $this->_helpers[$helper];
    }

    public function __isset($helper)
    {
        $has = true;
        try {
            $this->_getHelper($helper);
        } catch (JooS_Helper_Exception $e) {
            $has = false;
        }
        return $has;
    }

    /**
     * @throws JooS_Helper_Exception
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        require_once "JooS/Helper/Exception.php";

        throw new JooS_Helper_Exception("Forbidden");
    }

    /**
     * @throws JooS_Helper_Exception
     * @param string $name
     */
    public function __unset($name)
    {
        require_once "JooS/Helper/Exception.php";

        throw new JooS_Helper_Exception("Forbidden");
    }

    public function offsetExists($helper)
    {
        return $this->__isset($helper);
    }

    public function offsetGet($helper)
    {
        return $this->__get($helper);
    }

    public function offsetSet($offset, $value)
    {
        require_once "JooS/Helper/Exception.php";

        throw new JooS_Helper_Exception("Forbidden");
    }

    public function offsetUnset($offset)
    {
        require_once "JooS/Helper/Exception.php";

        throw new JooS_Helper_Exception("Forbidden");
    }

    private function __construct()
    {
        if (is_null(self::$_paths)) {
            require_once "JooS/Config.php";

            self::clearPrefixes();
            $prefixes = JooS_Config::Helper_Broker()->prefixes();

            if (is_array($prefixes)) {
                foreach ($prefixes as $prefix) {
                    self::addPrefix($prefix);
                }
            }
        }
    }

    /**
     * @param string $helper
     * @return JooS_Helper_Interface
     */
    private function _getHelper($helper)
    {
        if (!isset($this->_helpers[$helper])) {
            $this->_helpers[$helper] = self::_getHelperReflection($helper)->newInstance();
            $this->_helpers[$helper]->setSubject($this->_subject);
        }
        return $this->_helpers[$helper];
    }

    /**
     * @param string $helper
     * @return ReflectionClass
     */
    private static function _getHelperReflection($helper)
    {
        if (!isset(self::$_loadedHelpers[$helper])) {
            require_once "JooS/Loader.php";

            $className = null;
            for ($i = sizeof(self::$_paths) - 1; $i >= 0; $i--) {
                $className = self::$_paths[$i]["prefix"] . $helper;
                if (JooS_Loader::loadClass($className))
                    break;
                else
                    $className = null;
            }
            if (is_null($className)) {
                require_once "JooS/Helper/Exception.php";

                throw new JooS_Helper_Exception("Helper '$helper' was not found");
            }

            $rHelper = new ReflectionClass($className);
            if ($rHelper->implementsInterface("JooS_Helper_Interface")) {
                self::$_loadedHelpers[$helper] = $rHelper;
                $rHelper->getMethod("init")->invoke(null);
            } else {
                require_once "JooS/Helper/Exception.php";

                throw new JooS_Helper_Exception("Helper '$helper' must implement JooS_Helper_Interface");
            }
        }
        return self::$_loadedHelpers[$helper];
    }

}
