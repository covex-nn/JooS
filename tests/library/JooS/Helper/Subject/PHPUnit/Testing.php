<?php

namespace JooS\Helper;

require_once "JooS\Helper\Subject.php";

class Subject_PHPUnit_Testing implements Subject
{

  private $_helperBroker = null;

  public function helperBroker()
  {
    if ($this->_helperBroker === null) {
      require_once "JooS/Helper/Broker.php";

      $this->_helperBroker = Broker::newInstance($this);
    }
    return $this->_helperBroker;
  }

}
