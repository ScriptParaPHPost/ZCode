<?php

/**
 * @name migrator.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcode-script/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.7.0
 * @description Para actualizar la base de datos sin intervension
**/

include TS_EXTRA . 'database.php';

// Función para verificar si una tabla existe en la base de datos
function tableExists($mysqli, $table) {
   $result = $mysqli->query("SHOW TABLES LIKE '$table'");
   return $result && $result->num_rows > 0;
}

// Función para obtener las columnas de una tabla
function getTableColumns($mysqli, $table) {
   $columns = [];
   $result = $mysqli->query("SHOW COLUMNS FROM $table");
   while ($row = $result->fetch_assoc()) {
      $columns[] = $row['Field'];
   }
   return $columns;
}

// Variable para el estado del proceso
$success = true;

// Iterar sobre las consultas de creación de tablas
foreach ($zcode_sql as $sql) {
   // Ignorar líneas que comiencen con "INSERT INTO"
   if (strpos($sql, 'INSERT INTO') === 0) {
      continue;
   }
   if (preg_match('/CREATE TABLE IF NOT EXISTS `(.+?)`/', $sql, $matches)) {
     	$table = $matches[1];
     	// Verificar si la tabla existe
      if (tableExists($mysqli, $table)) {
         //echo "La tabla <strong>$table</strong> ya existe. Verificando columnas...<br>";
         // Obtener las columnas actuales de la tabla
         $currentColumns = getTableColumns($mysqli, $table);
         // Extraer las columnas definidas en el archivo database.php
         preg_match_all('/`(\w+)` (?:[a-zA-Z]+(?:\([\d,]+\))?|TEXT|BLOB|DATE|TIMESTAMP|INTEGER|SMALLINT|BIGINT|FLOAT|DOUBLE|DECIMAL|BOOLEAN|CHAR|VARCHAR|BINARY|VARBINARY|ENUM|SET)(?: (?:NOT NULL|NULL|DEFAULT|AUTO_INCREMENT|UNSIGNED|ZEROFILL))*[,]?/', $sql, $columns);
         $expectedColumns = $columns[1];
        	// Añadir columnas que no existen en la tabla actual
        	foreach ($expectedColumns as $column) {
        	   if (!in_array($column, $currentColumns)) {
        	      //echo "Añadiendo columna <strong>$column</strong> a la tabla <strong>$table</strong>...<br>";
        	      // Aquí puedes construir una consulta ALTER TABLE para añadir la columna
        	      $alterSQL = "ALTER TABLE $table ADD COLUMN `$column` TEXT"; // Ajustar el tipo de columna según sea necesario
        	     	if (!$mysqli->query($alterSQL)) {
                  $success = false;
               }
        	   }
        	}
            
         // Eliminar columnas que no están en el archivo database.php
         foreach ($currentColumns as $column) {
            if (!in_array($column, $expectedColumns)) {
               //echo "Eliminando columna <strong>$column</strong> de la tabla <strong>$table</strong>...<br>";
               $alterSQL = "ALTER TABLE $table DROP COLUMN `$column`";
               if (!$mysqli->query($alterSQL)) {
                  $success = false;
               }
            }
         }  
      } else {
         // Crear la tabla si no existe
         //echo "Creando tabla <strong>$table</strong>...<br>";
        	if (!$mysqli->query($sql)) {
            $success = false;
         }
      }
   }
}

// Verificar si hay tablas en la base de datos que no estén en el archivo database.php
$tablesInDb = $mysqli->query("SHOW TABLES LIKE '{$db['prefix']}%'");
$tablesInDbArray = [];
while ($row = $tablesInDb->fetch_array()) {
   $tablesInDbArray[] = $row[0];
}

$tablesInFileArray = [];
foreach ($zcode_sql as $sql) {
   // Ignorar líneas que comiencen con "INSERT INTO"
   if (strpos($sql, 'INSERT INTO') === 0) {
      continue;
   }
   if (preg_match('/CREATE TABLE IF NOT EXISTS `(.+?)`/', $sql, $matches)) {
      $tablesInFileArray[] = $matches[1];
   }
}

// Eliminar tablas que no están en el archivo database.php
foreach ($tablesInDbArray as $tableInDb) {
   if (!in_array($tableInDb, $tablesInFileArray)) {
      //echo "Eliminando tabla <strong>$tableInDb</strong>...<br>";
     	if (!$mysqli->query("DROP TABLE IF EXISTS $tableInDb")) {
         $success = false;
      }
   }
}