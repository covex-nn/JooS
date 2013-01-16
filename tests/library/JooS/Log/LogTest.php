<?php

namespace JooS\Log;

use JooS\Config\Config;

require_once "JooS/Log/Log.php";

require_once "JooS/Config/Config.php";

class LogTest extends \PHPUnit_Framework_TestCase
{

  public function testWriters()
  {
    Config::newInstance("JooS_Log", array(
      "writers" => array(
      )
    ));
    
    $writers1 = Log::getWriters();
    $this->assertEquals(0, sizeof($writers1));
    
    require_once "JooS/Log/None.php";
    
    $writerNull = new None();
    
    $add1 = Log::addWriter($writerNull);
    $this->assertTrue($add1);
    
    $add2 = Log::addWriter($writerNull);
    $this->assertFalse($add2);
    
    $writers2 = Log::getWriters();
    $this->assertEquals(1, sizeof($writers2));
    
    $writer = array_shift($writers2);
    $this->assertEquals($writerNull, $writer);
  }

  public function testObserver()
  {
    Config::newInstance("JooS_Log", array(
      "writers" => array(
        "output", "null"
      )
    ));
    
    require_once "JooS/Log/Log/Event.php";
    
    $event = Log_Event::getInstance();
    $event->message = "qwerty";
    
    ob_start();
    
    Log::observer($event);
    
    $text = ob_get_contents();
    ob_clean();
    
    $this->assertEquals("qwerty" . PHP_EOL, $text);
  }
  
  protected function setUp()
  {
    Log::clearWriters();
  }
  
  protected function tearDown()
  {
    Config::clearInstance("JooS_Log");
    
    Log::clearWriters();
  }
  
}
