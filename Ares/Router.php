<?php

namespace Ares;

/**
 * Directs HTTP traffic to the correct controller\method
 */
class Router {

	const HTTP_200 = 1,  // OK
	      HTTP_401 = -1, // Unauthorized
	      HTTP_404 = -2; // Not Found

	static $_loaded     = false,
	       $routes      = null,
	       $controllers = null;

	static function load() {

		// Prevent the config from being loaded more than once
		if (!self::$_loaded) {

			// Set environment routes
			Router::$routes = Config::$config['routes'];

			// Get controller references
			$files = glob(Config::$root . Config::$config['paths']['controllers'] . '*.php');
		}
	}

	/**
	 * Looks at the URI to determine the controller\method to run
	 * @param  (optional) string $urn URN to send the user to, defaults to
	 *                                the current URN
	 * @return int
	 */
	static function find($urn=null) {

		// Create controller & method
		$controller = $method = '';

		// Ensure the config has been loaded
		self::load();

		// Get the urn
		$urn = ($urn === null) ? Request::urn() : $urn;

		// Remove the preceding slash, cast as string to prevent false
		$urn = (substr($urn, 0, 1) == '/') ? (string) substr($urn, 1) : (string) $urn;

		// Get the controller & method parts
		if (strpos($urn, '/') !== false) {
			list($controller, $method) = explode("/", $urn);

		// If a single part urn, just set the controller as that
		} else {
			$controller = $urn;
		}
		
		// Set the default method to 'index' if none set
		if ($method == '') {
			$method = 'index';
		}

		// Check routes
		if (self::$routes !== null) {

			// Loop routes
			foreach (self::$routes as $route_controller => $route_path) {

				// Check for a match
				if ($route_path == $controller) {

					// Run the method
					self::sendTo($route_controller, $method);
				}
			}
		}

		if ($urn != '') {

			// Attempt to get the controller
			try {
				$instance = new $controller;
				return self::HTTP_200;

			} catch (Exception $e) {
				p($e->getMessage());
				return self::HTTP_404;

			}
		}

		return self::HTTP_404;

	}

	static function sendTo($controller, $method) {
		try {
			$instance = new $controller;
			return self::HTTP_200;

		} catch (Exception $e) {
			p($e->getMessage());
			return self::HTTP_404;

		}

		return self::HTTP_404;
	}

}