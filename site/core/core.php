<?php
defined('CORE_ROOT') or define('CORE_ROOT', dirname(__FILE__).'/');
require_once CORE_ROOT."http.php";
require_once CORE_ROOT."defaults/handlers.php";

class Configuration {
	function __construct($module) {
		$s = $this;
		require CORE_ROOT.'defaults/settings.php';
		require $module;
	}
	
	function get($name, $default=null) {
		if (isset($this->{$name})) return $this->{$name};
		else return $default;
	}
	
	function set($name, $value) {
		$this->{$name} = $value;
	}
}

class Resolver {
	var $patterns = array();
	var $root = "/";
	
	function __construct($module, $root="/") {
		$this->root = $root;
		$urls = $this;
		require $module;
	}
	
	function merge($patterns) {
		$this->patterns = array_merge($this->patterns, $patterns);
	}
	
	//function load($module) {
	//}
	
	function resolve($request) {
		if (empty($this->patterns)) return array('core.defaults.handlers.empty_project', array());
		$path = preg_replace('~^('.$this->root.')(.*)$~', '$2', $request->path);
		foreach ($this->patterns as $pattern => $options) {
			if (preg_match($pattern, $path, $attrs) == false) continue;
			array_shift($attrs);
			$selector = array_shift($options);
			return array($selector, array_merge($attrs, $options));
		}
		return array();
	}
}

class Core {
	private $settings = null;
	private $resolver = null;
	
	function __construct($settings_path) {
		$this->settings = new Configuration($settings_path);
		$this->resolver = new Resolver($this->settings->URL_MODULE, $this->settings->PROJECT_ROOT);
		date_default_timezone_set($this->settings->TIMEZONE);
	}
	
	function serve() {
		$request = new Request();
		$handler = $this->resolver->resolve($request);
		if (empty($handler)) {
			self::import('core.defaults.handlers');
			return handle_404($request, array())->send();
		}			
		$tokens = explode('.', $handler[0]);
		$callback = array_pop($tokens);
		try {
			self::import(implode($tokens, '.'));
			if (!function_exists($callback))
				throw new Exception("callback function '".$callback."' does not exist in '".$handler[0]."'.");
			$response = $callback($request, $handler[1])->send();
		} catch (Exception $e) {
			self::import('core.defaults.handlers');
			return handle_500($request, array($e))->send();
		}
	}
	
	static function import($selector) {
		if (preg_match("=^(site|root)\.([a-z0-9\.]+)=", $selector, $m) != false)
			$path = ($m[1] == 'site') ? self::module_path($m[2]) : self::module_path($m[2], CORE_ROOT);
		else $path = self::module_path($selector);
		if (!file_exists($path)) throw new Exception("No module '".$selector."' to import at '".$path."'.");
		require_once $path;
	}
	
	static function module_path($selector, $root=SITE_ROOT) {
		return $root.implode(explode('.', $selector), '/').'.php';
	}
}

