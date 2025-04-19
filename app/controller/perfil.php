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

use app\models\{Cuenta,Muro};

$tsPage = "perfil";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

$tsContinue = true;

include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

$tsTitle = $tsCore->settings['titulo'];

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if(!$tsLevelMsg) {	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {
	
	$username = $tsCore->setSecure($_GET['user']);
	$usuario = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_activo, user_baneado FROM @miembros WHERE user_name = '$username'"));

	if($tsUser->is_banned) {
		$banned_data = $tsUser->getUserBanned();
		if(!empty($banned_data)) {
			// SI NO ES POR AJAX
			if(empty($_GET['action'])){
				$smarty->assign('tsBanned', $banned_data);
				$smarty->display('suspension.tpl');
			} else die('<div class="emptyError">Usuario suspendido</div>');
			//
			exit;
		}
	}
	// EXISTE?
	if(empty($usuario['user_id']) || ($usuario['user_activo'] != 1 && !$tsUser->permisos['movcud'] && !$tsUser->is_admod) || ($usuario['user_baneado'] != 0 && !$tsUser->permisos['movcus'] && !$tsUser->is_admod)) {
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso", [
			'titulo' => 'Opps!', 
			'mensaje' => empty($usuario['user_id']) ? 'El usuario no existe' : "La cuenta de {$usuario['user_name']} se encuentra inhabilitada", 
			'but' => 'Ir a p&aacute;gina principal'
		]);
	} else {
		//
		include TS_JUNK . 'RedesDataIcon.php';
		include TS_JUNK . 'Paises.php';
		$tsCuenta = new Cuenta;

		$tsInfo = $tsCuenta->loadHeadInfo($usuario['user_id']);
		$tsInfo['uid'] = $usuario['user_id'];
		// IS ONLINE?
		$tsInfo['status'] = $tsZCode->statusUser($usuario['user_id']);
		// GENERAL
		$tsGeneral = $tsCuenta->loadGeneral($usuario['user_id']);
	 	$tsInfo['nick'] = $tsInfo['user_name'];
	 	$tsInfo = array_merge($tsInfo, $tsGeneral);
	 	// PAIS
		$tsInfo['pais'] = [
			'icon' => $tsInfo['user_pais'],
			'name' => $tsPaises[$tsInfo['user_pais']] ?? ''
		];
		// LO SIGO?
		$tsInfo['follow'] = $tsCuenta->iyfollow($usuario['user_id'], 'iFollow');
		// ME SIGUE?
		$tsInfo['yfollow'] = $tsCuenta->iyfollow($usuario['user_id'], 'yFollow');
	 	// MANDAR A PLANTILLA
		$smarty->assign("tsInfo", $tsInfo);
		$smarty->assign("tsRedes", $redes);
		$smarty->assign("tsGeneral", $tsGeneral);
	 	// MURO
	 	$tsMuro = new Muro();
	 	// PERMISOS
	 	$privacity = $tsMuro->getPrivacity($usuario['user_id'], $username, $tsInfo['follow'], $tsInfo['yfollow']);
	 	// SE PERMITE VER EL MURO?
	 	if($privacity['m']['v'] == true) {
    		// Determinar el tipo de contenido a cargar
    		$tsType = 'wall';
    		$tsData = null;
		  	// CARGAR HISTORIA
		  	if(!empty($_GET['pid'])) {
				$pub_id = $tsCore->setSecure($_GET['pid']);
				$story = $tsMuro->getStory($pub_id, $usuario['user_id']);
				//
				if(!is_array($story)){
					$tsPage = 'aviso';
					$smarty->assign("tsAviso", [
						'titulo' => 'Opps...', 
						'mensaje' => $story, 
						'but' => 'Ir a pagina principal', 
						'link' => $tsCore->settings['url']
					]);
				} else {
					$story['data'][1] = $story;
            	$tsType = 'story';
            	$tsData = $story;
				}
		  	} elseif((int)$tsCore->settings['c_allow_portal'] == 0 && $tsInfo['uid'] == $tsUser->uid) {
				$tsType = 'news';
        		$tsData = $tsMuro->getNews();
		  	} else {
				$tsType = 'wall';
        		$tsData = $tsMuro->getWall($usuario['user_id']);
		  	}
			$smarty->assign("tsMuro", $tsData);
			$smarty->assign("tsType", $tsType);
	 	}
	 	$smarty->assign("tsPrivacidad", $privacity);
		// TITULO
		$tsTitle = 'Perfil de '.$tsInfo['nick'].' - '.$tsTitle;
	}
}

if(empty($tsAjax)) {	

	$smarty->assign("tsTitle", $tsTitle);

	include BASEPATH . 'footer.php';

}