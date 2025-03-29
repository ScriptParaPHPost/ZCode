<?php 

if ( ! defined('ZCODE2')) exit('No direct script access allowed');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/


// DEFINICION DE CONSTANTES
define('TS_ROOT', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

define('ERROR_DIRECTORY', TS_ROOT . 'logs' . DIRECTORY_SEPARATOR);

$endlog = date('dmy') . '.log';
define('ERROR_LOG', ERROR_DIRECTORY . 'script' . $endlog);
define('DASHBOARD_LOG', ERROR_DIRECTORY . 'admod' . $endlog);
define('MYSQLI_LOG', ERROR_DIRECTORY . 'database' . $endlog);
define('EMAIL_LOG', ERROR_DIRECTORY . 'email' . $endlog);
define('DEBUG', true);

// SCRIPT INFO
define('SCRIPT_NAME', 'ZCode');
define('SCRIPT_AUTHOR', 'Miguel92');
define('SCRIPT_VERSION', file_get_contents(TS_ROOT . '.version'));

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