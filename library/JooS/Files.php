<?php

/**
 * @package JooS
 */
require_once "JooS/Helper/Abstract.php";

/**
 * File/directory operations helper.
 */
class JooS_Files extends JooS_Helper_Abstract
{

  /**
   * @var string
   */
  private $_systemTempDirectory = null;
  
  /**
   * @var int
   */
  private $_tempnamCounter = 0;

  /**
   * Destructor
   */
  public function __destruct()
  {
    $dir = $this->_sysGetTempDir(false);
    if (!is_null($dir)) {
      $this->delete($dir);
    }
  }
  
  /**
   * Creates directory in sys_get_temp_dir()
   * 
   * @param int $mode Mode
   * 
   * @return string
   */
  public function mkdir($mode = 0777)
  {
    $name = $this->tempnam();
    mkdir($name, $mode);
    
    return $name;
  }
  
  /**
   * Return unique filename
   *
   * @return string
   */
  public function tempnam()
  {
    $sysTempDir = $this->_sysGetTempDir();
    do {
      $this->_tempnamCounter++;
      $name = $sysTempDir . "/" . $this->_tempnamCounter;
    } while (file_exists($name));
    
    return $name;
  }
  
  /**
   * Delete file or directory
   * 
   * @param string $path Path
   * 
   * @return null
   */
  public function delete($path)
  {
    if (is_file($path)) {
      unlink($path);
    } else {
      $iteratorRd = new RecursiveDirectoryIterator($path);
      $iteratorRi = new RecursiveIteratorIterator(
        $iteratorRd, RecursiveIteratorIterator::CHILD_FIRST
      );
      foreach ($iteratorRi as $file) {
        /* @var $file SplFileInfo */
        if ($file->isDir()) {
          rmdir($file->getPathname());
        } else {
          unlink($file->getPathname());
        }
      }
      rmdir($path);
    }
  }
  
  /**
   * Return path to own temp directory
   * 
   * @param boolean $create Create new folder ?
   * 
   * @return string
   */
  private function _sysGetTempDir($create = true)
  {
    if (is_null($this->_systemTempDirectory) && $create) {
      $sysTmpDir = rtrim(sys_get_temp_dir(), "\\/");
      
      do {
        $name = $sysTmpDir . "/" . uniqid("fs", true);
      } while (file_exists($name));

      mkdir($name, 0777);
      $this->_systemTempDirectory = $name;
    }
    return $this->_systemTempDirectory;
  }
  
}