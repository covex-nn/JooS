<?php

namespace JooS\Config;

class Adapter_PHPUnit_Testing implements Adapter_Interface
{

  /**
   * @var array
   */
  private $_data = null;

  public function __construct(array $data)
  {
    $this->_data = $data;
  }

  public function load($name)
  {
    if (isset($this->_data[$name])) {
      $data = $this->_data[$name];
    } else {
      $data = array();
    }
    return $data;
  }

  public function save($name, Config $config)
  {
    $this->_data[$name] = $config->valueOf();

    return true;
  }
  
  public function delete($name)
  {
    unset($this->_data[$name]);
  }

}