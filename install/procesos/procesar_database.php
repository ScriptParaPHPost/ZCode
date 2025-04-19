<?php

/**
 * Si existen tablas, serán borradas, para evitar problemas
*/
if ($results = $mysqli->query("SHOW TABLES")) {
   while ($row = $results->fetch_row()) {
   	$mysqli->query("DROP TABLE IF EXISTS {$row[0]}");
   }
   $results->close();
} else {
   $message = "Error en la consulta: " . $mysqli->error;
   $continue = false;
}

include __DIR__ . '/MigrationRunner.php';

# RUTAS DE MIGRACIONES Y SEEDERS
$migrationsPath = BASE_PATH_APP . '/app/database/migrations/';
$seedersPath    = BASE_PATH_APP . '/app/database/seeders/';
$logFile        = BASE_PATH_APP . '/app/database/migrations_log.json';

if(file_exists($logFile)) unlink($logFile);

# EJECUTAMOS LAS MIGRACIONES Y SEEDERS
$migrationsLog = MigrationRunner::runMigrations($mysqli, $migrationsPath, $prefix);
$seedersLog = MigrationRunner::runMigrations($mysqli, $seedersPath, $prefix);
$allLogs = array_merge($migrationsLog, $seedersLog);
// Guardar el log de todo
MigrationRunner::saveLog($allLogs, $logFile);
# INSTALAMOS LA NUEVA BASE DE DATOS
$error = '';
// Verificamos si hubo errores
$success = array_filter($allLogs, fn($e) => $e['status'] === 'success');