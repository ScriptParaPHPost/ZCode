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

// DEFINICION DE CONSTANTES
define('BASEPATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

define('ERROR_DIRECTORY', BASEPATH . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR);

$endlog = date('dmy') . '.log';
define('ERROR_LOG', ERROR_DIRECTORY . 'script' . $endlog);
define('DASHBOARD_LOG', ERROR_DIRECTORY . 'admod' . $endlog);
define('MYSQLI_LOG', ERROR_DIRECTORY . 'database' . $endlog);
define('EMAIL_LOG', ERROR_DIRECTORY . 'email' . $endlog);
define('DEBUG', true);
define('DEBUG_FULL', true);
define('DEBUG_PRINT_SCREEN', true);

// SCRIPT INFO
define('SCRIPT_NAME', 'ZCode');
define('SCRIPT_AUTHOR', 'Miguel92');
define('SCRIPT_VERSION', file_get_contents(BASEPATH . '.version'));

// Reporte de errores
error_reporting(DEBUG_FULL ? E_ALL : (DEBUG ? (E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED) : 0));
ini_set('display_errors', DEBUG);
ini_set('log_errors', DEBUG);
ini_set('error_log', ERROR_LOG);

// Estilos para errores en pantalla
define('ERROR_STYLE', 'padding:1rem;margin:1rem 0;border-radius:.5rem;line-height:1.5;');
define('ERROR_BOX', 'background:#ffebeb;color:#a94442;border-left:.325rem solid #a94442;');
define('EXCEPTION_BOX', 'background:#e7f3fe;color:#31708f;border-left:.325rem solid #31708f;');
define('SHUTDOWN_BOX', 'background:#fcf8e3;color:#8a6d3b;border-left:.325rem solid #8a6d3b;');

// Función para mostrar errores con estilo
function displayError($title, $message, $file, $line, $style) {
   if (defined('DEBUG_PRINT_SCREEN') && DEBUG_PRINT_SCREEN) {
      echo "<div style='" . ERROR_STYLE . $style . "'>";
      echo "<strong>$title:</strong> $message <br><code>in <b>$file</b> on line <b>$line</b></code>";
      echo "</div>";
   }
}

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
   $logMessage = "[ERROR:$errno] $errstr in $errfile on line $errline";
   error_log($logMessage);
   displayError('Error', $errstr, $errfile, $errline, ERROR_BOX);
   return true;
});

// Custom exception handler
set_exception_handler(function($exception) {
   $logMessage = "[EXCEPTION] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
   error_log($logMessage);
   displayError('Exception', $exception->getMessage(), $exception->getFile(), $exception->getLine(), EXCEPTION_BOX);
});

// Custom shutdown handler
register_shutdown_function(function() {
   $error = error_get_last();
   if ($error !== NULL) {
      $logMessage = "[SHUTDOWN] {$error['message']} in {$error['file']} on line {$error['line']}";
      error_log($logMessage);
      displayError('Shutdown Error', $error['message'], $error['file'], $error['line'], SHUTDOWN_BOX);
   }
});

$days = 3;
$logFiles = glob(ERROR_DIRECTORY . '*.log'); // Obtiene todos los archivos .log
$oneWeekAgo = time() - ($days * 24 * 60 * 60); // x Tiempo
foreach ($logFiles as $file) {
   if (filemtime($file) < $oneWeekAgo) { // Si el archivo es más antiguo que una semana
      unlink($file); // Eliminar el archivo
   }
}