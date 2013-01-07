<?php

require_once "JooS/Log.php";

require_once "JooS/Config.php";

class JooS_LogTest extends PHPUnit_Framework_TestCase
{

  public function testWriters()
  {
    JooS_Config::newInstance("JooS_Log", array(
      "writers" => array(
      )
    ));
    
    $writers1 = JooS_Log::getWriters();
    $this->assertEquals(0, sizeof($writers1));
    
    require_once "JooS/Log/Null.php";
    
    $writerNull = new JooS_Log_Null();
    
    $add1 = JooS_Log::addWriter($writerNull);
    $this->assertTrue($add1);
    
    $add2 = JooS_Log::addWriter($writerNull);
    $this->assertFalse($add2);
    
    $writers2 = JooS_Log::getWriters();
    $this->assertEquals(1, sizeof($writers2));
    
    $writer = array_shift($writers2);
    $this->assertEquals($writerNull, $writer);
  }

  public function testObserver()
  {
    JooS_Config::newInstance("JooS_Log", array(
      "writers" => array(
        "output", "null"
      )
    ));
    
    require_once "JooS/Event/Log.php";
    
    $event = JooS_Event_Log::getInstance();
    $event->message = "qwerty";
    
    ob_start();
    
    JooS_Log::observer($event);
    
    $text = ob_get_contents();
    ob_clean();
    
    $this->assertEquals("qwerty" . PHP_EOL, $text);
  }
  
  protected function setUp()
  {
    JooS_Log::clearWriters();
  }
  
  protected function tearDown()
  {
    JooS_Config::clearInstance("JooS_Log");
    
    JooS_Log::clearWriters();
  }
  
}
