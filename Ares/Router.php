<?php

namespace Ares;

/**
 * Directs HTTP traffic to the correct controller\method
 */
class Router {

	const HTTP_200 = 1,  // OK
	      HTTP_401 = -1, // Unauthorized
	      HTTP_404 = -2; // Not Found

	/**
	 * Looks at the URI to determine the controller\method to run
	 * @param  (optional) string $urn URN to send the user to, defaults to
	 *                                the current URN
	 * @return int
	 */
	static function find($urn=null) {

		// Get the urn
		$urn = ($urn===null) ? $_SERVER['REQUEST_URI'] : $urn;

		v($urn);

		// Get the controller & method parts
		list($controller, $method) = explode("/", $urn);

		v($controller, $method);

		// Attempt to get the controller
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