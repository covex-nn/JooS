<?php

namespace JooS\Config;

require_once __DIR__ . "/Adapter/PHPUnit/Testing.php";

class ConfigTest extends \PHPUnit_Framework_TestCase
{

  public function testCreateSetUnset()
  {
    $dataArray = array(
      "e" => 1,
      "f" => 2
    );

    $c = Config::getInstance("Qqq_Sss");
    $c->d = $dataArray;

    $this->assertEquals(1, $c->d->e());
    $this->assertEquals(2, $c->d->f());
    $this->assertEquals($dataArray, $c->d->valueOf());

    $g = Config::qqq_Sss();
    $g["d"]->e = 3;
    unset($g->d->f);

    $this->assertEquals(3, $c->d->e());
    $this->assertEquals(null, $c->d->f());
    $this->assertEquals(array("e" => 3), $c->d());

    $this->assertEquals(array("d" => array("e" => 3)), $c());

    $this->assertTrue($c === $g);

    Config::clearInstance("Qqq_Sss");
  }

  public function testExistsClass()
  {
    $c1 = Config::Qwerty1();
    $c1->c = 1;

    $c2 = Config::newInstance("Qwerty2", array(
        "a" => array()
      ));

    $c2->a->b = $c1;

    $this->assertEquals(1, $c2->a->b->c());

    Config::clearInstance("Qwerty1");
    Config::clearInstance("Qwerty2");
  }

  public function testNewInstance()
  {
    $data = array(
      "a" => 1,
      "b" => 2,
    );
    $c = Config::newInstance("Qwerty1", $data);

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

    Config::clearInstance("Qwerty1");
  }

  /**
   * @expectedException PHPUnit_Framework_Error
   */
  public function testError_TypeMissmatch()
  {
    $c = Config::getInstance("Qwerty1");

    $c->a = (object) array();
  }

  /**
   * @expectedException PHPUnit_Framework_Error
   */
  public function testError_UseScalarValueAsArray()
  {
    $c = Config::getInstance("Qwerty1");
    $c->a = 1;

    $c->a->b = 2;
  }

  public function testDataAdapter()
  {
    $delete1 = Config::deleteInstance("Testing1");
    $this->assertFalse($delete1);
    
    $dataAdapter = new Adapter_PHPUnit_Testing(
        array(
          "testing1" => array("qwerty" => "asdf"),
        )
    );

    Config::setDataAdapter($dataAdapter);
    $this->assertEquals($dataAdapter, Config::getDataAdapter());

    $config1 = Config::Testing1();
    $this->assertEquals("asdf", $config1->qwerty->valueOf());

    $config1->qwerty = "zxcv";
    Config::saveInstance("Testing1");
    Config::clearInstance("Testing1");

    $config2 = Config::Testing1();
    $this->assertEquals(array("qwerty" => "zxcv"), $config2->valueOf());
    
    $config2->qwerty = "qqq";
    $config2->save();
    Config::clearInstance("Testing1");

    $config3 = Config::Testing1();
    $this->assertEquals(array("qwerty" => "qqq"), $config3->valueOf());
    
    Config::deleteInstance("Testing1");

    $config4 = Config::Testing1();
    $this->assertEquals(array(), $config4->valueOf());
    
    Config::clearInstance("Testing1");
    Config::clearDataAdapter();
    $this->assertEquals(null, Config::getDataAdapter());
  }

  public function testClearAll()
  {
    $c1 = Config::getInstance("test");
    $c1->value = 1;

    Config::clearAll();

    $c2 = Config::getInstance("test");
    $this->assertFalse(isset($c2->value));

    Config::clearDataAdapter();

    $save1 = Config::saveInstance("test");
    $this->assertFalse($save1);
  }

}

