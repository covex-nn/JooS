<?php

namespace JooS;

use JooS\Helper\Subject as Helper_Subject;
use JooS\Helper\Broker as Helper_Broker;

class OptionsTest extends \PHPUnit_Framework_TestCase implements Helper_Subject, Options_Subject
{

  public function testInstance()
  {
    $options = $this->helperBroker()->Options;

    $this->assertTrue(isset($options->qwerty));
    $this->assertEquals(1, $options->qwerty);

    unset($options->qwerty);
    $this->assertTrue(isset($options->qwerty));

    $options->qwerty = 2;
    $this->assertTrue(isset($options->qwerty));
    $this->assertEquals(2, $options->qwerty);

    unset($options->qwerty);
    $this->assertTrue(isset($options->qwerty));
    $this->assertEquals(1, $options->qwerty);

    $this->assertFalse(isset($options->asdf));
  }

  public function testNoSubject()
  {
    $options = new Options();

    $this->assertFalse(isset($options->qwerty));
  }

  public function testLoadJson()
  {
    $options = new Options();

    $load1 = $options->loadJson(__DIR__ . "/OptionsTest.json");

    $this->assertTrue($load1);
    $this->assertEquals(1, $options->param1);
    $this->assertEquals(array(1, "a"), $options->param2);
    $this->assertEquals(array("a" => false), $options->param3);

    $load2 = $options->loadJson(__FILE__);
    $this->assertFalse($load2);
  }

  public function testLoadCommandLine()
  {
    $options = new Options();

    $options->loadCommandLine(array(
      "--value1=2",
      "",
      "sdsdsd",
    ));

    $this->assertEquals(2, $options->value1);
  }

  private $_helperBroker = null;

  public function helperBroker()
  {
    if ($this->_helperBroker === null) {
      $this->_helperBroker = Helper_Broker::newInstance($this);
    }
    return $this->_helperBroker;
  }

  public function getDefaultOptions()
  {
    return array(
      "qwerty" => 1,
    );
  }

  /**
   * @return Options
   */
  public function getOptions()
  {
    $helperBroker = $this->helperBroker();

    return $helperBroker->Options;
  }

}
