<?php

namespace JooS;

require_once "JooS/Object/PHPUnit/Testing.php";

class ObjectTest extends \PHPUnit_Framework_TestCase
{

  public function testObject()
  {
    $object = new Object_PHPUnit_Testing();
    $object->param1 = 1;

    $this->assertEquals(1, $object->param1);
    $this->assertTrue(isset($object->param1));

    unset($object->param1);
    $this->assertFalse(isset($object->param1));
  }

  /**
   * @expectedException JooS\Object_Exception
   */
  public function testValidator1()
  {
    $object = new Object_PHPUnit_Testing();
    $object->param2 = 2;
  }

  public function testValidator2()
  {
    $object = new Object_PHPUnit_Testing();
    $object->param2 = 1;

    $this->assertEquals(1, $object->param2);
  }

  public function testCall()
  {
    $object = new Object_PHPUnit_Testing();

    $call1 = $object->setParamParam3(2);
    $this->assertEquals($object, $call1);
    $this->assertEquals(2, $object->paramParam3);

    $object->setParam4(null);
    $this->assertTrue(is_null($object->param4));

    $call2 = $object->function_does_not_exists();
    $this->assertEquals(null, $call2);
  }

}
