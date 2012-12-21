<?php

require_once "JooS/Object.php";

class JooS_Object_PHPUnit_Testing extends JooS_Object
{
  public function validateParam2($value)
  {
    return $value === 1;
  }
}