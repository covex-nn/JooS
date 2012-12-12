<?php

require_once "JooS/Options.php";

require_once "JooS/Helper/Subject.php";

require_once "JooS/Options/Subject.php";

class JooS_OptionsTest extends PHPUnit_Framework_TestCase
  implements JooS_Helper_Subject, JooS_Options_Subject
{

  public function testInstance() {
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
  
  public function testNoSubject() {
    $options = new JooS_Options();
    
    $this->assertFalse(isset($options->qwerty));
  }
  
  private $_helperBroker = null;

  public function helperBroker() {
    if ($this->_helperBroker === null) {
      require_once "JooS/Helper/Broker.php";

      $this->_helperBroker = JooS_Helper_Broker::newInstance($this);
    }
    return $this->_helperBroker;
  }
  
  public function getDefaultOptions()
  {
    return array(
      "qwerty" => 1, 
    );
  }
}
