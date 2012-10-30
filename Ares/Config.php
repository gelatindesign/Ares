<?php

namespace Ares;

use \Symfony\Component\Yaml as Yaml;

class Config {

	static $root     = null,
	       $config   = null,
	       $env      = null,
	       $envvars  = null,
	       $ctlr_ext = '';

	static function load() {

		if (self::$root == null) return false;

		if (substr(self::$root, -1) != '/') self::$root .= '/';

		// Load config
		$config_file = self::$root . 'config/config.yml';

		if (!is_file($config_file)) {
			throw new Exception\ConfigException(
				"Config not found in '" . $config_file . "'");
		}

		try {
			self::$config = Yaml\Yaml::parse($config_file);

		} catch (Exception $e) {
			throw new Exception\ConfigException(
				"Config not valid in '" . $config_file . "'", 0, $e);
		}

		// Load environment
		foreach (self::$config['environment'] as $env => $match) {
			$regex = simpleRegex($match);

			if (preg_match($regex, Request::host()) !== false) {
				self::$env = $env;
				
				$env_file = self::$root . 'config/' . $env . '.env.yml';

				if (!is_file($env_file)) {
					throw new Exception\ConfigException
					("Environment not found in '" . $env_file . "'");
				}

				try {
					self::$envvars = Yaml\Yaml::parse($env_file);
				
				} catch (Exception $e) {
					throw new Exception\ConfigException(
						"Environment not valid in '" . $env_file . "'", 0, $e);
				}

				break;
			}
		}

	}

}