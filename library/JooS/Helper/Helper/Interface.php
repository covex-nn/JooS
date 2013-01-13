<?php

/**
 * @package JooS
 * @subpackage Helper
 */
namespace JooS\Helper;

/**
 * Interface for Helpers
 */
interface Helper_Interface
{

  /**
   * Initialization
   * 
   * @return null
   */
  public static function init();

  /**
   * Set subject.
   * 
   * @param type $subject Subject
   * 
   * @return Helper_Interface
   */
  public function setSubject($subject);

}
