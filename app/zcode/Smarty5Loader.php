<?php 

/**
 * @name Smarty5Loader.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
**/

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