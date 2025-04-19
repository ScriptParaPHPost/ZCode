<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

use app\models\Cuenta;
use Symfony\Component\Clock\NativeClock;
use OTPHP\TOTP;


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
$tsCuenta = new Cuenta();

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
		$issuer = trim($tsCore->settings['titulo']);

		$clock = new NativeClock();
		$totp = TOTP::generate($clock);
		$totp->setLabel("$issuer v3 [{$tsUser->nick}]");
		$totp->setIssuer($issuer);

		$secret = $totp->getSecret();
		$grCodeUri = $totp->getQrCodeUri('https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=250x250&ecc=M', '[DATA]');

		// Asignar a Smarty
		$smarty->assign("tsGenerateNewQR", $grCodeUri);
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