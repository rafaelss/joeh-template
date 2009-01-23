<?php
require dirname(__FILE__) . '/config.php';
require 'Joeh/Template/Base.php';
require 'Joeh/Template/Helper/Cache.php';

class Template extends Joeh_Template_Base {

  public function __construct() {
    $this->basePath = dirname(__FILE__) . '/../assets/templates/';
    $this->compilePath = dirname(__FILE__) . '/../tmp/';
    $this->cachePath = $this->compilePath . 'cache/';

    $this->addHelper(new Joeh_Template_Helper_Cache($this));
  }
}

$tpl = new Template();
$tpl->render('with_cache');
?>
