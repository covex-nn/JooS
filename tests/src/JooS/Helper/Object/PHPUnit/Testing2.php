<?php

namespace JooS;

use JooS\Helper\Helper_Abstract;

class Helper_Object_PHPUnit_Testing2 extends Helper_Abstract
{

  public function func()
  {
    return get_class($this->getSubject());
  }

}
