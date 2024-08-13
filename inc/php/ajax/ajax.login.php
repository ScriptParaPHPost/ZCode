<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.login.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'login-user' => array('n' => 1, 'p' => ''),
		'login-activar' => array('n' => 1, 'p' => ''),
		'login-form' => array('n' => 1, 'p' => 'form'),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.login.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg; die(); }
	// CODIGO
	switch($action){
		case 'login-user':
			//<---
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
			$smarty->assign('OAuth', $tsCore->OAuth());
		break;
		case 'login-salir':
			//<---
			$tsUser->logoutUser($tsUser->uid, true);
			//--->
		break;
	}