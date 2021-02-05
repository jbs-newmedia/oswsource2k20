<?php

// PHP_VERSION_ID is available as of PHP 5.2.7, if our
// version is lower than that, then emulate it
// http://php.net/manual/en/function.phpversion.php
if (!defined('PHP_VERSION_ID')) {
	$version = explode('.', PHP_VERSION);
	define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));

	define('PHP_MAJOR_VERSION',  $version[0]);
	define('PHP_MINOR_VERSION',  $version[1]);
	define('PHP_RELEASE_VERSION', $version[2]);
}

?>