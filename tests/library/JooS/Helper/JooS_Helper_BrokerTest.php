<?php

require_once "JooS/Helper/Broker.php";
require_once "JooS/Helper/Subject.php";
require_once "JooS/Helper/Abstract.php";

/**
 * Test class for JooS_Helper_Broker.
 */
class JooS_Helper_BrokerTest extends PHPUnit_Framework_TestCase
{

  public function testCallHelper_returnParam()
  {
    require_once "JooS/Helper/Subject/PHPUnit/Testing.php";

    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();

    $helperSubject->helperBroker()->Helper_Object_PHPUnit_Testing1();

    $this->assertEquals(
      1, $helperSubject->helperBroker()
        ->Helper_Object_PHPUnit_Testing1
        ->func(1)
    );

    $this->assertEquals(
      "JooS_Helper_Subject_PHPUnit_Testing", $helperSubject->helperBroker()->Helper_Object_PHPUnit_Testing2->func()
    );
  }

  public function testArrayAccess()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();

    $this->assertTrue(isset($helperBroker["Helper_Object_PHPUnit_Testing1"]));
    $this->assertEquals("JooS_Helper_Object_PHPUnit_Testing1", get_class($helperBroker["Helper_Object_PHPUnit_Testing1"]));
  }

  /**
   * @todo надо сделать так, чтобы после этого тэста можно было всё вернуть обратно
   */
  public function testPrefixes()
  {
    JooS_Helper_Broker::clearPrefixes();

    $this->assertEquals(array(
      array(
        "dir" => "JooS" . DIRECTORY_SEPARATOR,
        "prefix" => "JooS_"
      )
      ), JooS_Helper_Broker::getPrefixes());

    JooS_Helper_Broker::addPrefix("JooSX_Test");

    $this->assertEquals(array(
      array(
        "dir" => "JooS" . DIRECTORY_SEPARATOR,
        "prefix" => "JooS_"
      ),
      array(
        "dir" => "JooSX" . DIRECTORY_SEPARATOR . "Test" . DIRECTORY_SEPARATOR,
        "prefix" => "JooSX_Test_"
      )
      ), JooS_Helper_Broker::getPrefixes());
  }

  public function testError_helperDoesNotExists()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();

    $this->assertTrue(!isset($helperSubject->Helper_Does_Not_Exists));
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helperDoesNotExists2()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();

    $a = $helperSubject->helperBroker()->Helper_Does_Not_Exists;
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helperMustImplement()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    $helperSubject->helperBroker()->Namespace();
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helper__setForbidden()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    $helperSubject->helperBroker()->Helper_Subject_PHPUnit_Testing = 1;
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helper__unsetForbidden()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    unset($helperSubject->helperBroker()->Helper_Subject_PHPUnit_Testing);
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helperOffsetSetForbidden()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();
    $helperBroker["Helper_Subject_PHPUnit_Testing"] = 1;
  }

  /**
   * @expectedException JooS_Helper_Exception
   */
  public function testError_helperOffsetUnsetForbidden()
  {
    $helperSubject = new JooS_Helper_Subject_PHPUnit_Testing();
    $helperBroker = $helperSubject->helperBroker();
    unset($helperBroker["Helper_Subject_PHPUnit_Testing"]);
  }

}

