<?php

// only the most relevant status codes.
static $HTTP_STATUS_CODES = array(
	200 => 'OK',
	201 => 'Created',
	202 => 'Accepted',
	203 => 'Non-Authoritative Information',
	301 => 'Moved Permanently',
	302 => 'Found',
	307 => 'Temporary Redirect',
	401 => 'Unauthorized',
	402 => 'Payment Required',
	404 => 'Not Found',
	410 => 'Gone',
	500 => 'Internal Server Error',
	501 => 'Not Implemented',
	503 => 'Service Unavailable',
	505 => 'HTTP Version Not Supported',
);

class Request {
	var $method = 'GET';
	var $path = '/';
	
	function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->path = $_SERVER['REQUEST_URI'];
	}
}

class Response {
	var $headers = array();
	var $status = 0;
	var $contentType = '';
	var $content = '';
	
	function __construct($content="", $status=200, $contentType="text/html") {
		$this->status = $status;
		$this->contentType = $contentType;
		$this->content = $content;
	}
	
	function send() {
		global $HTTP_STATUS_CODES;
		ob_start();
		header('HTTP/1.1 '.$this->status.' '.$HTTP_STATUS_CODES[$this->status]);
		header('Content-type: '.$this->contentType);
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value);
		}
		echo($this->content);
		ob_end_flush();
		flush();
		exit;
	}
	
}

class RedirectResponse extends Response {
	function __construct($url, $isPermanent=false) {
		parent::__construct();
		$this->status = $isPermanent ? 301 : 302;
		$this->headers['Location'] = $url;
	}
}