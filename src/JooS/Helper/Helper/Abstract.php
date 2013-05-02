<?php

/**
 * @package JooS
 * @subpackage Helper
 */
namespace JooS\Helper;

/**
 * Helper abstract.
 */
abstract class Helper_Abstract implements Helper_Interface
{

  /**
   * @var Subject
   */
  private $_subject = null;

  /**
   * Set helper's subject
   * 
   * @param Subject $subject Subject
   * 
   * @return Helper_Abstract
   */
  final public function setSubject($subject)
  {
    $this->_subject = $subject;
    return $this;
  }

  /**
   * Returns helper's subject
   * 
   * @return Subject
   */
  final protected function getSubject()
  {
    return $this->_subject;
  }

}
