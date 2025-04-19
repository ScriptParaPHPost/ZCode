<?php

require_once __DIR__ . '/Polyfill.php';

$db = [
	'host'   => $_ENV['DB_HOST'] ?? 'localhost',
	'user'   => $_ENV['DB_USER'] ?? 'root',
	'pass'   => $_ENV['DB_PASS'] ?? '',
	'name'   => $_ENV['DB_NAME'] ?? '',
	'prefix' => $_ENV['DB_PREFIX'] ?? ''
];

$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);

if ($mysqli->connect_error) {
	die('Error al conectar a la base de datos: ' . $mysqli->connect_error);
}

return compact('db', 'mysqli');
