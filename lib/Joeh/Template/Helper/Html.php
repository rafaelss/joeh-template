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

require_once LIB_PATH . "Joeh/Template/Helper.php";
require_once LIB_PATH . "Joeh/Template/Tag.php";

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

    public function contentType($type, $encoding = 'utf-8') {
        return '<meta http-equiv="Content-Type" content="' . $type . '; charset=' . $encoding . '" />' . PHP_EOL;
    }

    public function doctype($type) {
        switch($type) {
            case "xhtml1-transitional":
                return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . PHP_EOL;
            case "xhtml1-strict":
                return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . PHP_EOL;
        }
    }
}
?>