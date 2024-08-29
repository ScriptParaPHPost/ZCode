<?php 
/**
 * Controlador
 *
 * @name    perfil.php
 * @author  ZCode | PHPost
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "perfil";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 0;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo']; 	// TITULO DE LA PAGINA ACTUAL

/*++++++++ = ++++++++*/
	
	// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1){	
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso",$tsLevelMsg);
		//
		$tsContinue = false;
	}
	//
	if($tsContinue){
/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	$username = $tsCore->setSecure($_GET['user']);
	$usuario = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_activo, user_baneado FROM @miembros WHERE user_name = '$username'"));
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
		include TS_ZCODE . 'RedesDataIcon.php';
		include TS_MODELS . "c.cuenta.php";
		$tsCuenta = new tsCuenta();

		$tsInfo = $tsCuenta->loadHeadInfo($usuario['user_id']);
		$tsInfo['uid'] = $usuario['user_id'];
		// IS ONLINE?
		$tsInfo['status'] = $tsCore->statusUser($usuario['user_id']);
		// GENERAL
		$tsGeneral = $tsCuenta->loadGeneral($usuario['user_id']);
	 	$tsInfo['nick'] = $tsInfo['user_name'];
	 	$tsInfo = array_merge($tsInfo, $tsGeneral);
	 	// PAIS
		$tsInfo['pais'] = [
			'icon' => $tsInfo['user_pais'],
			'name' => $tsPaises[$tsInfo['user_pais']]
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
	 	include_once TS_MODELS . "c.muro.php";
	 	$tsMuro = new tsMuro();
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

	include TS_ROOT . 'footer.php';

}