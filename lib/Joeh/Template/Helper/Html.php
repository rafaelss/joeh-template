<?php
require_once dirname(__FILE__) . "/../Helper.php";
require_once dirname(__FILE__) . "/../Tag.php";

class Joeh_Template_Helper_Html extends Joeh_Template_Helper {

    public function getName() {
        return "html";
    }

    public function scriptTag($url) {
        $script = new Joeh_Template_Tag("script", "");
        $script->type = "text/javascript";
        $script->src = $url;
        return $script->toHTML() . PHP_EOL;
    }

    public function buttonTag($value, array $options = array()) {
        $options["type"] = "button";

        $button = new Joeh_Template_Tag("input");
        $button->value = $value;
        $button->addAttributesFromArray($options);
        return $button->toHTML() . PHP_EOL;
    }

    public function doctype($type) {
        switch($type) {
            case "xhtml1-transitional":
                return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" . PHP_EOL;
        }
    }
}
?>