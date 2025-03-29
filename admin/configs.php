<?php if ( ! defined('ZCODE2')) exit('No direct script access allowed');

/**
 * @name define.php
 * @copyright ZCode 2024
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://zcodev.alwaysdata.net/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.1.15
 * @description Se definen todas las rutas
**/
//DEFINICION DE CONSTANTES
define('TS_ADMIN', TS_ROOT . 'admin' . DIRECTORY_SEPARATOR);

// Reporte de errores
error_reporting(DEBUG ? E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED : 0);
ini_set('display_errors', DEBUG);
ini_set('log_errors', DEBUG);
ini_set('error_log', ERROR_LOG);

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
   $logMessage = "[ERROR:$errno] $errstr in $errfile on line $errline";
   error_log($logMessage);
   if (DEBUG) {
      #echo "<b>Error:</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
   }
   return true;
});

// Custom exception handler
set_exception_handler(function($exception) {
   $logMessage = "[EXCEPTION] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
   error_log($logMessage);
   if (DEBUG) {
      #echo "<b>Exception:</b> " . $exception->getMessage() . " in <b>" . $exception->getFile() . "</b> on line <b>" . $exception->getLine() . "</b><br>";
   }
});

// Custom shutdown handler
register_shutdown_function(function() {
   $error = error_get_last();
   if ($error !== NULL) {
      $logMessage = "[SHUTDOWN] {$error['message']} in {$error['file']} on line {$error['line']}";
      error_log($logMessage);
      if (DEBUG) {
         #echo "<b>Shutdown Error:</b> {$error['message']} in <b>{$error['file']}</b> on line <b>{$error['line']}</b><br>";
      }
   }
});
/**
 * Rutas APP
 */
define('TS_MODELS',	 TS_ADMIN . 'models' . DIRECTORY_SEPARATOR);
define('TS_HELPERS',	 TS_ADMIN . 'helpers' . DIRECTORY_SEPARATOR);

define('TS_APP', 	 	 TS_ROOT . 'app' . DIRECTORY_SEPARATOR);
define('TS_EXTRA', 	 TS_APP . 'extras' . DIRECTORY_SEPARATOR);
define('TS_PLUGINS',  TS_APP . 'plugins' . DIRECTORY_SEPARATOR);
define('TS_SMARTY', 	 TS_APP . 'smarty' . DIRECTORY_SEPARATOR);
define('TS_ZCODE', 	 TS_APP . 'zcode' . DIRECTORY_SEPARATOR);

define('GOOGLE2FA', 	 TS_EXTRA . 'google' . DIRECTORY_SEPARATOR);
define('DATABASE', 	 TS_ZCODE . 'database.php');

/**
 * Rutas ASSETS
 */
define('TS_ASSETS', 		TS_ROOT . 'assets' . DIRECTORY_SEPARATOR);
define('TS_IMAGES', 		TS_ASSETS . 'images' . DIRECTORY_SEPARATOR);
define('TS_AVATARES',	TS_IMAGES . 'avatares' . DIRECTORY_SEPARATOR);

define('TS_AUTH', 		TS_ROOT . 'auth' . DIRECTORY_SEPARATOR);

/**
 * Rutas STORAGE
 */
define('TS_STORAGE', 	 TS_ROOT . 'storage' . DIRECTORY_SEPARATOR);
define('TS_AVATAR', 		 TS_STORAGE . 'avatar' . DIRECTORY_SEPARATOR);
define('TS_CACHE', 		 TS_STORAGE . 'cache' . DIRECTORY_SEPARATOR);
define('TS_PORTADAS',	 TS_STORAGE . 'portadas' . DIRECTORY_SEPARATOR);
define('TS_UPLOADS', 	 TS_STORAGE . 'uploads' . DIRECTORY_SEPARATOR);
define('TS_BACKUP', 		 TS_STORAGE . 'backup' . DIRECTORY_SEPARATOR);
define('LOCK', 		 	 TS_STORAGE . '.lock');
define('VERSION', 		 TS_STORAGE . '.version');
define('TS_AVATAR_USER', TS_AVATAR . 'user');

/**
 * Rutas THEMES
 */
define('TS_THEMES', TS_ROOT . 'themes' . DIRECTORY_SEPARATOR);

define('LICENSE',   TS_ROOT . 'LICENSE');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));