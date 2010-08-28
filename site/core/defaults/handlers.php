<?php

function empty_project($request, $attrs) {
	$m = "<!DOCTYPE html>\n";
	$m .= '<html lang="en"><head><meta charset="utf-8" /><title>Welcome</title><style type="text/css">'."\n";
	$m .= "body {background: #fff; color: #333; font: normal 16px/20px Helvetica,Arial,sans-serif;}\n";
	$m .= "h1,p {width: 480px; margin: 0;}\n";
	$m .= "h1 {font-size: 2em; line-height: 1.1em;}\n";
	$m .= "p {text-indent: .4em; color: #555;}\n";
	$m .= "div {margin: 4em auto 0 auto; width: 480px;}\n";
	$m .= '</style></head><body><div><h1>Works.</h1>';
	$m .= '<p>Now hurry and create a wonderful website.</p></div>';
	$m .= '</body></html>';
	return new Response($m);
}

function handle_404($request, $attrs) {
	$m = "<!DOCTYPE html>\n";
	$m .= '<html lang="en"><head><meta charset="utf-8" /><title>404 File Not Found</title><style type="text/css">'."\n";
	$m .= "body {background: #fff; color: #333; font: normal 16px/20px Helvetica,Arial,sans-serif;}\n";
	$m .= "h1,p {width: 480px; margin: 0;}\n";
	$m .= "h1 {font-size: 2em; line-height: 1.1em;}\n";
	$m .= "p {color: #555;}\n";
	$m .= "div {margin: 4em auto 0 auto; width: 480px;}\n";
	$m .= '</style></head><body><div><h1>404 File Not Found</h1>';
	$m .= '<p>The page you are looking for was not found at this URL.</p></div>';
	$m .= '</body></html>';
	return new Response($m, $status=404);
}

function handle_500($request, $attrs) {
	$error = isset($attrs['error']) ? $error->getMessage() : "Don't panic! Nothing to see here. Please, move on.";
	$m = "<!DOCTYPE html>\n";
	$m .= '<html lang="en"><head><meta charset="utf-8" /><title>500 Internal Server Error</title><style type="text/css">'."\n";
	$m .= "body {background: #fff; color: #333; font: normal 16px/22px Helvetica,Arial,sans-serif;}\n";
	$m .= "h1,p {width: 480px; margin: 0;}\n";
	$m .= "h1 {font-size: 2em; line-height: 1.1em;}\n";
	$m .= "p {text-indent: .4em; color: #555;}\n";
	$m .= "div {margin: 4em auto 0 auto; width: 480px;}\n";
	$m .= '</style></head><body><div><h1>Woops, Server Error</h1>';
	$m .= '<p>'.$error.'</p></div>';
	$m .= '</body></html>';
	return new Response($m, $status=500);
}