<?php

namespace JooS;

/**
 * Test class for JooS_Loader.
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{

  public function testLoadClass()
  {
    $exists1 = Loader::loadClass('\JooS\VarSpace');
    $this->assertTrue($exists1);

    $exists2 = Loader::loadClass('JooS_Exception_Class_Not_Exists');
    $this->assertFalse($exists2);

    $exists3 = Loader::loadClass('PHPUnit_Framework_TestCase');
    $this->assertTrue($exists3);
  }

  public function testGetClassName()
  {
    $this->assertEquals("qqq_Class1", Loader::getClassName("qqq", "Class1"));

    $this->assertEquals("NS\\qqq_Class1", Loader::getClassName("NS\\qqq", 'Class1'));
    
    $this->assertEquals("Qqq_Class1_Class2", Loader::getClassName("qqq", "claSS1_class2", true));

    $this->assertEquals("Ns1\\Ns2\\Class1_Class2", Loader::getClassName("ns1\\ns2\\", "class1_CLASS2", true));
    
    $this->assertEquals("Ns1\\Ns2\\Class1_Class2", Loader::getClassName("ns1\\", "ns2\\class1_Class2", true));

    $this->assertEquals("\\Ns1_ns2\\Class1_Class2", Loader::getClassName("\\ns1_NS2\\", "class1_Class2", true));
  }

}
