<?php

Class Registry Implements ArrayAccess
{
	static private
		$vars = [];
		
	private function __construct() {}

	static function set($key, $var) {
		if (isset(self::$vars[$key]) == true) {
			throw new Exception('Unable to set var `' . $key . '`. Already set.');
		}

		self::$vars[$key] = $var;
	}

	static function get($key) {
		if (isset(self::$vars[$key]) == false) {
				return null;
		}

		return self::$vars[$key];
	}

	static function remove($var) {
		unset($this->vars[$key]);
	}
	
	// реализация интерфейса ArrayAccess
	
	function offsetExists($offset) {
		return isset($this->vars[$offset]);
	}

	function offsetGet($offset) {
		return $this->get($offset);
	}

	function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	function offsetUnset($offset) {
		unset($this->vars[$offset]);
	}

}
