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

	/**
	 * Load in the routes available
	 * @return void
	 */
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

		// Ensure the config has been loaded
		self::load();

		// Create controller & method
		$controller = $method = '';
		$args = array();

		// Get the urn
		$urn = ($urn === null) ? Request::urn() : $urn;

		// Remove the preceding slash, cast as string to prevent false
		$urn = (substr($urn, 0, 1) == '/') ? (string) substr($urn, 1) : (string) $urn;

		// Get the controller & method parts
		if (strpos($urn, '/') !== false) {
			$args = explode("/", $urn);
			$controller = array_shift($args);
			$method = array_shift($args);

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
					$http = self::execute($route_controller, $method, $args);

					return $http;
				}
			}
		}

		// Return a 404 if no method found
		return self::HTTP_404;
	}

	/**
	 * Execute a method
	 * @param  string $controller Controller to act on
	 * @param  string $method     Method to run
	 * @param  array  $args       Args to pass to the method
	 * @return int Http response
	 */
	static function execute($controller, $method, $args) {

		// Get the class name with the controller extension
		$class = ucfirst(strtolower($controller)) . Config::$ctlr_ext;

		try {
			// Get a singleton of the class
			$instance = Singleton::get($class);

			// Prepend the method with 'GET_' or 'POST_'
			$request_method = Request::method() . '_' . $method;

			// Check the request method exists
			if (method_exists($instance, $request_method)) {

				// Call the method, passing through the args
				$http = call_user_func_array(array($instance, $request_method), $args);

				// If there was a return value, return it up
				if ($http !== null) {
					return $http;
				}
			}

			// Default to return a 200 OK
			return self::HTTP_200;

		} catch (Exception $e) {
			p($e->getMessage());
			return self::HTTP_404;
		}

		return self::HTTP_404;
	}

}