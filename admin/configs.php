<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

if ( ! defined('ZCODEV3')) exit('No direct script access allowed');

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
      echo htmlspecialchars("<b>Error:</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>");
   }
   return true;
});

// Custom exception handler
set_exception_handler(function($exception) {
   $logMessage = "[EXCEPTION] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
   error_log($logMessage);
   if (DEBUG) {
      echo htmlspecialchars("<b>Exception:</b> " . $exception->getMessage() . " in <b>" . $exception->getFile() . "</b> on line <b>" . $exception->getLine() . "</b><br>");
   }
});

// Custom shutdown handler
register_shutdown_function(function() {
   $error = error_get_last();
   if ($error !== NULL) {
      $logMessage = "[SHUTDOWN] {$error['message']} in {$error['file']} on line {$error['line']}";
      error_log($logMessage);
      if (DEBUG) {
         echo htmlspecialchars("<b>Shutdown Error:</b> {$error['message']} in <b>{$error['file']}</b> on line <b>{$error['line']}</b><br>");
      }
   }
});