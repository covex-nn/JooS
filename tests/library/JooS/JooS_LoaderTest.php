<?php

require_once "JooS/Loader.php";

require_once "JooS/Config.php";

/**
 * Test class for JooS_Loader.
 */
class JooS_LoaderTest extends PHPUnit_Framework_TestCase
{

  public function testLoadClass()
  {
    $exists1 = JooS_Loader::loadClass("JooS_Exception");
    $this->assertTrue($exists1);

    $exists2 = JooS_Loader::loadClass("JooS_Exception_Class_Not_Exists");
    $this->assertFalse($exists2);

    $exists3 = JooS_Loader::loadClass("JooS_Loader");
    $this->assertTrue($exists3);
  }

  public function testGetClassName()
  {
    $this->assertEquals("qqq_Class1", JooS_Loader::getClassName("qqq", "Class1"));

    $this->assertEquals("qqq_Class1_Class2", JooS_Loader::getClassName("qqq", "claSS1_class2", true));
  }

}
