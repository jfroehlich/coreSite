<?php

Core::import('site.myapp.models');

function example_view($request, $attributes) {
	$item = new MyAppItem();
	$message = "This is a ".$item->name." view on ".$item->created;
	return new Response($message);
}