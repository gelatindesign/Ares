<?php

namespace Ares;

use Symfony\Component\Yaml;

class Config {

	static $root = null,
	       $env  = null;

	static function load() {

		if (self::$root == null) return false;

		// Load config
		$config_file = self::$root . '/config/config.yml';
		$config = Yaml::parse($config_file);

		v($config);

		// Load environment
		foreach ($config['environment'] as $env => $match) {
			$regex = simpleRegex($match);

			if (preg_match($regex, Request::host())) {
				self::$env = Yaml::parse(self::$root . '/config/' . $env . '.env.php');
				break;
			}
		}

		v(self::$env);

	}

}