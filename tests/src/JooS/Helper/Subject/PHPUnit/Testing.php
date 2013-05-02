<?php

namespace JooS\Helper;

class Subject_PHPUnit_Testing implements Subject
{

  private $_helperBroker = null;

  public function helperBroker()
  {
    if ($this->_helperBroker === null) {
      $this->_helperBroker = Broker::newInstance($this);
    }
    return $this->_helperBroker;
  }

}
