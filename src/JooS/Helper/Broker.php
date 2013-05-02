<?php

/**
 * @package JooS
 * @subpackage Helper
 */
namespace JooS\Helper;

use JooS\Config\Config;
use JooS\Loader;

/**
 * Helper broker.
 */
final class Broker
{

  /**
   * Create new helper instance for subject
   * 
   * @param Subject $subject Subject
   * 
   * @return Broker
   */
  public static function newInstance(Subject $subject)
  {
    $instance = new self();
    /* @var $instance Broker */
    $instance->_subject = $subject;

    return $instance;
  }

  /**
   * @var Subject
   */
  private $_subject = null;

  /**
   * Private contructor
   */
  private function __construct()
  {
    $this->clearPrefixes();
    $this->appendPrefix("JooS");
    
    $prefixes = Config::Helper_Broker()->prefixes();
    if (is_array($prefixes)) {
      foreach ($prefixes as $prefix) {
        $this->appendPrefix($prefix);
      }
    }
  }

  /**
   * @var array
   */
  private $_helpers = array();
  
  /**
   * Returns helper
   * 
   * @param string $helper Name
   * 
   * @return Helper_Abstract
   */
  public function __get($helper)
  {
    if (isset($this->_helpers[$helper])) {
      $instance = $this->_helpers[$helper];
    } else {
      $instance = $this->_getHelper($helper);
      if (!is_null($instance)) {
        $this->_helpers[$helper] = $instance;
      }
    }
    return $instance;
  }

  /**
   * Is helper presents ?
   * 
   * @param string $helper Name
   * 
   * @return boolean
   */
  public function __isset($helper)
  {
    if (isset($this->_helpers[$helper])) {
      $isset = true;
    } else {
      $isset = !is_null($this->_getHelper($helper));
    }
    return $isset;
  }

  /**
   * Creates a new helper's instance
   * 
   * @param string $helper Name
   * 
   * @return Helper_Interface
   */
  private function _getHelper($helper)
  {
    $instance = null;
    foreach ($this->_prefixes as $prefix) {
      $className = $prefix . "\\" . $helper;
      if (Loader::loadClass($className)) {
        if (is_subclass_of($className, __NAMESPACE__ . "\\Helper_Interface")) {
          $instance = new $className();
          /* @var $instance Helper_Interface */
          $instance->setSubject($this->_subject);
        }
        break;
      }
    }
    return $instance;
  }
  
  /**
   * @var array
   */
  private $_prefixes = array();
  
  /**
   * Returns all available helper classNames prefixes
   * 
   * @return array
   */
  public function getPrefixes()
  {
    return array_values($this->_prefixes);
  }
  
  /**
   * Append namespace-prefix
   * 
   * @param string $prefix Prefix
   * 
   * @return null
   */
  public function appendPrefix($prefix)
  {
    $this->deletePrefix($prefix);
    $this->_prefixes[$prefix] = $prefix;
  }
  
  /**
   * Prepend namespace-prefix
   * 
   * @param string $prefix Prefix
   * 
   * @return null
   */
  public function prependPrefix($prefix)
  {
    $this->deletePrefix($prefix);
    
    $prepend = array($prefix => $prefix);
    $this->_prefixes = array_merge($prepend, $this->_prefixes);
  }
  
  /**
   * Delete namespace-prefix
   * 
   * @param string $prefix Prefix
   * 
   * @return null
   */
  public function deletePrefix($prefix)
  {
    unset($this->_prefixes[$prefix]);
  }

  /**
   * Clears all helpers classNames prefixes
   * 
   * @return null
   */
  public function clearPrefixes()
  {
    $this->_prefixes = array();
  }

  /**
   * It is not allowed to create Helper outside Broker class.
   * 
   * @param string $name  Name
   * @param mixed  $value Value
   * 
   * @return null
   * @throws Exception
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function __set($name, $value)
  {
    throw new Exception("Forbidden");
  }

  /**
   * It it not allowed to unload helper.
   * 
   * @param string $name Name
   * 
   * @return null
   * @throws Exception
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function __unset($name)
  {
    throw new Exception("Forbidden");
  }

}
