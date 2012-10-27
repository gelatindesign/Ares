<?php

namespace Ares;

class Request {

	static function host() {
		return $_SERVER['HTTP_HOST'];
	}

	static function urn() {
		return $_SERVER['REQUEST_URI'];
	}

	static function method() {
		$methods = array('GET', 'POST', 'PUT', 'DELETE');
		$method = strtoupper($_SERVER['REQUEST_METHOD']);

		if (!in_array($method, $methods)) {
			throw new Exception\RequestException("Request method '".$method."' invalid");
		}

		return $method;
	}

	static function start() {

		// Create the session
		session_start();

		// Encoding
		mb_internal_encoding("UTF-8");
		mb_http_output("UTF-8");
		
		// Timezone
		date_default_timezone_set('GMT');
		
		// Locale
		setlocale(LC_ALL, 'en_GB');

		// Load in the helpers
		include __DIR__ . "/../helpers.php";

		// Load the config
		try {
			Config::load();
		} catch (Exception $e) {
			throw $e;
		}
	}

}