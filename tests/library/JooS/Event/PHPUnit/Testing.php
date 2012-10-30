<?php

require_once "JooS/Event.php";

/**
 * @property param1
 */
class JooS_Event_PHPUnit_Testing extends JooS_Event {
  /**
   * @return JooS_Event_PHPUnit_Testing
   */
  public static function getInstance() {
    return parent::_getInstance(__CLASS__);
  }


  public static function getInstanceError() {
    return parent::_getInstance(__CLASS__ . "_NotAClass");
  }
}
