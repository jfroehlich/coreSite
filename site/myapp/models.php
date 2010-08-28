<?php

// the models of this app.

class MyAppItem {
	var $name = "";
	var $created = "";
	
	function __construct($name="demo") {
		$this->name = $name;
		$this->created = date("F jS, Y, G:i");
	}
}