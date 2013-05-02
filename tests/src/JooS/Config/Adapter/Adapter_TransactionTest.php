<?php

namespace JooS\Config;

require_once __DIR__ . "/PHPUnit/Testing.php";

class Adapter_TransactionTest extends \PHPUnit_Framework_TestCase
{

  public function testInstance()
  {
    $start1 = Adapter_Transaction::start();
    $this->assertTrue($start1);
    $start2 = Adapter_Transaction::start();
    $this->assertFalse($start2);

    $commit1 = Adapter_Transaction::commit();
    $this->assertTrue($commit1);
    $commit2 = Adapter_Transaction::commit();
    $this->assertFalse($commit2);
    $cancel1 = Adapter_Transaction::cancel();
    $this->assertFalse($cancel1);
  }

  public function testCommit()
  {
    Adapter_Transaction::start();

    $config1 = Config::getInstance("Test");
    $config1->qqq = "www";

    Config::saveInstance("Test");

    Adapter_Transaction::commit();

    Config::clearInstance("Test");

    $config2 = Config::getInstance("Test");
    $this->assertEquals("www", $config2->qqq->valueOf());

    Adapter_Transaction::start();

    Config::deleteInstance("Test");

    Adapter_Transaction::commit();

    $config3 = Config::getInstance("Test");
    $this->assertEquals(array(), $config3->valueOf());
  }

  public function testCancel()
  {
    Adapter_Transaction::start();

    $config1 = Config::getInstance("Test");
    $config1->qqq = "www";
    Config::saveInstance("Test");

    $cancel1 = Adapter_Transaction::cancel();
    $this->assertTrue($cancel1);

    $config2 = Config::getInstance("Test");
    $this->assertEquals(array(), $config2->valueOf());
  }

  protected function setUp()
  {
    $adapter = new Adapter_PHPUnit_Testing(array());
    
    Config::clearAll();
    Config::setDataAdapter($adapter);
  }

  protected function tearDown()
  {
    Config::clearDataAdapter();
  }

}
