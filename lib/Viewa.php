<?php

class View {

	static $data = array(),
	       $template = 'default',
	       $view = null;

	static function render() {
		extract(func_get_args());

		require TEMPLATES_PATH . self::$template . '.php';
	}

	static function view() {
		require VIEWS_PATH . self::$view . '.php';
	}

	static function partial($partial) {
		require PARTIALS_PATH . $partial . '.php';
	}

}