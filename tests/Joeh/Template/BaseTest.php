<?php
require_once dirname(__FILE__) . "../../../config.php";
require_once "Joeh/Test/UnitTestCase.php";
require_once "Joeh/Template/Base.php";

class MyTemplate extends Joeh_Template_Base {

  public function basePath() {
    return ROOT_PATH . "assets/templates/";
  }

  public function compilePath() {
    return ROOT_PATH . "tmp/compiled/";
  }

  public function cachePath() {
    return ROOT_PATH . "tmp/cache";
  }
}

class Joeh_Template_BaseTest extends Joeh_Test_UnitTestCase {

  private $template;

  public function setUp() {
    $this->deleteTemps();

    $this->template = new MyTemplate();
  }

  public function testBasePath() {
    $this->assertNotNull($this->template->basePath());
  }

  public function testCompilePath() {
    $this->assertNotNull($this->template->compilePath());
  }

  public function testCachePath() {
    $this->assertNotNull($this->template->cachePath());
  }

  public function testRender() {
    $this->assertNotNull($this->template->render("name.tpl"));
  }

  public function testSyntaxError() {
    $this->setExpectedException("RuntimeException", "Syntax error in line 1");
    $this->template->render("syntax_error.tpl");
  }

  ##################
  ## PRIVATE METHODS
  ##################

  private function deleteTemps() {
    if(is_dir(ROOT_PATH . "tmp/compiled")) {
      if(file_exists(ROOT_PATH . "tmp/compiled/name.tpl.php")) {
        unlink(ROOT_PATH . "tmp/compiled/name.tpl.php");
      }
      rmdir(ROOT_PATH . "tmp/compiled");
    }

    if(is_dir(ROOT_PATH . "tmp/cache")) {
      rmdir(ROOT_PATH . "tmp/cache");
    }
  }
}
?>
