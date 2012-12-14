<?php

require_once "JooS/Config/Adapter/Interface.php";

class JooS_Config_Adapter_PHPUnit_Testing implements JooS_Config_Adapter_Interface
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

  public function save($name, JooS_Config $config)
  {
    $this->_data[$name] = $config->valueOf();

    return true;
  }

}