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
	'cuenta-qr-regenerate' => ['n' => 2, 'p' => 'regenerate'],
	'cuenta-two-factor' => ['n' => 2, 'p' => ''],
	'cuenta-delete-2fa' => ['n' => 2, 'p' => ''],
	'cuenta-desactivate' => ['n' => 2, 'p' => '']
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
	case 'cuenta-desactivate':
		if(!empty($_POST['validar'])) echo $tsCuenta->desCuenta();
	break;
	case 'cuenta-qr-regenerate':
		include GOOGLE2FA . "GoogleAuthStart.php";
		$authenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

		# Comprobamos que tenga el 2FA desactivado
		$secret = $authenticator->generateSecret();

	   # Generamos el código QR
	   $issuer = trim($tsCore->settings['titulo']);
	   $accountName = rawurlencode("{$tsCore->settings['titulo']} [{$tsUser->nick}]");

	   $generate = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($accountName, $secret, $issuer, 250);
	   # Asignamos una variable para mostrar la imagen
	   $smarty->assign("tsGenerateNewQR", $generate);
	   $smarty->assign("tsSecret", $secret);
	break;
	case 'cuenta-two-factor':
		echo $tsCuenta->activeTwoFactor();
	break;
	case 'cuenta-delete-2fa':
		echo $tsCuenta->removeTwoFactor();
	break;
}