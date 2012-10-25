<?php

class PersonFront extends FrontController {

	function GET_view(int $id) {
		$person = Person::findById($id);

		View::render($person);
	}

}