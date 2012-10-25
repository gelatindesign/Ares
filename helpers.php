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