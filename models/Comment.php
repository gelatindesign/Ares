<?php

namespace Ares;

class Comment extends Model {

	static $schema = array(
		'comment' => 'text'
	);

	function __toString() {
		if (trim($this->name) != '') {
			return $this->name.' ('.$this->email.')';
		} else {
			return $this->email;
		}
	}

}