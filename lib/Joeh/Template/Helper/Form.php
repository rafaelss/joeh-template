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

require_once 'Joeh/Template/Helper.php';
require_once 'Joeh/Template/Helper/Url.php';

class Joeh_Template_Helper_Form extends Joeh_Template_Helper {

    public function getName() {
        return 'form';
    }

    public function text($object, $property, array $htmlOptions = array()) {
        if(!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        }
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $value = $this->{$object}->{$property};
        }

        return $this->textTag($name, $value, $htmlOptions);
    }

    public function textTag($name, $value = null, array $htmlOptions = array()) {
        return $this->input('text', $name, $value, $htmlOptions);
    }

    public function textarea($object, $property, array $htmlOptions = array()) {
        if(!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        }
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $value = $this->{$object}->{$property};
        }

        return $this->textareaTag($name, $value, $htmlOptions);
    }

    public function textareaTag($name, $value = '', array $htmlOptions = array()) {
        if($value === null) {
            $value = '';
        }

        if(!isset($htmlOptions['rows'])) {
            $htmlOptions['rows'] = 10;
        }
        if(!isset($htmlOptions['cols'])) {
            $htmlOptions['cols'] = 40;
        }

        $textarea = new Joeh_Template_Tag('textarea', $value);
        $textarea->addAttributesFromArray($htmlOptions);
        $textarea->name = $name;
        return $textarea->toHTML();
    }

    public function password($object, $property, array $htmlOptions = array()) {
        $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $value = $this->{$object}->{$property};
        }

        return $this->passwordTag($name, $value, $htmlOptions);
    }

    public function passwordTag($name = 'password', $value = null, array $htmlOptions = array()) {
        return $this->input('password', $name, $value, $htmlOptions);
    }

    public function submit($value = 'Submit', array $htmlOptions = array()) {
        return $this->input('submit', null, $value, $htmlOptions);
    }

    public function file($name, $value = null, array $htmlOptions = array()) {
        if(empty($htmlOptions['size'])) {
            $htmlOptions['size'] = 40;
        }

        $linkToFile = null;
        if(!empty($value)) {
            $linkToFile = $this->html->anchor('Visualizar', $this->url->base() . 'assets/upload/' . $value);
        }

        return $this->input('file', $name, $value, $htmlOptions) . $linkToFile;
    }

    public function date($name, $value = null, array $options = array(), array $htmlOptions = array()) {
		if(empty($options["formatForField"])) {
			$options["formatForField"] = "%Y-%m-%d";
		}

		if(empty($options["formatToShow"])) {
			$options["formatToShow"] = "%d/%m/%Y";
		}

		if(!empty($options["showTime"]) && ($options["showTime"] === "true" || $options["showTimes"] === true)) {
			$options["formatForField"] .= " %H:%M:%S";
			$options["formatToShow"] .= " %H:%M:%S";
		}

		empty($options["empty"]) && $options["empty"] = false;

		if(empty($value) || $value == '0000-00-00') {
			$valueToShow = "&nbsp;";
			if($options["empty"] !== true) {
				$now = time();
				$value = strftime($options["formatForField"], $now);
				$valueToShow = strftime($options["formatToShow"], $now);
			}
		}
		else {
			$valueToShow = strftime($options["formatToShow"], strtotime($value));
		}

		$html = $this->hidden($name, $value);

		if(!isset($options["class"])) {
			$options["class"] = "datefield";
		}

		$showArea = new Joeh_Template_Tag("span", $valueToShow);
		$showArea->class = $options["class"];
		$showArea->id = "show_" . $name;
		$html .= $showArea->toHTML();

		$script = new Joeh_Template_Tag("script", "Calendar.setup({inputField: \"" . $name . "\", displayArea: \"" . $showArea->id . "\", ifFormat: \"" . $options["formatForField"] . "\", daFormat: \"" . $options["formatToShow"] . "\", date: new Date(\"" . str_replace("-", "/", $value) . "\"), showsTime: true, timeFormat: \"24\"});");
		$script->type = "text/javascript";
		$html .= $script->toHTML();

		return $html;
    }

    public function select($object, $property, $from, array $htmlOptions = array()) {
        $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $value = $this->{$object}->{$property};
        }

        $textProperty = "name";
        $valueProperty = "id";

        if(!empty($htmlOptions["textProperty"])) {
            $textProperty = $htmlOptions["textProperty"];
            unset($htmlOptions["textProperty"]);
        }

        if(!empty($htmlOptions["valueProperty"])) {
            $valueProperty = $htmlOptions["valueProperty"];
            unset($htmlOptions["valueProperty"]);
        }

        return $this->selectTag($name, $this->optionsForSelect($from, $textProperty, $valueProperty, $value), $htmlOptions);
    }

    public function selectTag($name, $options = '', array $htmlOptions = array()) {
        $select = new Joeh_Template_Tag('select', $options);
        $select->addAttributesFromArray($htmlOptions);
        $select->name = $name;
        $select->id = str_replace('[', '_', str_replace(']', '', str_replace('[]', '', $name)));
        return $select->toHTML() . PHP_EOL;
    }

    public function optionsForSelect($collection, $textKey, $valueKey = null, $selected = null, array $options = array()) {
        $html = null;

        if(is_array($collection)) {
            $collection = new ArrayObject($collection, ArrayObject::ARRAY_AS_PROPS);
        }

        if(!is_object($selected) || !($selected instanceof Traversable)) {
            if(is_scalar($selected)) {
                $selected = array($selected);
            }
            else if(!$selected) {
                $selected = array();
            }

            $selected = new ArrayObject($selected);
        }

        if(!empty($options['first'])) {
            $tag = new Joeh_Template_Tag('option', $this->html->escape($options['first']));
            $html .= $tag->toHTML() . PHP_EOL;
        }

        if($hasGroup = !empty($options['group'])) {
            $group = $options['group'];
        }

        $prevGroup = '';
        $groupTag = null;
        foreach($collection as $object) {
            $tag = new Joeh_Template_Tag('option', $this->html->escape($object->{$textKey}));
            if(!$valueKey) {
                $tag->value = $object->{$textKey};
            }
            else {
                $tag->value = $object->{$valueKey};
            }

            foreach($selected as $selectedValue) {
                if(is_object($selectedValue) && method_exists($selectedValue, '__toString')) {
                    $selectedValue = $selectedValue->__toString();
                }

                if($tag->value == $selectedValue) {
                    $tag->selected = 'selected';
                }
            }

            if($hasGroup) {
                if(empty($object->{$group})) {
                    $object->{$group} = '(Sem Nome)';
                }

                if($prevGroup != $object->{$group}) {
                    if($groupTag instanceof Joeh_Template_Tag) {
                        $html .= $groupTag->toHTML() . PHP_EOL;
                    }

                    $groupTag = new Joeh_Template_Tag('optgroup', $tag->toHTML() . PHP_EOL);
                    $groupTag->label = $object->{$group};
                    $prevGroup = $object->{$group};
                }
                else {
                    $groupTag->addChildsFromXml($tag->toHTML());
                }
            }
            else {
                $html .= $tag->toHTML() . PHP_EOL;
            }
        }

        if($groupTag instanceof Joeh_Template_Tag) {
            $html .= $groupTag->toHTML() . PHP_EOL;
        }
        return $html;
    }

    public function hidden($object, $property, array $htmlOptions = array()) {
        if(!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        }
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $value = $this->{$object}->{$property};
        }

        return $this->hiddenTag($name, $value, $htmlOptions = array());
    }

    public function hiddenTag($name, $value = null, array $htmlOptions = array()) {
        return $this->input('hidden', $name, $value, $htmlOptions);
    }

    public function check($object, $property, $defaultValue = "1", array $htmlOptions = array()) {
        $htmlOptions['id'] = sprintf('%s_%s', $object, $property);
        $name = sprintf('%s[%s]', $object, $property);
        $value = null;

        $checked = false;
        if(isset($this->{$object}) && isset($this->{$object}->{$property})) {
            $checked = ($defaultValue == $this->{$object}->{$property});
        }

        return $this->checkTag($name, $defaultValue, $checked, $htmlOptions);
    }

    public function checkTag($name, $value = "1", $checked = false, array $htmlOptions = array()) {
        if($checked) {
            $htmlOptions['checked'] = 'checked';
        }
        return
            $this->hidden($name, 0, array('id' => false)) .
            $this->input('checkbox', $name, $value, $htmlOptions);
    }

    ##################
    ## PRIVATE METHODS
    ##################

    private function input($type, $name, $value = null, array $htmlOptions = array()) {
        $genId = true;
        if(isset($htmlOptions['id']) && $htmlOptions['id'] === false) {
            $genId = false;
        }

        $input = new Joeh_Template_Tag('input');
        $input->addAttributesFromArray($htmlOptions);
        $input->type = $type;
        if(!empty($name)) {
            $input->name = $name;

            if($genId) {
                $input->id = $name;
            }
        }
        $input->value = $value;
        return $input->toHTML() . PHP_EOL;
    }
}
?>
