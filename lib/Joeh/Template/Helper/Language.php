<?php
class Joeh_Template_Helper_Language extends Joeh_Template_Helper {

    private $path;

    public function getName() {
        return 'language';
    }

    public function _($key) {
        parse_ini_file($this->path . 
        return $key;
    }

    public function setTranslationPaths($path) {
        $this->path = $path;
    }
}
?>