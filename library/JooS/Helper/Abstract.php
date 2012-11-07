<?php

/**
 * @package JooS
 */
require_once "JooS/Helper/Interface.php";

/**
 * Helper abstract.
 */
abstract class JooS_Helper_Abstract implements JooS_Helper_Interface
{

  /**
   * @var JooS_Helper_Subject
   */
  private $_subject = null;

  /**
   * No initialization code yet. Can be overwrited.
   */
  public static function init()
  {
    
  }

  /**
   * Set helper's subject
   * 
   * @param JooS_Helper_Subject $subject
   * 
   * @return JooS_Helper_Abstract
   */
  final public function setSubject($subject)
  {
    $this->_subject = $subject;
    return $this;
  }

  /**
   * Returns helper's subject
   * 
   * @return JooS_Helper_Subject
   */
  final protected function getSubject()
  {
    return $this->_subject;
  }

}
