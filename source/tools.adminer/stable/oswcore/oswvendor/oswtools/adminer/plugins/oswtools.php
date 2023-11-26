<?php

class AdmineroswTools {

	function name() {
		//custom name in title and heading
		return 'Software';
	}

	function css() {
		$return=[];
		$return[]='../resources/php/adminer/designs/hever/adminer.css';

		return $return;
	}

}
