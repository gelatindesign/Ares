<?php

function p() {
	$args = func_get_args();
	echo '<pre>';
	print_r($args);
	echo '</pre>';
}

function v() {
	$args = func_get_args();
	echo '<pre>';
	var_dump($args);
	echo '</pre>';
}

function simpleRegex($string) {

	// %+ => .+
	$string = str_replace('%+', '(.+)', $string);

	// % => .*
	$string = str_replace('%', '(.*)', $string);

	// :string => [A-Za-z_\-]+

	// :number => [0-9]+

	return '~' . $string . '~';
}