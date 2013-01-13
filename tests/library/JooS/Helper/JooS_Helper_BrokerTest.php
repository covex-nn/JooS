<?php

namespace JooS\Helper;

require_once "JooS/Helper/Broker.php";
require_once "JooS/Helper/Subject.php";
require_once "JooS/Helper/Helper/Abstract.php";

class BrokerTest extends \PHPUnit_Framework_TestCase
{

  public function testCallHelper_returnParam()
  {
    require_once "JooS/Helper/Subject/PHPUnit/Testing.php";

    $helperSubject = new Subject_PHPUnit_Testing();

    $helperSubject->helperBroker()->Helper_Object_PHPUnit_Testing1();

    $this->assertEquals(
      1, $helperSubject->helperBroker()
        ->Helper_Object_PHPUnit_Testing1
        ->func(1)
    );

    $this->assertEquals(
      "JooS\Helper\Subject_PHPUnit_Testing", $helperSubject->helperBroker()->Helper_Object_PHPUnit_Testing2->func()
    );
  }

  public function testArrayAccess()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();

    $this->assertTrue(isset($helperBroker["Helper_Object_PHPUnit_Testing1"]));
    $this->assertEquals("JooS\Helper_Object_PHPUnit_Testing1", get_class($helperBroker["Helper_Object_PHPUnit_Testing1"]));
  }

  /**
   * @todo надо сделать так, чтобы после этого тэста можно было всё вернуть обратно
   */
  public function testPrefixes()
  {
    Broker::clearPrefixes();

    $this->assertEquals(
      array(
        array(
          "dir" => "JooS/",
          "prefix" => "JooS\\"
        )
      ), 
      Broker::getPrefixes()
    );

    Broker::addPrefix("JooSX\\Test");

    $this->assertEquals(
      array(
        array(
          "dir" => "JooS/",
          "prefix" => "JooS\\"
        ),
        array(
          "dir" => "JooSX/Test/",
          "prefix" => "JooSX\\Test\\"
        )
      ),
      Broker::getPrefixes()
    );
  }

  public function testError_helperDoesNotExists()
  {
    $helperSubject = new Subject_PHPUnit_Testing();

    $this->assertTrue(!isset($helperSubject->Helper_Does_Not_Exists));
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helperDoesNotExists2()
  {
    $helperSubject = new Subject_PHPUnit_Testing();

    $a = $helperSubject->helperBroker()->Helper_Does_Not_Exists;
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helperMustImplement()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    $helperSubject->helperBroker()->Loader();
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helper__setForbidden()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    $helperSubject->helperBroker()->Helper_Subject_PHPUnit_Testing = 1;
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helper__unsetForbidden()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    unset($helperSubject->helperBroker()->Helper_Subject_PHPUnit_Testing);
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helperOffsetSetForbidden()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();
    $helperBroker["Helper_Subject_PHPUnit_Testing"] = 1;
  }

  /**
   * @expectedException JooS\Helper\Exception
   */
  public function testError_helperOffsetUnsetForbidden()
  {
    $helperSubject = new Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();
    unset($helperBroker["Helper_Subject_PHPUnit_Testing"]);
  }

}

