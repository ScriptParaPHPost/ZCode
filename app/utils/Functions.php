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

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

use app\core\Helper;

Helper::ensureLogDirectoryExists(__DIR__ . '/../storage/logs/');
if(Helper::isInstallerNeeded()) {
   header("Location: ./install/");
   exit;
}

if (!isset($tsUser)) {
   $tsUser = new stdClass(); // Evita errores de propiedad en null
   $tsUser->is_admod = false; // Asigna valores por defecto
}

include_once TS_APP . 'core/ShowError.php';
include_once TS_APP . 'core/MySQLi.php';

function addDataToTable(array $array = [], string $tabla = '', array $datos = [], string $prefijo = '') {
   if(empty($tabla) OR empty($datos)) {
   	throw new InvalidArgumentException('No hay datos ingresados');
   }
   // Convertir el array en una cadena para la consulta INSERT INTO
   $prefixedKeys = array_map(function ($key) use ($prefijo) {
      return $prefijo . $key;
   }, array_keys($datos));
   // Convertir el array en una cadena para la consulta INSERT INTO
   $keys = implode(', ', $prefixedKeys);
   //
   $values = array_map(function ($value) {
      // Si el valor es numérico, no agregamos comillas
      return is_numeric($value) ? $value : "'$value'";
   }, array_values($datos));
   $insertString = '(' . implode(', ', $values) . ')';
   //
   return db_exec($array, 'query', "INSERT INTO $tabla ($keys) VALUES $insertString");
}

function removeDataById(array $fileline = [], string $isTable = '', string $where_id = '') {
   if (empty($isTable)) {
      throw new InvalidArgumentException('No hay tabla');
   }
   if (empty($where_id)) {
      throw new InvalidArgumentException('No hay dato para eliminar');
   }
   return db_exec($fileline, 'query', "DELETE FROM $isTable WHERE $where_id");   
}

function statsUpdate(array $fileline = [], array $isData = [], bool $sum = false) {
   // Verificar que las claves necesarias existen en el array $isData
   if (!isset($isData['table']) || !isset($isData['columna']) || !isset($isData['donde'])) {
      throw new InvalidArgumentException("Faltan claves necesarias en el array isData.");
   }
   
   $isTable = $isData['table'];
   $isColumn = $isData['columna'];
   $isCount = $sum ? '+ 1' : '- 1';
   $where_id = $isData['donde'];
   
   // Usar consultas preparadas para mayor seguridad
   $query = "UPDATE $isTable SET $isColumn = $isColumn $isCount WHERE $where_id";
   
   db_exec($fileline, 'query', $query);
}

function updateRecordById(array $fileline = [], string $isTable = '', string $isData = '', string $where_id = '') {
   if (empty($isTable)) {
      throw new InvalidArgumentException('No hay tabla');
   }
   if (empty($where_id)) {
      throw new InvalidArgumentException('No hay dato para eliminar');
   }
   db_exec($fileline, 'query', "UPDATE $isTable SET $isData WHERE $where_id");   
}
