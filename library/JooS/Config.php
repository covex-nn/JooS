<?php

/**
 * @package JooS
 */
class JooS_Config implements ArrayAccess, Iterator
{

    const CLASS_PREFIX = "JooSX_Config";

    /**
     * @var array
     */
    private $_data;
    private $_className = null;
    private $_root = null;
    private $_modified = null;
    private static $_instances = array();

    protected function __construct(&$data)
    {
        $this->_data = &$data;
        reset($this);
    }

    /**
     *
     * @param string $name
     * @return JooS_Config
     */
    public static function getInstance($name)
    {
        require_once "JooS/Loader.php";

        $className = self::getClassName($name);
        if (!isset(self::$_instances[$className])) {
            if (JooS_Loader::loadClass($className)) {
                $config = new $className();
            } else {
                $data = array();
                $config = new self($data);
            }

            /* @var $config JooS_Config */
            $config->_className = $className;
            $config->_root = $config;

            self::$_instances[$className] = $config;
        }
        return self::$_instances[$className];
    }

    /**
     *
     * @param string $name
     */
    public static function clearInstance($name)
    {
        $className = self::getClassName($name);
        if (isset(self::$_instances[$className])) {
            unset(self::$_instances[$className]);
        }
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public static function getClassName($name)
    {
        require_once "JooS/Loader.php";

        return JooS_Loader::getClassName(self::CLASS_PREFIX, $name, true);
    }

    /**
     *
     * @param string $name
     * @param array $data
     * @return JooS_Config
     */
    public static function newInstance($name, $data = null)
    {
        if (!is_array($data)) {
            $data = array();
        }

        $config = self::getInstance($name);
        $config->_data = $data;

        return $config;
    }

    /**
     * @return array
     */
    public function valueOf()
    {
        return $this->_data;
    }

    /**
     * @return string
     */
    public function get_class()
    {
        return $this->_className;
    }

    /**
     * @return bool
     */
    public function is_modified()
    {
        return $this->_root->_modified;
    }

    public function __get($key)
    {
        if ($this->__isset($key)) {
            $config = new self($this->_data[$key]);
            $config->_root = $this->_root;
        } else {
            $config = null;
        }

        return $config;
    }

    public function __set($key, $value)
    {
        if (!is_array($this->_data)) {
            trigger_error(
              "Cannot use a scalar value as an array", E_USER_NOTICE
            );
            return;
        }

        if (is_array($value) || is_scalar($value)) {
            $newValue = $value;
        } elseif (is_object($value) && $value instanceof JooS_Config) {
            $newValue = $value->valueOf();
        } else {
            trigger_error("Type mismatch", E_USER_NOTICE);
            return;
        }

        $this->_data[$key] = $newValue;
        $this->_root->_modified = true;
    }

    public function __isset($key)
    {
        return is_array($this->_data) && isset($this->_data[$key]);
    }

    public function __unset($key)
    {
        if ($this->__isset($key)) {
            unset($this->_data[$key]);
            $this->_root->_modified = true;
        }
    }

    public function __call($name, $param)
    {
        $config = $this->__get($name);
        return is_null($config) ? null : $config->valueOf();
    }

    public function __invoke()
    {
        return $this->valueOf();
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return JooS_Config
     */
    public static function __callStatic($name, $arguments)
    {
        return self::getInstance($name);
    }

    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
    }

    public function offsetExists($key)
    {
        return $this->__isset($key);
    }

    public function offsetUnset($key)
    {
        $this->__unset($key);
    }

    public function current()
    {
        return $this->__get($this->key());
    }

    public function key()
    {
        return key($this->_data);
    }

    public function next()
    {
        list(, $next) = each($this->_data);
        return $this->current();
    }

    public function rewind()
    {
        reset($this->_data);
    }

    public function valid()
    {
        $key = $this->key();
        return is_null($key) ? false : isset($this->_data[$key]);
    }

}

