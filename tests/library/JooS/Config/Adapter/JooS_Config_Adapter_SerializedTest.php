<?php

require_once "JooS/Config/Adapter/Serialized.php";

require_once "JooS/Config.php";

class JooS_Config_Adapter_SerializedTest extends PHPUnit_Framework_TestCase
{
  
  /**
   * @var JooS_Files
   */
  private $_files;

  public function testInstance()
  {
    $config1 = JooS_Config::getInstance("Test_Config1");
    $expected1 = array(
      "key1" => 1,
      "key2" => 3
    );
    $this->assertEquals($expected1, $config1->valueOf());

    $config2 = JooS_Config::getInstance("Test_Config2");
    $expected2 = array();
    $this->assertEquals($expected2, $config2->valueOf());

    $config1->key3 = 5;
    $save1 = JooS_Config::saveInstance("Test_Config1");
    $this->assertTrue($save1);

    JooS_Config::clearInstance("Test_Config1");

    $config3 = JooS_Config::getInstance("Test_Config1");
    $expected3 = array(
      "key1" => 1,
      "key2" => 3,
      "key3" => 5
    );
    $this->assertEquals($expected3, $config3->valueOf());

    JooS_Config::clearInstance("Test_Config1");
    JooS_Config::clearInstance("Test_Config2");
  }

  public function testDelete()
  {
    $config1 = JooS_Config::getInstance("Test_Config1");
    $value1 = $config1->valueOf();

    $this->assertFalse(sizeof($value1) == 0);

    $delete1 = JooS_Config::deleteInstance("Test_Config1");
    $this->assertTrue($delete1);

    $config2 = JooS_Config::getInstance("Test_Config1");
    $value2 = $config2->valueOf();

    $this->assertTrue(sizeof($value2) == 0);

    $delete2 = JooS_Config::deleteInstance("Test_Config1");
    $this->assertFalse($delete2);
  }

  protected function setUp()
  {
    if (is_null($this->_files)) {
      require_once "JooS/Files.php";

      $this->_files = new JooS_Files();
    }
    $dir = $this->_files->mkdir();
    
    $data = array("key1" => 1, "key2" => 3);
    file_put_contents($dir . "/test_config1.serialized", serialize($data));
    
    $dataAdapter = new JooS_Config_Adapter_Serialized($dir);

    JooS_Config::setDataAdapter($dataAdapter);
  }

  protected function tearDown()
  {
    JooS_Config::clearDataAdapter();
  }

}
