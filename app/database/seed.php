<?php

$mysqli = require __DIR__ . '/../../config/database.php';
$logFile = __DIR__ . '/seeders_log.json';

$seeders = glob(__DIR__ . '/seeders/*.php');
sort($seeders);

$executed = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

$prefix = $_ENV['ZCODE_DB_PREFIX'];

foreach ($seeders as $file) {
   $name = basename($file);
   echo "🌱 Ejecutando seeder: $name\n";
   require $file;
}

file_put_contents($logFile, json_encode($executed, JSON_PRETTY_PRINT));
echo "✅ Seeders completados.\n";
