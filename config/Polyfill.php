<?php

/**
 * @name Polyfill.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
**/

if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);

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
      return (!is_null($data) && ($data !== false || $data === 'b:0;')) ? unserialize($data) : [];
   }
}

$fileenv = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
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