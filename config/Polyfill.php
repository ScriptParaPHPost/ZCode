<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

if (!function_exists('safe_count')) {
	/**
	 * Función safe_count
	 * @author Miguel92 
	 * Actua igual que is_countable, excepto que este devuelve 
	 * el valor y no un booleano
	*/
   function safe_count($data, $mode = COUNT_NORMAL) {
      return (is_array($data) || $data instanceof Countable) ? count($data, $mode) : 0;
   }
}

if (!function_exists('safe_unserialize')) {
   /**
    * Safely unserialize data.
    *
    * @param string $data The serialized data to be unserialized.
    * @return mixed The unserialized data or an empty array if unserialization fails.
    */
   function safe_unserialize($data) {
      if (!is_string($data) || empty($data)) {
         return [];
      }
      $result = @unserialize($data);
      return $result === false && $data !== 'b:0;' ? [] : $result;
   }
}

$fileenv = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (file_exists($fileenv) && is_readable($fileenv)) {
   $dotenv = fopen($fileenv, 'r');
   if ($dotenv) {
      while (($line = fgets($dotenv)) !== false) {
         // Ignorar comentarios y líneas vacías
         $trimmedLine = trim($line);
         if ($trimmedLine === '' || strpos($trimmedLine, '#') === 0) {
            continue;
         }
         if (preg_match('/\A([a-zA-Z0-9_]+)=(.*)\z/', $trimmedLine, $matches)) {
            $_ENV[$matches[1]] = $matches[2];
         }
      }
      fclose($dotenv);
   }
}