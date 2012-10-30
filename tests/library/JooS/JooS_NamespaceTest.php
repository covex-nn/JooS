<?php

  require_once "JooS/Namespace.php";

  /**
   * Test class for JooS_Namespace.
   */
  class JooS_NamespaceTest extends PHPUnit_Framework_TestCase {
    /**
     * @var JooS_Namespace
     */
    protected $ns;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
      $this->ns = new JooS_Namespace();
      $this->ns->set($this->ns->getRoot("name"), 1);
    }

    public function testSet() {
      $this->assertEquals(1, $this->ns->get("name"));
      $this->assertEquals(1, $this->ns->getRoot("name"));
    }

    public function testSet2() {
      $this->ns->nsPush();

      $this->assertEquals(1, $this->ns->getBack(1, "name"));

      $this->ns->set($this->ns->get("qqq"), 2);
      $this->ns->set($this->ns->get("www"), array("eee" => "rrr"));

      $this->assertEquals(2, $this->ns->get("qqq"));
      $this->assertEquals(array("eee" => "rrr"), $this->ns->get("www"));
      $this->assertEquals("rrr", $this->ns->get("www/eee"));

      $this->ns->nsPush();

      $this->assertEquals(1, $this->ns->getBack(2, "name"));
      $this->assertEquals("rrr", $this->ns->getBack(1, "www/eee"));

      $this->ns->nsPop();
      $this->ns->nsPop();

      $this->assertEquals(1, $this->ns->get("name"));
      $this->assertEquals(1, $this->ns->getRoot("name"));
    }
  }
