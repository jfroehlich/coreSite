<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
defined('SITE_ROOT') or define('SITE_ROOT', dirname(__FILE__).'/');

require_once SITE_ROOT.'core/core.php';
$site = new Core(SITE_ROOT.'settings.php');
$site->serve();