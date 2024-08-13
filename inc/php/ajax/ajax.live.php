<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.live.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'live-stream' => array('n' => 2, 'p' => 'stream'),
		'live-avatar' => array('n' => 2, 'p' => ''),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.live.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	// CODIGO
	switch($action){
		case 'live-stream':
			// NOTIFICACIONES
			$tsStream = ($_POST['notifications'] === 'ON') ? $tsMonitor->getNotificaciones(true) : 0;
			
			// MENSAJES
			$tsMensajes = ($_POST['messages'] === 'ON') ? $tsMP->getMensajes(1, true, 'live') : 0;
			
			$smarty->assign("tsStream", $tsStream);
			$smarty->assign("tsMensajes", $tsMensajes);
		break;
		case 'live-avatar':
			echo $tsCore->getAvatar((int)$_GET['uid'], 'use');
		break;
		default:
			die('0: Este archivo no existe.');
		break;
	}