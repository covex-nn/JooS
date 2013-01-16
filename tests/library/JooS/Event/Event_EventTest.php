<?php

namespace JooS\Event;

use JooS\Config\Config;

require_once "JooS/Event/Event.php";

require_once "JooS/Event/PHPUnit/Testing.php";

class EventTest extends \PHPUnit_Framework_TestCase
{

  public function testCreate()
  {
    $event = PHPUnit_Testing::getInstance();
    $event->setParam1(1);

    $this->assertTrue(isset($event->param1));
    $this->assertEquals(1, $event->param1);
    unset($event->param1);
    $this->assertTrue(!isset($event->param1));

    $event->setParam2(2);
    $this->assertEquals(2, $event->param2);
    $event->setParam2(null);
    $this->assertTrue(!isset($event->param2));

    $this->assertEquals("JooS\\Event\\PHPUnit_Testing", $event->name());
  }

  public function testNotify()
  {
    $event = PHPUnit_Testing::getInstance();
    $event->setParam1(1);

    $this->assertEquals(0, sizeof($event->observers()));

    $var1 = 0;
    $observer = function(PHPUnit_Testing $event) use (&$var1)
      {
        $var1 = $event->param1;
      };

    $event->attach($observer);
    $this->assertEquals(1, sizeof($event->observers()));
    $this->assertEquals(array($observer), $event->observers());

    $event
      ->attach("observer_not_exists")
      ->notify()
      ->detach("observer_not_exists")
      ->detach($observer);

    $this->assertEquals(1, $var1);
    $this->assertEquals(0, sizeof($event->observers()));
  }

  public function testClearInstance()
  {
    $event = PHPUnit_Testing::getInstance();
    $event->attach(function(PHPUnit_Testing $event)
      {
        
      });

    $this->assertEquals(1, sizeof($event->observers()));

    Event::clearInstance("JooS\\Event\\PHPUnit_Testing");

    $event1 = PHPUnit_Testing::getInstance();
  }

  /**
   * @expectedException JooS\Event\Exception
   */
  public function testCancel()
  {
    $event = PHPUnit_Testing::getInstance();
    $event->cancel();
  }

  /**
   * @expectedException JooS\Event\Exception
   */
  public function testClone()
  {
    $event = clone PHPUnit_Testing::getInstance();
  }

  public function testSave()
  {
    $event1 = PHPUnit_Testing::getInstance();
    
    $config1 = Config::getInstance("JooS_Event_PHPUnit_Testing");
    $this->assertEquals(array(), $config1->valueOf());
    
    $event1->attach(
      array(__CLASS__, "observerPHPUnitTestingEvent")
    );
    
    $config2 = Config::getInstance("JooS_Event_PHPUnit_Testing");
    $this->assertEquals(array(), $config2->valueOf());
    $event1->save();
    
    $config3 = Config::getInstance("JooS_Event_PHPUnit_Testing");
    
    $this->assertEquals(
      array(
        array(__CLASS__, "observerPHPUnitTestingEvent")
      ),
      $config3->valueOf()
    );
    
    Event::clearInstance("JooS\\Event\\PHPUnit_Testing");
  }
  
  public static function observerPHPUnitTestingEvent(PHPUnit_Testing $event)
  {
    
  }
}
