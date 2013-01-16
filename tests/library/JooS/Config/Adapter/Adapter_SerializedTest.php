<?php

namespace JooS\Config;

use JooS\Files;

require_once "JooS/Config/Adapter/Serialized.php";

require_once "JooS/Config/Config.php";

class Adapter_SerializedTest extends \PHPUnit_Framework_TestCase
{
  
  /**
   * @var Files
   */
  private $_files;

  public function testInstance()
  {
    $config1 = Config::getInstance("Test_Config1");
    $expected1 = array(
      "key1" => 1,
      "key2" => 3
    );
    $this->assertEquals($expected1, $config1->valueOf());

    $config2 = Config::getInstance("Test_Config2");
    $expected2 = array();
    $this->assertEquals($expected2, $config2->valueOf());

    $config1->key3 = 5;
    $save1 = Config::saveInstance("Test_Config1");
    $this->assertTrue($save1);

    Config::clearInstance("Test_Config1");

    $config3 = Config::getInstance("Test_Config1");
    $expected3 = array(
      "key1" => 1,
      "key2" => 3,
      "key3" => 5
    );
    $this->assertEquals($expected3, $config3->valueOf());

    Config::clearInstance("Test_Config1");
    Config::clearInstance("Test_Config2");
  }

  public function testDelete()
  {
    $config1 = Config::getInstance("Test_Config1");
    $value1 = $config1->valueOf();

    $this->assertFalse(sizeof($value1) == 0);

    $delete1 = Config::deleteInstance("Test_Config1");
    $this->assertTrue($delete1);

    $config2 = Config::getInstance("Test_Config1");
    $value2 = $config2->valueOf();

    $this->assertTrue(sizeof($value2) == 0);

    $delete2 = Config::deleteInstance("Test_Config1");
    $this->assertFalse($delete2);
  }

  protected function setUp()
  {
    if (is_null($this->_files)) {
      require_once "JooS/Files.php";

      $this->_files = new Files();
    }
    $dir = $this->_files->mkdir();
    
    $data = array("key1" => 1, "key2" => 3);
    file_put_contents($dir . "/test_config1.serialized", serialize($data));
    
    $dataAdapter = new Adapter_Serialized($dir);

    Config::setDataAdapter($dataAdapter);
  }

  protected function tearDown()
  {
    Config::clearDataAdapter();
  }

}
