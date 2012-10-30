<?php

  require_once "JooS/Config.php";

  /**
   * Test class for JooS_Config.
   */
  class JooS_ConfigTest extends PHPUnit_Framework_TestCase {

    public function testCreateSetUnset() {
      $dataArray = array(
        "e" => 1,
        "f" => 2
      );

      $c    = JooS_Config::getInstance("Qqq_Sss");
      $c->d = $dataArray;

      $this->assertEquals(1, $c->d->e());
      $this->assertEquals(2, $c->d->f());
      $this->assertEquals($dataArray, $c->d->valueOf());

      $g         = JooS_Config::qqq_Sss();
      $g["d"]->e = 3;
      unset($g->d->f);

      $this->assertEquals(3, $c->d->e());
      $this->assertEquals(null, $c->d->f());
      $this->assertEquals(array("e" => 3), $c->d());

      $this->assertEquals(array("d" => array("e" => 3)), $c());

      $this->assertTrue($c === $g);

      JooS_Config::clearInstance("Qqq_Sss");
    }

    public function testModified() {
      $c1 = JooS_Config::Qwerty1();

      $flag1 = $c1->is_modified();
      $c1->a = 1;
      $flag2 = $c1->is_modified();

      $this->assertTrue(!$flag1);
      $this->assertTrue($flag2);
      JooS_Config::clearInstance("Qwerty1");
    }

    public function testExistsClass() {
      $c1    = JooS_Config::Qwerty1();
      $c1->c = 1;

      $c2       = JooS_Config::PHPUnit_Testing();
      $flag3    = $c2->is_modified();
      $c2->a->b = $c1;
      $flag4    = $c2->is_modified();

      $this->assertEquals(1, $c2->a->b->c());
      $this->assertTrue(!$flag3);
      $this->assertTrue($flag4);

      JooS_Config::clearInstance("Qwerty1");
      JooS_Config::clearInstance("PHPUnit_Testing");
    }

    public function testNewInstance() {
      $data = array(
        "a" => 1,
        "b" => 2,
      );
      $c  = JooS_Config::newInstance("Qwerty1", $data);

      $this->assertEquals(1, $c->a());
      $this->assertEquals("JooSX_Config_Qwerty1", $c->get_class());

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
    public function testError_TypeMissmatch() {
      $c = JooS_Config::getInstance("Qwerty1");

      $c->a = (object) array();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testError_UseScalarValueAsArray() {
      $c    = JooS_Config::getInstance("Qwerty1");
      $c->a = 1;

      $c->a->b = 2;
    }

  }

  