<?php

namespace Ares;

class Singleton {

	private static $loaded = array();

	static function get($name) {
		if (!isset(self::$loaded[$name])) {
			$instance = new $name;
			self::$loaded[$name] = $instance;
		}

		return self::$loaded[$name];
	}
}