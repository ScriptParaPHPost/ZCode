<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.fotos.php
 * @author  Miguel92
*/


$files = [
   'fotos-votar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.fotos.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CLASE
require_once TS_CLASS . "c.fotos.php";
$tsFotos = new tsFotos();

// CODIGO
switch($action){
	case 'fotos-votar':
		echo $tsFotos->votarFoto();
	break;
}