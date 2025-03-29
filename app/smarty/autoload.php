<?php 

require_once TS_SMARTY . "functions.php";

spl_autoload_register(function ($class) {
	$prefix = 'Smarty\\';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);
	$file = TS_SMARTY . str_replace('\\', '/', $relative_class) . '.php';
	if (file_exists($file)) {
		require_once($file);
	}
});
