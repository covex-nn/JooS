<?php

/**
 * @package JooS
 */

/**
 * Interface for Helpers
 */
interface JooS_Helper_Interface
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
   * @return JooS_Helper_Interface
   */
  public function setSubject($subject);

}
