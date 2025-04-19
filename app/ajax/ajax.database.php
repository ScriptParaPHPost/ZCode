<?php 

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 3.1.18
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/


// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
$files = [
	'database-analyze' => ['n' => 2, 'p' => ''],
	'database-optimize' => ['n' => 2, 'p' => ''],
	'database-repair' => ['n' => 2, 'p' => ''],
	'database-check' => ['n' => 2, 'p' => ''],
	'database-all' => ['n' => 2, 'p' => ''],
	'database-backup' => ['n' => 2, 'p' => ''],
	'database-backup-del' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.database.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die();
}

 // CLASE
include TS_MODELS . "c.database.php";
$tsDatabase = new tsDatabase();

$type = strtoupper(explode('-', $action)[1]);
// CODIGO
switch($action) {
	case 'database-analyze':
	case 'database-optimize':
	case 'database-repair':
	case 'database-check':
   	echo $tsDatabase->handleAction($type);
   break;
   case 'database-all':
   	echo $tsDatabase->allActions();
   break;
   case 'database-backup':
   	echo $tsDatabase->createBackup();
   break;
   case 'database-backup-del':
   	echo $tsDatabase->delBackup();
   break;
   default: 
   	echo 'Acción no válido!';
};
