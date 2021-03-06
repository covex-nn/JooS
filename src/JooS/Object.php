<?php

/**
 * @package JooS
 */
namespace JooS;

/**
 * ArrayObject
 */
class Object
{
  
  /**
   * @var array
   */
  private $_data = array();
  
  /**
   * Magic method __set
   * 
   * @param string $name  Name
   * @param mixed  $value Value
   * 
   * @return null
   * @throws Object_Exception
   */
  public function __set($name, $value)
  {
    $method = "validate" . ucfirst($name);
    if (method_exists($this, $method)) {
      $validator = array($this, $method);
      /**
       * Валидатор должен возвращать FALSE в случае неверного значения
       */
      $valid = call_user_func($validator, $value) !== false;
    } else {
      $valid = true;
    }
    
    if ($valid) {
      $this->_data[$name] = $value;
    } else {
      throw new Object_Exception("Type mismatch for '$name'");
    }
  }
  
  /**
   * Magic method __call
   * 
   * @param string $name      Method name
   * @param array  $arguments Arguments
   * 
   * @return JooS_Object
   */
  public function __call($name, $arguments)
  {
    if (substr($name, 0, 3) === "set") {
      $name = strtolower(substr($name, 3, 1)) . substr($name, 4);
      
      if (isset($arguments[0])) {
        $value = $arguments[0];
      } else {
        $value = null;
      }

      $this->__set($name, $value);
      
      return $this;
    }
  }
  
  /**
   * Magic method __isset
   * 
   * @param string $name Name
   * 
   * @return boolean
   */
  public function __isset($name)
  {
    return isset($this->_data[$name]);
  }
  
  /**
   * Magic methos __get
   * 
   * @param string $name Name
   * 
   * @return mixed
   */
  public function __get($name)
  {
    return $this->_data[$name];
  }
  
  /**
   * Magic method __unset
   * 
   * @param string $name Name
   * 
   * @return null
   */
  public function __unset($name)
  {
    unset($this->_data[$name]);
  }
  
}
