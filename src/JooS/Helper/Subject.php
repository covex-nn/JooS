<?php

/**
 * @package JooS
 * @subpackage Helper
 */
namespace JooS\Helper;

/**
 * Interface for Helper Subject
 */
interface Subject
{

  /**
   * creates Helper Broker for object
   * 
   * @return Broker
   */
  public function helperBroker();

}
