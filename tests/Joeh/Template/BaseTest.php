<?php
/*
The MIT License

Copyright (c) 2008 Rafael Souza <rafael@joeh.com.br>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

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

  public function testRenderWithCache() {
    $this->assetNotNull($this->template->render('with_cache.tpl'));
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
