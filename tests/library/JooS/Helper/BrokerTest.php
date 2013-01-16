<?php

namespace JooS\Helper;

use JooS\Config\Config;

require_once "JooS/Helper/Broker.php";
require_once "JooS/Helper/Subject.php";
require_once "JooS/Helper/Helper/Abstract.php";

class BrokerTest extends \PHPUnit_Framework_TestCase
{
  
  /**
   * @var Broker
   */
  private $_broker;

  public function testCallHelper_returnParam()
  {

    $helperObject1 = $this->_broker->Helper_Object_PHPUnit_Testing1;
    $this->assertTrue($helperObject1 instanceof \JooS\Helper_Object_PHPUnit_Testing1);
    $this->assertEquals(1, $helperObject1->func(1));
    
    $helperObject2 = $this->_broker->Helper_Object_PHPUnit_Testing1;
    $this->assertEquals($helperObject1, $helperObject2);
    $this->assertTrue(isset($this->_broker->Helper_Object_PHPUnit_Testing1));

    $this->assertEquals(
      "JooS\Helper\Subject_PHPUnit_Testing", $this->_broker->Helper_Object_PHPUnit_Testing2->func()
    );
  }

  public function testPrefixes1()
  {
    $this->assertEquals(array("JooS"), $this->_broker->getPrefixes());
    
    $this->assertTrue(isset($this->_broker->Files));
    $this->assertFalse(isset($this->_broker->Object_PHPUnit_Testing3));
    
    $this->_broker->appendPrefix("JooS\\Helper");
    $this->assertEquals(array("JooS", "JooS\\Helper"), $this->_broker->getPrefixes());
    
    $this->assertTrue(isset($this->_broker->Files));
    $this->assertTrue(isset($this->_broker->Object_PHPUnit_Testing3));
    
    $this->_broker->deletePrefix("JooS");
    $this->assertEquals(array("JooS\\Helper"), $this->_broker->getPrefixes());
    
    $this->assertFalse(isset($this->_broker->Files));
    $this->assertTrue(isset($this->_broker->Object_PHPUnit_Testing3));
    
    $this->_broker->clearPrefixes();
    $this->assertEquals(array(), $this->_broker->getPrefixes());
    
    $this->assertFalse(isset($this->_broker->Files));
    $this->assertFalse(isset($this->_broker->Object_PHPUnit_Testing3));
  }

  public function testPrefixes2()
  {
    $this->assertEquals(array("JooS"), $this->_broker->getPrefixes());
    
    $this->_broker->prependPrefix("JooS\\Helper");
    $this->assertEquals(array("JooS\\Helper", "JooS"), $this->_broker->getPrefixes());
  }
  
  public function testError_helperDoesNotExists()
  {
    $this->assertTrue(!isset($this->_broker->Helper_Does_Not_Exists));
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helper__setForbidden()
  {
    $this->_broker->Helper_Subject_PHPUnit_Testing = 1;
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helper__unsetForbidden()
  {
    unset($this->_broker->Helper_Subject_PHPUnit_Testing);
  }

  /**
   * @var array
   */
  private $_prefixes = null;
  
  protected function setUp()
  {
    require_once "JooS/Config/Config.php";
    
    if (isset(Config::Helper_Broker()->prefixes)) {
      $this->_prefixes = Config::Helper_Broker()->prefixes();
    } else {
      $this->_prefixes = null;
    }
    Config::Helper_Broker()->prefixes = array();
    
    require_once "JooS/Helper/Subject/PHPUnit/Testing.php";
    
    $helperSubject = new Subject_PHPUnit_Testing();
    $this->_broker = $helperSubject->helperBroker();
  }
  
  protected function tearDown()
  {
    $this->_broker = null;
    
    if (is_null($this->_prefixes)) {
      unset(Config::Helper_Broker()->prefixes);
    } else {
      Config::Helper_Broker()->prefixes = $this->_prefixes;
    }
  }
  
}

