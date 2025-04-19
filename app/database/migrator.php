<?php

$mysqli = require __DIR__ . '/../../config/database.php';
$logFile = __DIR__ . '/migrations_log.json';

$migrations = glob(__DIR__ . '/migrations/*.php');
sort($migrations);

$executed = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

$prefix = $_ENV['ZCODE_DB_PREFIX'];

foreach ($migrations as $file) {
   $name = basename($file);
   if (!in_array($name, $executed)) {
      echo "🔧 Ejecutando migración: $name\n";
      require $file;
      $executed[] = $name;
   }
}

file_put_contents($logFile, json_encode($executed, JSON_PRETTY_PRINT));
echo "✅ Migraciones completadas.\n";
