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

		$branch = isset($_POST['branch']) ? $tsCore->setSecure($_POST['branch']) : 'alpha';

		$url = $tsActualizacion->getApiGithub() . "/$branch";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		$response = curl_exec($ch);
		curl_close($ch);

		echo $response;

	break;
}