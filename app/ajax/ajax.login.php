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

use app\models\OAuthentication;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'login-user' => ['n' => 1, 'p' => ''],
	'login-activar' => ['n' => 1, 'p' => ''],
	'login-form' => ['n' => 1, 'p' => 'form'],
	'login-validar' => ['n' => 1, 'p' => ''],
	'login-salir' => ['n' => 0, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.login.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

$tsLevelMsg = $tsCore->setLevel($tsLevel, true);

// Verificamos si la acción requiere CSRF y si falla el nivel o el token
if ($action !== 'login-salir' && ($tsLevelMsg != 1 || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf'])) {
   $errorMsg = ($_POST['csrf_token'] !== $_SESSION['csrf']) ? 'CSRF inválido' : $tsLevelMsg;
   echo '0: ' . $errorMsg;
   die();
}

// CODIGO
switch($action){
	case 'login-user':
		$user = $tsCore->setSecure($_POST['nick']);
		$pass = $tsCore->setSecure($_POST['pass']);
		$reme = ($_POST['rem'] == 'true') ? true : false;
		$tsUser->is_type = 'login';
		//
		echo (empty($user) || empty($pass)) ? '0: Faltan datos' : $tsUser->loginUser($user, $pass, $reme);
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
		$OAuthentication = new OAuthentication();
		$smarty->assign('OAuth', $OAuthentication->OAuth($tsCore->currentUrl()));
	break;
	case 'login-salir':
		//<---
		$tsUser->logoutUser($tsUser->uid, true);
		//--->
	break;
	case 'login-validar':
		$_POST['csrf_token'] = $_SESSION['csrf'];
		echo $tsUser->validateTwoFactor();
	break;
}