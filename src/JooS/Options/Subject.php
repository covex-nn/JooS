<?php

/**
 * @package JooS
 * @subpackage Helper
 */
namespace JooS;

/**
 * Options helper-subject's interface
 */
interface Options_Subject
{
  
  /**
   * Return options object
   * 
   * @return Options
   */
  public function getOptions();
  
  /**
   * Return default options
   * 
   * @return array
   */
  public function getDefaultOptions();
  
}
