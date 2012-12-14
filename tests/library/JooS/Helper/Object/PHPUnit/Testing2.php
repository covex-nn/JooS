<?php

require_once "JooS/Helper/Abstract.php";

class JooS_Helper_Object_PHPUnit_Testing2 extends JooS_Helper_Abstract
{

  public static function init()
  {
    
  }

  public function func()
  {
    return get_class($this->getSubject());
  }

}
