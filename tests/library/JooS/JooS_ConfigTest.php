<?php

require_once "JooS/Config.php";

/**
 * Test class for JooS_Config.
 */
class JooS_ConfigTest extends PHPUnit_Framework_TestCase
{

  public function testCreateSetUnset()
  {
    $dataArray = array(
      "e" => 1,
      "f" => 2
    );

    $c = JooS_Config::getInstance("Qqq_Sss");
    $c->d = $dataArray;

    $this->assertEquals(1, $c->d->e());
    $this->assertEquals(2, $c->d->f());
    $this->assertEquals($dataArray, $c->d->valueOf());

    $g = JooS_Config::qqq_Sss();
    $g["d"]->e = 3;
    unset($g->d->f);

    $this->assertEquals(3, $c->d->e());
    $this->assertEquals(null, $c->d->f());
    $this->assertEquals(array("e" => 3), $c->d());

    $this->assertEquals(array("d" => array("e" => 3)), $c());

    $this->assertTrue($c === $g);

    JooS_Config::clearInstance("Qqq_Sss");
  }

  public function testExistsClass()
  {
    $c1 = JooS_Config::Qwerty1();
    $c1->c = 1;

    $c2 = JooS_Config::newInstance("Qwerty2", array(
        "a" => array()
      ));

    $c2->a->b = $c1;

    $this->assertEquals(1, $c2->a->b->c());

    JooS_Config::clearInstance("Qwerty1");
    JooS_Config::clearInstance("Qwerty2");
  }

  public function testNewInstance()
  {
    $data = array(
      "a" => 1,
      "b" => 2,
    );
    $c = JooS_Config::newInstance("Qwerty1", $data);

    $this->assertEquals(1, $c->a());

    $checkString = "";
    foreach ($c as $key => $value) {
      $checkString .= "[$key:" . $value->valueOf() . "]";
    }
    $this->assertEquals("[a:1][b:2]", $checkString);

    $c["a"] = 3;
    $this->assertEquals(3, $c->a());

    $this->assertTrue(isset($c["a"]));
    unset($c["a"]);
    $this->assertTrue(!isset($c["a"]));

    JooS_Config::clearInstance("Qwerty1");
  }

  /**
   * @expectedException PHPUnit_Framework_Error
   */
  public function testError_TypeMissmatch()
  {
    $c = JooS_Config::getInstance("Qwerty1");

    $c->a = (object) array();
  }

  /**
   * @expectedException PHPUnit_Framework_Error
   */
  public function testError_UseScalarValueAsArray()
  {
    $c = JooS_Config::getInstance("Qwerty1");
    $c->a = 1;

    $c->a->b = 2;
  }

  public function testDataAdapter()
  {
    require_once "JooS/Config/Adapter/PHPUnit/Testing.php";

    $dataAdapter = new JooS_Config_Adapter_PHPUnit_Testing(
        array(
          "testing1" => array("qwerty" => "asdf"),
        )
    );

    JooS_Config::setDataAdapter($dataAdapter);
    $this->assertEquals($dataAdapter, JooS_Config::getDataAdapter());

    $config1 = JooS_Config::Testing1();
    $this->assertEquals("asdf", $config1->qwerty->valueOf());

    $config1->qwerty = "zxcv";
    JooS_Config::saveInstance("Testing1");
    JooS_Config::clearInstance("Testing1");

    $config2 = JooS_Config::Testing1();
    $this->assertEquals(array("qwerty" => "zxcv"), $config2->valueOf());

    JooS_Config::clearInstance("Testing1");
    JooS_Config::clearDataAdapter();
    $this->assertEquals(null, JooS_Config::getDataAdapter());
  }

  public function testClearAll()
  {
    $c1 = JooS_Config::getInstance("test");
    $c1->value = 1;

    JooS_Config::clearAll();

    $c2 = JooS_Config::getInstance("test");
    $this->assertFalse(isset($c2->value));

    JooS_Config::clearDataAdapter();

    $save1 = JooS_Config::saveInstance("test");
    $this->assertFalse($save1);
  }

}

