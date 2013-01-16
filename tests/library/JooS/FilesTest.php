<?php

namespace JooS;

require_once "JooS/Files.php";

class FilesTest extends \PHPUnit_Framework_TestCase
{

  public function testInstance()
  {
    $files = new Files();
    $this->assertTrue($files instanceof Files);
    
    $dir1 = $files->mkdir();
    $this->assertTrue(file_exists($dir1));
    $this->assertTrue(is_dir($dir1));
    $this->assertTrue(is_writable($dir1));
    
    $file1 = $files->tempnam();
    $this->assertFalse(file_exists($file1));
    $dir2 = dirname($file1);
    $this->assertTrue(is_writable($dir2));
    file_put_contents($file1, "qwerty1");

    $file2 = $files->tempnam();
    file_put_contents($file2, "qwerty2");
    $files->delete($file2);
    $this->assertFalse(file_exists($file2));
    
    unset($files);
    
    clearstatcache();
    $this->assertFalse(file_exists($dir1));
    $this->assertFalse(file_exists($file1));
  }

}
