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
		if (!self::$_loaded) {

			// Set environment routes
			Router::$routes = Config::$config['routes'];

			p(Config::$config);

			// Get controller references
			$files = glob(Config::$root . Config::$config['paths']['controllers'] . '*.php');
			p($files);
		}
	}

	/**
	 * Looks at the URI to determine the controller\method to run
	 * @param  (optional) string $urn URN to send the user to, defaults to
	 *                                the current URN
	 * @return int
	 */
	static function find($urn=null) {
		self::load();

		// Get the urn
		$urn = ($urn===null) ? Request::urn() : $urn;

		// Remove the preceding slash
		$urn = (substr($urn, 0, 1) == '/') ? substr($urn, 1) : $urn;

		// Get the controller & method parts
		list($controller, $method) = explode("/", $urn);

		if ($method == '') {
			$method = 'index';
		}

		// Check routes
		if (self::$routes !== null) {

			// Loop routes
			foreach (self::$routes as $route_controller => $route_path) {

				// Check for a match
				if ($route_controller == $controller) {

					// Run the method
					self::sendTo($controller, $method);
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