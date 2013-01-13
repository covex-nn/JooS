<?php

namespace JooS;

require_once "JooS/Object.php";

class Object_PHPUnit_Testing extends Object
{
  public function validateParam2($value)
  {
    return $value === 1;
  }
}
