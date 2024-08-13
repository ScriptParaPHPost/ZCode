<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.cuenta.php
 * @author  Miguel92
*/


$files = [
   'cuenta-guardar' => ['n' => 2, 'p' => ''],
   'cuenta-avatar-gif' => ['n' => 2, 'p' => ''],
   'cuenta-color' => ['n' => 2, 'p' => ''],
   'cuenta-avatar-change' => ['n' => 2, 'p' => ''],
   'cuenta-desvincular' => ['n' => 2, 'p' => ''],
   'cuenta-customizer' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.cuenta.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CLASE
require_once TS_CLASS . "c.cuenta.php";
$tsCuenta = new tsCuenta();

$pagina = htmlspecialchars($_POST['pagina'] ?? '');

// CODIGO
switch($action){
	case 'cuenta-guardar':
		echo $tsCuenta->saveSettings($pagina);
	break;
	case 'cuenta-avatar-gif':
		echo $tsCuenta->saveAvatarGif();
	break;
	case 'cuenta-color':
	case 'cuenta-scheme':
		$columna = ($action === 'cuenta-color') ? 'user_color' : 'user_scheme';
		echo $tsCuenta->saveColorScheme($columna);
	break;
	case 'cuenta-avatar-change':
		echo $tsCuenta->changeAvatar();
	break;
	case 'cuenta-desvincular':
		echo $tsUser->unlinkAccount();
	break;
	case 'cuenta-customizer':
		echo $tsCuenta->saveColorCustomizer();
	break;
}