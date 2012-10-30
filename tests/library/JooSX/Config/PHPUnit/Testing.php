<?php

  require_once "JooS/Config.php";

  class JooSX_Config_PHPUnit_Testing extends JooS_Config {
    private static $_data = array(
      "a" => array()
    );

    public function __construct() {
      parent::__construct(self::$_data);
    }
  }
