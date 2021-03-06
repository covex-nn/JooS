<?php

/**
 * @package JooS
 */
namespace JooS;

/**
 * Namespace from CityDNK's template-engine for #value/value.
 */
class VarSpace
{

  const DELIMITER = "/";

  /**
   * @var array
   */
  private $_data;

  /**
   * @var array
   */
  private $_current;

  /**
   * @var array
   */
  private $_tmp;

  /**
   * @var array
   */
  private $_pushedData;

  /**
   * @var int
   */
  private static $_pushID = 1;

  /**
   * Constructor.
   * 
   * @param array $data Data
   */
  public function __construct($data = array())
  {
    $this->_data = $data;
    $this->_names = array(&$this->_data);
    $this->_current = &$this->_data;
    $this->_pushedData = array();
  }

  /**
   * Sets value.
   * 
   * @param string &$get  Name
   * @param mixed  $value Value
   * 
   * @return null
   */
  public function set(&$get, $value)
  {
    $get = $value;
  }

  /**
   * Returns value #value/value
   * 
   * @param string $path Path
   * 
   * @return mixed
   */
  public function &get($path = '')
  {
    $this->_tmp = &$this->_current;
    $this->_shiftTmp($path);
    return $this->_tmp;
  }

  /**
   * Returns value #../../value
   * 
   * @param int    $times Times
   * @param string $path  Path
   * 
   * @return mixed
   */
  public function &getBack($times, $path = '')
  {
    $level = sizeof($this->_names) - 1;
    if ($level <= $times) {
      $this->_tmp = &$this->_data;
    } else {
      $this->_tmp = &$this->_names[$level - $times];
    }

    $this->_shiftTmp($path);
    return $this->_tmp;
  }

  /**
   * Returns value #/value
   * 
   * @param string $path Path
   * 
   * @return mixed
   */
  public function &getRoot($path = '')
  {
    $this->_tmp = &$this->_data;
    $this->_shiftTmp($path);
    return $this->_tmp;
  }

  /**
   * Pushes namespace.
   * 
   * @return null
   */
  public function nsPush()
  {
    $nsName = self::DELIMITER . (self::$_pushID++);

    $this->_data[$nsName] = array();
    $this->_current = &$this->_data[$nsName];
    $this->_names[] = &$this->_current;
    $this->_pushedData[] = $nsName;
  }

  /**
   * Pops namespace.
   * 
   * @return null
   */
  public function nsPop()
  {
    array_pop($this->_names);
    $this->_current = &$this->_names[sizeof($this->_names) - 1];
    unset($this->_data[array_pop($this->_pushedData)]);
  }

  /**
   * It shifts.
   * 
   * @param string $path Path
   * 
   * @return null
   */
  private function _shiftTmp($path)
  {
    if ($path) {
      $names = explode(self::DELIMITER, $path);
      foreach ($names as $name) {
        if (!is_array($this->_tmp)) {
          $this->_tmp = array();
        }
        if (!isset($this->_tmp[$name])) {
          $this->_tmp[$name] = null;
        }

        $this->_tmp = &$this->_tmp[$name];
      }
    }
  }

}

