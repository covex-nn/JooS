<?php

require_once "JooS/Config/Adapter/Transaction.php";

class JooS_Config_Adapter_TransactionTest extends PHPUnit_Framework_TestCase
{

  public function testInstance()
  {
    $start1 = JooS_Config_Adapter_Transaction::start();
    $this->assertTrue($start1);
    $start2 = JooS_Config_Adapter_Transaction::start();
    $this->assertFalse($start2);

    $commit1 = JooS_Config_Adapter_Transaction::commit();
    $this->assertTrue($commit1);
    $commit2 = JooS_Config_Adapter_Transaction::commit();
    $this->assertFalse($commit2);
    $cancel1 = JooS_Config_Adapter_Transaction::cancel();
    $this->assertFalse($cancel1);
  }

  public function testCommit()
  {
    JooS_Config_Adapter_Transaction::start();

    $config1 = JooS_Config::getInstance("Test");
    $config1->qqq = "www";

    JooS_Config::saveInstance("Test");

    JooS_Config_Adapter_Transaction::commit();

    JooS_Config::clearInstance("Test");

    $config2 = JooS_Config::getInstance("Test");
    $this->assertEquals("www", $config2->qqq->valueOf());

    JooS_Config_Adapter_Transaction::start();

    JooS_Config::deleteInstance("Test");

    JooS_Config_Adapter_Transaction::commit();

    $config3 = JooS_Config::getInstance("Test");
    $this->assertEquals(array(), $config3->valueOf());
  }

  public function testCancel()
  {
    JooS_Config_Adapter_Transaction::start();

    $config1 = JooS_Config::getInstance("Test");
    $config1->qqq = "www";
    JooS_Config::saveInstance("Test");

    $cancel1 = JooS_Config_Adapter_Transaction::cancel();
    $this->assertTrue($cancel1);

    $config2 = JooS_Config::getInstance("Test");
    $this->assertEquals(array(), $config2->valueOf());
  }

  protected function setUp()
  {
    require_once "JooS/Config/Adapter/PHPUnit/Testing.php";
    
    $adapter = new JooS_Config_Adapter_PHPUnit_Testing(array());
    
    require_once "JooS/Config.php";

    JooS_Config::clearAll();
    JooS_Config::setDataAdapter($adapter);
  }

  protected function tearDown()
  {
    JooS_Config::clearDataAdapter();
  }

}
