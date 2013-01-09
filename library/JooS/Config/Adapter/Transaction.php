<?php

/**
 * @package JooS
 * @subpackage Deploy
 */
require_once "JooS/Config/Adapter/Interface.php";

/**
 * Transaction config adapter.
 */
class JooS_Config_Adapter_Transaction implements JooS_Config_Adapter_Interface
{

  /**
   * @var JooS_Config_Adapter_Interface
   */
  private $_stored;
  
  /**
   * @var JooS_Config_Adapter_Transaction
   */
  private static $_transaction = null;
  
  /**
   * @var array
   */
  private $_data;
  
  /**
   * Constructor
   */
  protected function __construct()
  {
    $this->_data = array();
  }
  
  /**
   * Load config data
   * 
   * @param string $name Config name
   * 
   * @return array
   */
  public function load($name)
  {
    if (isset($this->_data[$name])) {
      if (is_null($this->_data[$name])) {
        $data = array();
      } else {
        $data = $this->_data[$name];
      }
    } else {
      if (is_null($this->_stored)) {
        $data = array();
      } else {
        $data = $this->_stored->load($name);
      }
    }
    
    return $data;
  }
  
  /**
   * Save config data
   * 
   * @param string      $name   Config name
   * @param JooS_Config $config Config data
   * 
   * @return boolean
   */
  public function save($name, JooS_Config $config)
  {
    $this->_data[$name] = $config;
    
    return true;
  }
  
  /**
   * Delete config data
   * 
   * @param string $name Config name
   * 
   * @return boolean
   */
  public function delete($name)
  {
    $this->_data[$name] = null;
    
    return true;
  }
  
  /**
   * Start transaction and save current config adapter
   * 
   * @return boolean
   */
  public static function start()
  {
    if (is_null(self::$_transaction)) {
      require_once "JooS/Config.php";
      
      $object = self::$_transaction = new self();
      $object->_stored = JooS_Config::getDataAdapter();
      
      JooS_Config::setDataAdapter($object);
      
      $result = true;
    } else {
      $result = false;
    }
    
    return $result;
  }

  /**
   * Cancel transaction
   * 
   * @return boolean
   */
  public static function cancel()
  {
    if (is_null(self::$_transaction)) {
      $result = false;
    } else {
      $result = true;

      $aTran = self::$_transaction;
      $aCurr = $aTran->_stored;

      require_once "JooS/Config.php";
      
      if (!is_null($aCurr)) {
        $names = array_keys($aTran->_data);
        foreach ($names as $name) {
          JooS_Config::clearInstance($name);
        }
      }
      
      self::_stop();
      self::$_transaction = null;
    }
    
    return $result;
  }
  
  /**
   * Commit transaction
   * 
   * @return boolean
   */
  public static function commit()
  {
    if (is_null(self::$_transaction)) {
      $result = false;
    } else {
      $result = true;
      
      $aTran = self::$_transaction;
      $aCurr = $aTran->_stored;
      
      if (!is_null($aCurr)) {
        foreach ($aTran->_data as $name => $value) {
          /* @var $value JooS_Config */
          if (is_null($value)) {
            $aCurr->delete($name);
          } else {
            $aCurr->save($name, $value);
          }
        }
      }
      
      self::_stop();
      self::$_transaction = null;
    }
    
    return $result;
  }
  
  /**
   * Restore old data adapter
   * 
   * @return null
   */
  private static function _stop()
  {
    $adapter = self::$_transaction->_stored;
    
    require_once "JooS/Config.php";

    if (is_null($adapter)) {
      JooS_Config::clearDataAdapter();
    } else {
      JooS_Config::setDataAdapter($adapter);
    }
  }
  
}
