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
	'login-user' => ['n' => 1, 'p' => ''],
	'login-activar' => ['n' => 1, 'p' => ''],
	'login-form' => ['n' => 1, 'p' => 'form'],
	'login-validar' => ['n' => 1, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.login.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die(); 
}

// CODIGO
switch($action){
	case 'login-user':
		$user = $tsCore->setSecure($_POST['nick']);
		$pass = $tsCore->setSecure($_POST['pass']);
		$reme = ($_POST['rem'] == 'true') ? true : false;
		$tsUser->is_type = 'login';
		$tsUser->response = $tsCore->setSecure($_POST['response']);
		//
		if(empty($user) or empty($pass)) echo '0: Faltan datos';
		else echo $tsUser->loginUser($user, $pass, $reme);
		//--->
	break;
	case 'login-activar':
		//<--
		$activar = $tsUser->userActivate();
		if($activar['user_password']) {
			$tsUser->is_type = 'activar';
			$tsUser->loginUser($activar['user_nick'], $activar['user_password'], true, $tsCore->settings['url'].'/cuenta/');
		} else {
			$tsPage = "aviso";
			$tsAjax = 0;
			$tsAviso = array('titulo' => 'Error al activar tu cuenta', 'mensaje' => 'El c&oacute;digo de validaci&oacute;n es incorrecto.');
			//
			$smarty->assign("tsAviso",$tsAviso);
		}
		//-->
	break;
	case 'login-form':
		// Solo debo poner esto
		include TS_ZCODE . 'OAuthentication.php';
		$OAuthentication = new OAuthentication();
		$smarty->assign('OAuth', $OAuthentication->OAuth($tsCore->currentUrl()));
	break;
	case 'login-salir':
		//<---
		$tsUser->logoutUser($tsUser->uid, true);
		//--->
	break;
	case 'login-validar':
		echo $tsUser->validateTwoFactor();
	break;
}