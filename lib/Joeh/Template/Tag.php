<?php
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

	/**
	 * Adiciona um atributo ao tag principal
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function addAttribute($name, $value) {
		$this->element["attributes"][$name] = $value;
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