<?php

require_once "JooS\Helper\Subject.php";

class JooS_Helper_Subject_PHPUnit_Testing implements JooS_Helper_Subject
{

  private $_helperBroker = null;

  public function helperBroker()
  {
    if ($this->_helperBroker === null) {
      require_once "JooS/Helper/Broker.php";

      $this->_helperBroker = JooS_Helper_Broker::newInstance($this);
    }
    return $this->_helperBroker;
  }

}
