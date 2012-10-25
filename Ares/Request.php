<?php

namespace Ares;

class Request {

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

		// Load the config
		Config::load();

		// Load in the helpers
		include "../helpers.php";
	}

}