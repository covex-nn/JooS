<?php

  require_once "JooS/Loader.php";

  require_once "JooS/Config.php";

  /**
   * Test class for JooS_Loader.
   */
  class JooS_LoaderTest extends PHPUnit_Framework_TestCase {

    public function testLoadClass() {
      $exists1 = JooS_Loader::loadClass("JooS_Exception");
      $this->assertTrue($exists1);
      
      $exists2 = JooS_Loader::loadClass("JooS_Loader");
      $this->assertTrue($exists2);
    }

    public function testPaths() {
      $libraryPath = JooS_Config::PHPackager_Library()->path();
      $rootPath    = JooS_Config::PHPackager()->path();

      $this->assertEquals(implode(DIRECTORY_SEPARATOR, array(
          $rootPath,
          $libraryPath ? $libraryPath : "library",
          "Qwerty1",
          "Qwerty2.php",
        )), JooS_Loader::getRealPath("Qwerty1_Qwerty2"));
    }
    
    protected function tearDown() {
      JooS_Config::clearInstance("PHPackager_Library");
      JooS_Config::clearInstance("PHPackager");
    }
  }

  