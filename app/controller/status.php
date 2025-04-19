<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

error_reporting(E_ALL);
date_default_timezone_set('America/Argentina/Buenos_Aires');

$fileenv = dirname(__DIR__, 2) . '/.env';
if (file_exists($fileenv)) {
   $dotenv = fopen($fileenv, 'r');
   if ($dotenv) {
      while (($line = fgets($dotenv)) !== false) {
         // Ignorar comentarios y líneas vacías
         if (trim($line) === '' || strpos(trim($line), '#') === 0) {
            continue;
         }
         if (preg_match('/\A([a-zA-Z0-9_]+)=(.*)\z/', trim($line), $matches)) {
         	$_ENV[$matches[1]] = $matches[2];
         }
      }
      fclose($dotenv);
   }
}
function env(string $key, $default = null) {
   if (isset($_ENV[$key])) return $_ENV[$key];
   if (isset($_SERVER[$key])) return $_SERVER[$key];
   if (function_exists('getenv')) {
      $value = getenv($key);
      if ($value !== false) return $value;
   }
   return $default;
}

include dirname(__DIR__) . '/services/SystemHealthCheck.php';
include dirname(__DIR__) . '/plugins/modifier.elapsed_time.php';
include dirname(__DIR__) . '/plugins/modifier.hace.php';
include dirname(__DIR__) . '/plugins/modifier.fecha.php';

$healthCheck = new SystemHealthCheck();

$tsTitle = 'ZCode - Status';


// Uso
$healthCheck->run();
#$healthCheck->serveHealthCheck();
#$healthCheck->verificar();

$statusFile = dirname(__DIR__, 2) . '/storage/system_health.json';
$ZCODE_HEALTH = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];

$tiempo_ejecucion = $healthCheck->getExecutionTime();
include dirname(__DIR__, 2) . '/errors/status.html';