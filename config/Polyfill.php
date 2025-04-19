<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 3.1.18
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

if(file_exists(__DIR__ . '/../.env')) {
   $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', null, false, null);
   $dotenv->load();
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
