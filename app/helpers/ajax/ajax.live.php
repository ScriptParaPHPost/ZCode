<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'live-stream' => ['n' => 2, 'p' => 'stream'],
	'live-avatar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.live.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CODIGO
switch($action){
	case 'live-stream':
		// NOTIFICACIONES
		$tsStream = (isset($_POST['notifications']) && $_POST['notifications'] === 'ON') ? $tsMonitor->getNotificaciones(true) : 0;
		// MENSAJES
		$tsMensajes = (isset($_POST['messages']) && $_POST['messages'] === 'ON') ? $tsMP->getMensajes(1, true, 'live') : 0;
		$smarty->assign("tsStream", $tsStream);
		$smarty->assign("tsMensajes", $tsMensajes);
	break;
	case 'live-avatar':
		echo $tsZCode->getAvatar((int)$_GET['uid'], 'use');
	break;
	default:
		die('0: Este archivo no existe.');
	break;
}