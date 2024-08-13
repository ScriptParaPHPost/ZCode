<?php 
/**
 * Controlador
 *
 * @name    fotos.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "fotos";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

/*++++++++ = ++++++++*/
	// PARA LAS FOTOS...
	$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : '';
	if((int)$tsCore->settings['c_fotos_private'] === 0) {	
		if($action == '' || $action == 'ver') $tsLevel = 0;		
	} else {		
		$tsLevel = 2;		
	}
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
	if($tsContinue) {

		include TS_CLASS . "c.fotos.php";
		$tsFotos = new tsFotos();

		switch($action) {
			case '':
				$smarty->assign("tsLastFotos", $tsFotos->getLastFotos());
				$smarty->assign("tsLastComments", $tsFotos->getLastComments());
				$stats = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT stats_miembros, stats_fotos, stats_foto_comments FROM @stats WHERE stats_no = 1'));
				$smarty->assign("tsStats", $stats);
			break;
			 case 'agregar':
				if(!empty($_POST['titulo'])) {
					$result = $tsFotos->newFoto();
					$tsPage = 'aviso';
					if(!is_array($result) && $result > 0){
						$titulo = $tsCore->setSecure($_POST['titulo']);
						$smarty->assign("tsAviso",array('titulo' => 'Foto Agregada', 'mensaje' => "La imagen <b>".$titulo."</b> fue agregada.", 'but' => 'Ver imagen', 'link' => "{$tsCore->settings['url']}/fotos/{$tsUser->nick}/{$result}/".$tsCore->setSEO($titulo).".html"));
					} else {
						$smarty->assign("tsAviso",array('titulo' => 'Opps...', 'mensaje' => $result, 'but' => 'Volver', 'link' => "{$tsCore->settings['url']}/fotos/agregar.php"));
					}
				}
					
			  break;
			  case 'editar':
					if(empty($_POST['titulo'])){
						 $tsFoto = $tsFotos->getFotoEdit();
						 if(!is_array($tsFoto)){
							  $tsPage = 'aviso';
							  $smarty->assign("tsAviso",array('titulo' => 'Opps...', 'mensaje' => $tsFoto, 'but' => 'Ir a Fotos', 'link' => "{$tsCore->settings['url']}/fotos/"));
						 }
						 else $smarty->assign("tsFoto", $tsFoto);
					} else {
						 $tsPage = 'aviso';
						 $tsFoto = $tsFotos->editFoto();
						 $smarty->assign("tsAviso",array('titulo' => 'Opps...', 'mensaje' => $tsFoto, 'but' => 'Ir a Fotos', 'link' => "{$tsCore->settings['url']}/fotos/"));
					}
			  break;
			  case 'borrar':
					$tsAjax = 1;
					echo $tsFotos->delFoto();
			  break;
			case 'ver':
				$tsFoto = $tsFotos->getFoto();
				// TITULO
				$tsTitle = "{$tsFoto['foto']['f_title']} - {$tsFoto['foto']['user_name']} | {$tsCore->settings['titulo']}";
				
				if(((int)$tsFoto['foto']['f_status'] === 1 && (!$tsUser->is_admod && $tsUser->permisos['moacp'] == false)) OR ((int)$tsFoto['foto']['exist'] === 0)) {
					if((int)$tsFoto['foto']['exist'] === 0) {
						$message = 'Esta foto no existe';
					} else {
						$message = 'Esta foto se encuentra en revisi&oacute;n por acumulaci&oacute;n de denuncias';
					}
					$tsPage = 'aviso';
					$smarty->assign("tsAviso", [
						'titulo' => 'Opps...', 
						'mensaje' => $message, 
						'but' => 'Ir a Fotos', 
						'link' => "{$tsCore->settings['url']}/fotos/"
					]);
				} else {
					$smarty->assign("tsFoto", $tsFoto['foto']);
					$smarty->assign("tsUltimasFotos", $tsFoto['ultimas_fotos']);
					$smarty->assign("tsAmigosFotos", $tsFoto['amigos']);
					$smarty->assign("tsComentariosFotos", $tsFoto['comentarios']);
					$smarty->assign("tsVisitasFotos", $tsFoto['visitas']);
					$smarty->assign("tsMedallasFotos", $tsFoto['medallas']);
				}
			break;
			case 'album':
				$username = $_GET['user'];
				$user_id = $tsUser->getUserID($username);
				if(empty($user_id)){
					$tsPage = 'aviso';
					$smarty->assign("tsAviso",array('titulo' => 'Opps...', 'mensaje' => 'Este usuario no existe.', 'but' => 'Ir a Fotos', 'link' => "{$tsCore->settings['url']}/fotos/"));
				} else {
					$tsFotox = $tsFotos->getFotos($user_id);
					$smarty->assign("tsFotos", $tsFotox);
					$smarty->assign("tsFUser", array($user_id, $username));
				}
			break;
		 }

/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	 $smarty->assign("tsAction",$action);
	 
}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}