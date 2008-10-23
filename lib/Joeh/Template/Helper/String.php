<?php
class Joeh_Template_Helper_String extends Joeh_Template_Helper {

    public function getName() {
        return 'string';
    }

	/**
	 * Flag que diz se a extensao mbstring está carregada
	 *
	 * @var boolean $loadedMbString
	 */
	private static $loadedMbString = true;

	/**
	 * Diz se uma string começa com outra
	 * Suporte a string em UNICODE
	 *
	 * @param string $string
	 * @param string $prefix
	 * @return boolean
	 * @todo melhorar teste unitario
	 */
	public function startsWith($string, $prefix) {
		return (self::indexOf($string, $prefix) === 0);
	}

	/**
	 * Diz se uma string termina com outra
	 *
	 * @param string $string
	 * @param string $suffix
	 * @return boolean
	 * @todo testar string com acentos
	 */
	public function endsWith($string, $suffix) {
		return self::startsWith(self::reverse($string), self::reverse($suffix));
	}

	/**
	 * Inverte uma string
	 *
	 * @param string $string
	 * @return string
	 */
	public function reverse($string) {
		return strrev($string);
	}

	/**
	 * Transforma uma string em CamelCase
	 *
	 * @param string $string
	 * @param boolean $capsFirst
	 * @return string
	 */
	public function camelize($string, $capsFirst = false) {
		$string = '_' . str_replace('_', ' ', $string);
		$string = ltrim(str_replace(' ', '', ucwords($string)), '_');
		if($capsFirst) {
			$string = ucfirst($string);
		}
		else {
			$string[0] = self::toLower($string[0]);
		}
		return $string;
	}

	/**
	 * Transforma uma palavra para plural
	 *
	 * @param string $name
	 * @return string
	 */
	public function plural($name) {
		return Inflect::pluralize($name);
	}

	/**
	 * Transforma uma palavra para singular
	 *
	 * @param string $name
	 * @return string
	 */
	public function singular($name) {
		return Inflect::singularize($name);
	}

	/**
	 * Transforma qualquer string por outra separada por undescore
	 *
	 * @param string $string
	 * @return string
	 */
	public function underscore($string) {
		$string = preg_replace('/([a-z])([A-Z])/', "$1_$2", $string);
		$string = strtr($string, " ", "_");
		return self::toLower($string);
	}

	/**
	 * Converte toda a string para letras minusculas
	 *
	 * @return string $string
	 */
	public function toLower($string) {
		if($string === null) {
			return $string;
		}

		if(self::$loadedMbString) {
			return mb_strtolower($string, mb_detect_encoding($string));
		}
		$string = strtr($string, "ÁÀÃÂÄÉÈÊËÍÌÏÓÒÕÔÖÚÙÜÇ", "áàãâäéèêëíìïóòõôöúùüç");
		return utf8_encode(strtolower(utf8_decode($string)));
	}

	/**
	 * Converte toda a string para letras MAIUSCULAS
	 * Retorna NULL caso a string seja nula
	 *
	 * @return string $string
	 */
	public function toUpper($string) {
		if($string === null) {
			return $string;
		}

		if(self::$loadedMbString) {
			return mb_strtoupper($string, mb_detect_encoding($string));
		}
		$string = strtr($string, "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ");
		return strtoupper($string);
	}

	/**
	 * Verifica se uma string é nula ou vazia
	 *
	 * @param mixed $string
	 * @return boolean
	 */
	public function isNullOrEmpty($var) {
		if(is_array($var)) {
			return count($var) <= 0;
		}
		return ($var === null || trim($var) == "");
	}

	/**
	 * Converte a primeira letra da string para maiuscula
	 * e mantem as demais em minuscula
	 *
	 * @param string $string
	 * @return string
	 */
	public function capitalize($string) {
		return self::toUpper(substr($string, 0, 1)) . self::toLower(substr($string, 1));
	}

	/**
	 * Remove acento da string.
	 * This method is case-insensitive.
	 * Foi utilizado método utf8_decode para garantir mesmo charset
	 *
	 * @param string $str
	 * @return string
	 */
	public function normalize($str) {
		$from = "áàãâäéèêëíìïóòõôöúùüçÁÀÃÂÄÉÈÊËÍÌÏÓÒÕÔÖÚÙÜÇ ";
		$to   = "aaaaaeeeeiiiooooouuucAAAAAEEEEIIIOOOOOUUUC_";
		return strtr(utf8_decode($str), utf8_decode($from), utf8_decode($to));
	}

	/**
	 * Transforma a primeira letra de cada palavra em caixa-alta
	 *
	 * @param string $string
	 * @return string
	 */
	public function titleize($string) {
		$string = strtr($string, "_", " ");
		return ucwords($string);
	}

	/**
	 * Retorna parte de string especificada em $start e $length
	 * Suporte a palavras em UNICODE
	 *
	 * @param string $string
	 * @param int $start
	 * @param int $length
	 * @return string
	 * @todo teste unitario
	 */
	public function substring($string, $start, $length = null) {
		if($length === null) {
			$length = W3_String::length($string) - $start;
		}
		if(self::$loadedMbString) {
			return mb_substr($string, $start, $length, mb_detect_encoding($string));
		}
		return substr($string, $start, $length);
	}

	/**
	 * Retorna a quantidade de caracteres da string
	 * Suporte a strings em UNICODE
	 *
	 * @param string $string
	 * @return int
	 * @todo testes unitarios
	 */
	public function length($string) {
		if(self::$loadedMbString) {
			return mb_strlen($string);
		}
		return strlen($string);
	}

	/**
	 * Retorna a posição de uma string dentro de outra
	 *
	 * @param string $string
	 * @param string $needle
	 * @param int $offset
	 * @return int
	 */
	public function indexOf($string, $needle, $offset = null) {
		if(self::$loadedMbString) {
			return mb_strpos($string, $needle, $offset, mb_detect_encoding($string));
		}
		return strpos($string, $needle, $offset);
	}

	/**
	 * Diz se uma string contem outra
	 *
	 * @param string $string
	 * @param string $needle
	 * @return bool
	 */
	public function contains($string, $needle) {
		$arguments = func_get_args();
		$count = count($arguments);
		for($i = 1; $i < $count; $i++) {
			if(self::indexOf($string, $arguments[$i]) !== false) {
				return true;
			}
		}
		return false;
	}

	public function equals($string, $string2) {
		$arguments = func_get_args();
		$count = count($arguments);
		for($i = 1; $i < $count; $i++) {
			if($string == $arguments[$i]) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Define se a extensao mbstring está carregada e retorna o valor anterior
	 *
	 * @param boolean $loaded
	 * @return boolean
	 */
	public static function setLoadedMbString($loaded) {
		$current = self::$loadedMbString;
		self::$loadedMbString = $loaded;
		return $current;
	}
}

Joeh_Template_Helper_String::setLoadedMbString(extension_loaded("mbstring"));
?>