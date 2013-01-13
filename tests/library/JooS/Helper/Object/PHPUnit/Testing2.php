<?php

namespace JooS;

use JooS\Helper\Helper_Abstract;

require_once "JooS/Helper/Helper/Abstract.php";

class Helper_Object_PHPUnit_Testing2 extends Helper_Abstract
{

  public static function init()
  {
    
  }

  public function func()
  {
    return get_class($this->getSubject());
  }

}
