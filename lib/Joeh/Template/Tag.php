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

class Joeh_Template_Tag {

	/**
	 * Elemento principal do xml
	 *
	 * @var array $element
	 */
	private $element = array();

	/**
	 * Construtor da classe. Recebe o nome e conteudo, se tiver,
	 * do tag que será criado
	 *
	 * @param string $name
	 * @param mixed $contents
	 */
	public function __construct($name, $contents = null) {
		$this->element["name"] = $name;
		$this->element["attributes"] = array();

		if($contents instanceof self) {
			$contents = $contents->toHTML();
		}
		$this->element["cdata"] = $contents;
	}

	public function __get($name) {
		return $this->element["attributes"][$name];
	}

	public function __set($name, $value) {
		$this->addAttribute($name, $value);
	}

    public function __isset($name) {
        return isset($this->element["attributes"][$name]);
    }

    public function __unset($name) {
        unset($this->element["attributes"][$name]);
    }

	/**
	 * Adiciona um atributo ao tag principal
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function addAttribute($name, $value) {
        if($value === false) {
            unset($this->element["attributes"][$name]);
        }
        else {
            if($value === true) {
                $value = $name;
            }
            $this->element["attributes"][$name] = $value;
        }
	}

	/**
	 * Adiciona atributos a tag apartir de um array associativo
	 *
	 * @param array $attributes
	 */
	public function addAttributesFromArray(array $attributes) {
		foreach($attributes as $name => $value) {
			if($value !== null) {
				$this->addAttribute($name, $value);
			}
		}
	}

	/**
	 * Adiciona elementos no elemento atual apartir de uma string
	 *
	 * @param string $xmlstr
	 */
	public function addChildsFromXml($xmlstr) {
		$this->element["cdata"] .= $xmlstr;
	}

	/**
	 * Diz se está setado um atributo para a tag
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function hasAttribute($name) {
		$attributes = array_keys($this->element["attributes"]);
		return in_array($name, $attributes);
	}

	/**
	 * Gera a string do elemento atual
	 *
	 * @return string
	 */
	public function toHTML() {
		extract($this->element);

		$html = "<{$name}";

		foreach($attributes as $key => $value) {
			$html .= " {$key}=\"{$value}\"";
		}

		if($cdata === null) {
			$html .= "/>";
		}
		else {
			$html .= ">{$cdata}</{$name}>";
		}
    	return $html;
	}
}
?>