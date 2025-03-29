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
	'notificaciones-ajax' => ['n' => 2, 'p' => 'ajax'],
	'notificaciones-filtro' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.notificaciones.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
//
$how = $_POST['action'];
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die();
}

// CODIGO
switch($action){
	case 'notificaciones-ajax':
		$tsAjax = 1; // AJAX
		switch($how) {
			case 'last':
				$tsAjax = 0; // AJAX
				$notificaciones = $tsMonitor->getNotificaciones();
				$smarty->assign("tsData",$notificaciones['data']);
			break;
			case 'follow':
				echo $tsMonitor->setFollow();
			break;
			case 'unfollow':
				echo $tsMonitor->setUnFollow();
			break;
			case 'spam':
				echo $tsMonitor->setSpam();
			break;
		}
	break;
   case 'notificaciones-filtro':
      echo $tsMonitor->setFiltro();
   break;
}

// HACK xD
$_GET['ts'] = true;
