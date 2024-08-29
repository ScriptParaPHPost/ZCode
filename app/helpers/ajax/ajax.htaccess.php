<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.htaccess.php
 * @author  Miguel92
*/


$files = [
   'htaccess-backup' => ['n' => 4, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.htaccess.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

include TS_MODELS . "c.admin.php";
$tsAdmin = new tsAdmin();

// CODIGO
switch($action){
	case 'htaccess-backup':
		echo $tsAdmin->createCopy();
	break;
}