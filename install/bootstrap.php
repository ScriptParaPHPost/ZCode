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

session_start();

define('_ZCMS', true);

require_once __DIR__ . '/procesos/functions.php';
// Reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/install.log');

$FN = new Functions(__DIR__ . '/../.env.example', __DIR__ . '/../.env');

// SCRIPT INFO
$_SESSION['script'] = 'ZCode';
$_SESSION['author'] = 'Miguel92';
$_SESSION['version'] = $FN->get_data_file(__DIR__ . '/../.version');

$message = "";

// Define las etapas del instalador
$steps = ['licencia', 'requisitos', 'datos', 'finalizar', 'fail'];

# Forzamos
if(empty($FN->setInput('action'))) {
	header("Location: ?action=licencia&version=" . $FN->get_data_file(__DIR__ . '/../.version'));
}

if(!in_array($FN->setInput('action'), $steps)) {
	die('Esta acción no pertenece al instalador...');
}

$continue = true;