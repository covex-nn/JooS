<?php

/**
 * @package JooS
 */

/**
 * Options helper-subject's interface
 */
interface JooS_Options_Subject
{
  
  /**
   * Return options object
   * 
   * @return JooS_Options
   */
  public function getOptions();
  
  /**
   * Return default options
   * 
   * @return array
   */
  public function getDefaultOptions();
  
}
