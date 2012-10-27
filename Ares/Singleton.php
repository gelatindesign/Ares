<?php

namespace Ares;

class Singleton {

	private $loaded = array();

	function get($name) {
		if (!isset(self::$loaded[$name])) {
			$instance = new $name;
			self::$loaded[$name] = $instance;
		}

		return self::$loaded[$name];
	}
}