<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.github.php
 * @author  Miguel92
*/


$files = [
   'github-api' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.github.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

include TS_CLASS . 'c.actualizacion.php';
$tsActualizacion = new tsActualizacion;

// CODIGO
switch($action){
	case 'github-api':

		$tsActualizacion->BRANCH = isset($_POST['branch']) ? $tsCore->setSecure($_POST['branch']) : 'alpha';

		$last = $tsActualizacion->getLastCommit();
		$response = $tsActualizacion->api_response('info');
	
		echo json_encode($response->commit);

	break;
}