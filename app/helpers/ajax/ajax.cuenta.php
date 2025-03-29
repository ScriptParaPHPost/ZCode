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

$files = [
   'cuenta-guardar' => ['n' => 2, 'p' => ''],
   'cuenta-avatar-gif' => ['n' => 2, 'p' => ''],
   'cuenta-avatar-change' => ['n' => 2, 'p' => ''],
   'cuenta-desvincular' => ['n' => 2, 'p' => ''],
   'cuenta-customizer' => ['n' => 2, 'p' => ''],
	'cuenta-qr-regenerate' => ['n' => 2, 'p' => 'regenerate'],
	'cuenta-token-regenerate' => ['n' => 2, 'p' => ''],
	'cuenta-two-factor' => ['n' => 2, 'p' => ''],
	'cuenta-delete-2fa' => ['n' => 2, 'p' => ''],
	'cuenta-desactivate' => ['n' => 2, 'p' => ''],
	'cuenta-eliminar-tiempo' => ['n' => 2, 'p' => ''],
	'cuenta-avatar-social' => ['n' => 2, 'p' => ''],
   'cuenta-scheme' => ['n' => 2, 'p' => ''],
   'cuenta-color' => ['n' => 2, 'p' => ''],
   'cuenta-family' => ['n' => 2, 'p' => ''],
   'cuenta-size' => ['n' => 2, 'p' => ''],
   'cuenta-pagebox' => ['n' => 2, 'p' => '']
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.cuenta.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) {
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASE
require_once TS_MODELS . "c.cuenta.php";
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
	case 'cuenta-family':
	case 'cuenta-size':
	case 'cuenta-pagebox':

		$columna = match($action) {
			'cuenta-color' => 'user_color',
			'cuenta-scheme' => 'user_scheme',
			'cuenta-family' => 'user_font_family',
			'cuenta-size' => 'user_font_size',
			'cuenta-pagebox' => 'user_pagebox',
			default => null
		};
		
		echo $tsCuenta->saveThemeOption($columna);
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
	case 'cuenta-token-regenerate':
		echo $tsCuenta->regenerateToken();
	break;
	case 'cuenta-two-factor':
		echo $tsCuenta->activeTwoFactor();
	break;
	case 'cuenta-delete-2fa':
		echo $tsCuenta->removeTwoFactor();
	break;
	case 'cuenta-eliminar-tiempo':
		$opcion = (int)$_POST['outtime_type'];
		echo $tsUser->deleteUserOutTime($opcion, time());
	break;
	case 'cuenta-avatar-social':
		echo $tsCuenta->activeAvatarSocial();
	break;
}