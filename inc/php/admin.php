<?php 
/**
 * Controlador
 *
 * @name    admin.php
 * @author  Miguel92 & PHPost.es
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "admin";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 4;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

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
	
	include_once TS_CLASS . "c.admin.php";

	// ACTION
	$action = htmlspecialchars($_GET['action'] ?? '');
	// ACTION 2
	$act = htmlspecialchars($_GET['act'] ?? '');
	// CLASE POSTS
	$tsAdmin = new tsAdmin();

	// Bienvenida
	if($action === '') {
		$tsTitle = 'Centro de Administración';
		$smarty->assign("tsAdmins", $tsAdmin->getAdmins());
      $smarty->assign("tsInst", $tsAdmin->getInst());

	// Creditos
	} elseif($action === 'creditos') {
		$tsTitle = 'Soporte y Cr&eacute;ditos';
		$smarty->assign("tsVersion", $tsAdmin->getVersions());

	// Base de datos
	} elseif($action === 'database') {
		$tsTitle = 'Base de datos';
    	require_once TS_CLASS . "c.database.php";
    	$tsDatabase = new tsDatabase;
    	$smarty->assign('tsTablesSQL', $tsDatabase->getAllTables());
    	if($act === 'lista') {
    		$smarty->assign('tsBackupSQL', $tsDatabase->getBackups());
    	}

   // Generador de favicon
   } elseif($action === 'favicon') {
		$tsTitle = 'Generador de favicon';
    	require_once TS_CLASS . "c.favicon.php";
    	$tsFavicon = new tsFavicon;
    	$smarty->assign('tsAllFavicons', $tsFavicon->getAllFavicons());

   // Generador de actualizacion
   } elseif($action === 'actualizacion') {
		$tsTitle = 'Generador de actualizacion';
    	require_once TS_CLASS . "c.actualizacion.php";
    	$tsActualizacion = new tsActualizacion;
    
    	$commits = $tsActualizacion->getLastCommit();
    	$getUpdated = $tsActualizacion->saveIDUpdate('get');
    	$smarty->assign([
    		'tsUpdated' => $getUpdated,
    		'tsLastCommit' => $commits
    	]);
    	if(is_string($commits) AND !empty($getUpdated)) {
    		$smarty->assign('tsLastCommitFiles', $tsActualizacion->getLastCommitFiles());
    		$files = $tsActualizacion->filesStatus();
    		$smarty->assign('tsFilesStatus', $files);
    		$smarty->assign('tsFilesTotal', safe_count($files));
    		if($act === 'actualizar') {
    			if($tsActualizacion->getFilesUpdate()) {
    				$tsActualizacion->saveIDUpdate('save', '');
    				$unset = ['sha', 'commit', 'files'];
    				foreach($unset as $del) unset($_SESSION[$del]);
    				$tsCore->redireccionar('admin', $action);
    			}
    		}
    	}

   // Configuraciones y Registro
	} elseif(in_array($action, ['configs', 'registro'])){
		$tsTitle = ($action === 'configs') ? 'Configuraci&oacute;n' : 'Registro de ' . $tsTitle;
		// GUARDAR CONFIGURACION
		if(!empty($_POST['titulo']) OR (!empty($_POST['pkey']) AND !empty($_POST['skey']))) {
			if($tsAdmin->saveConfig()) $tsCore->redireccionar('admin', $action, 'save=true');
		}

	// Redes sociales
	} elseif($action === 'socials') {
    	// CLASE MEDAL
    	require_once TS_CLASS . "c.socials.php";
    	$tsSocials = new tsSocials();
    	$smarty->assign('tsNetsSocials', [
    		'discord' => 'Discord',
    		'facebook' => 'Facebook',
    		'github' => 'Github',
    		'google' => 'Google'
    	]);
    	//
		$tsTitle = 'Configurar redes sociales';
		if(empty($act)) $smarty->assign('tsSocials', $tsSocials->getSocials());
		// Editar o Nuevo tema
		elseif(in_array($act, ['editar', 'nueva'])) {
			$tsTitle = ucfirst($act) . ' red social';
			if(!empty($_POST['save']) OR !empty($_POST['edit'])) {
				$social = ($act === 'editar') ? $tsSocials->saveSocial() : $tsSocials->newSocial();
				if($social) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				if($act === 'editar') $smarty->assign("tsSocial", $tsSocials->getSocial());
				if($act === 'nuevo') $smarty->assign("tsError", $tsSocials->newSocial());
			} 
		}

	// Seo
	} elseif($action === 'seo') {
    	// CLASE MEDAL
    	require_once TS_CLASS . "c.seo.php";
    	$tsSeo = new tsSeo();
    	
		$tsTitle = 'Configurar SEO';
		if(empty($act)) $smarty->assign('tsSeo', $tsSeo->getSeo());
		if(!empty($_POST['titulo'])) {
			if($tsSeo->saveSEO()) $tsCore->redireccionar('admin', $action, 'save=true');
		}

	// Control de mensajes
	} elseif($action == 'mensajes') {
		include_once TS_CLASS . "c.mensajes.php";
		$tsMensajes =new tsMensajes();
		if(empty($act)){
			$smarty->assign("tsControlMensajes", $tsMensajes->getMensajesControl());
		} elseif($act == 'leer'){
			$smarty->assign("tsDatamp", $tsMensajes->getDataMensajePrivado());
			$smarty->assign("tsLeermp", $tsMensajes->getLeerMensajePrivado());
		}

	// Temas
	} elseif($action === 'temas') {
		$tsTitle = 'Diseños / Temas';
		// VER TEMAS
		if(empty($act)) {
			# Instalamos themes automaticamente
			include TS_EXTRA . 'ThemeInstall.php';
			$installer->installThemes();
			$installer->verifyThemesInstalled();
			# Mostramos
			$smarty->assign("tsTemas", $tsAdmin->getTemas());
		}
		// Editar o Nuevo tema
		elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . ' tema';
			if(!empty($_POST['save']) OR !empty($_POST['path'])) {
				$ActTheme = ($act === 'editar') ? $tsAdmin->saveTema() : $tsAdmin->newTema();
				if($ActTheme) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				if($act === 'editar') $smarty->assign("tsTema", $tsAdmin->getTema());
				if($act === 'nuevo') $smarty->assign("tsError", $tsAdmin->newTema());
			} 
		} elseif($act === 'usar') {
			$tsTitle = 'Activar tema';
			if($tsAdmin->changeTema()) $tsCore->redireccionar('admin', $action, 'save=true');
		} elseif($act === 'borrar') {
			$tsTitle = 'Borrar tema';
			if(!empty($_POST['confirm'])) {
				if($tsAdmin->deleteTema()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
			$smarty->assign("tt", $_GET['tt']);
		}

	// Noticias
   } elseif($action === 'news'){
		$tsTitle = 'Noticias';
      if(empty($act)) $smarty->assign("tsNews", $tsAdmin->getNoticias());
      elseif($act === 'nuevo' && !empty($_POST['not_body'])){
         if($tsAdmin->newNoticia()) $tsCore->redireccionar('admin', $action, 'save=true');
      } elseif($act === 'editar'){
         if(!empty($_POST['not_body'])) {
            if($tsAdmin->editNoticia()) $tsCore->redireccionar('admin', $action, 'save=true');
         } else $smarty->assign("tsNew", $tsAdmin->getNoticia());
      }  elseif($act === 'borrar'){
         if($tsAdmin->delNoticia()) $tsCore->redireccionar('admin', $action, 'borrar=true');
		}

	// Publicidades
	} elseif($action === 'ads'){
		$tsTitle = 'Publicidades';
		if(!empty($_POST['save'])){
			if($tsAdmin->saveAds()) $tsCore->redireccionar('admin', $action, 'save=true');
		}

	// POSTS
	} elseif($action === 'posts'){
		$tsTitle = 'Todos los posts';
		if(!$act) $smarty->assign("tsAdminPosts", $tsAdmin->GetAdminPosts());

	//FOTOS
	} elseif($action === 'fotos') {
		$tsTitle = 'Todas las fotos';
		if(!$act) $smarty->assign("tsAdminFotos", $tsAdmin->GetAdminFotos());

	// ESTADÍSTICAS
	} elseif($action === 'stats'){
		$tsTitle = 'Estad&iacute;sticas';
		$smarty->assign("tsAdminStats", $tsAdmin->GetAdminStats());	

	// CAMBIOS DE NOMBRE DE USUARIO
	} elseif($action === 'nicks'){
		$tsTitle = 'Nicks';
		$smarty->assign("tsAdminNicks", $tsAdmin->getChangeNicks($act));

   // LISTA NEGRA
   } elseif($action === 'blacklist') {
		$tsTitle = 'Lista negra';
		if(!$act) $smarty->assign("tsBlackList",$tsAdmin->getBlackList());
		elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . ' lista negra';
         if($_POST['edit'] OR $_POST['new']){
            $response = ($act === 'editar') ? $tsAdmin->saveBlock() : $tsAdmin->newBlock();
				if($response) $tsCore->redireccionar('admin', $action, 'save=true');
				else $smarty->assign("tsError", $response); 
				// Arreglo
				$block['value'] = $_POST['value'];
				$block['type'] = $_POST['type'];
				if($act === 'nuevo') $block['reason'] = $_POST['reason'];
				//
				$smarty->assign("tsBL", $block);
         } else $smarty->assign("tsBL", $tsAdmin->getBlock());
		}

   // CENSURAS
   } elseif($action === 'badwords'){
		$tsTitle = 'Todas las censuras';
		if(!$act) $smarty->assign("tsBadWords",$tsAdmin->getBadWords());
		elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . ' censura';
         if($_POST['edit'] OR $_POST['new']){
            $response = ($act === 'editar') ? $tsAdmin->saveBadWord() : $tsAdmin->newBadWord();
				if($response == 1) $tsCore->redireccionar('admin', $action, 'save=true');
				else $smarty->assign("tsError", $response); 
				$tsBWA = [
					'word' => $_POST['before'], 
					'swop' => $_POST['after'], 
					'method' => $_POST['method'], 
					'type' => $_POST['type']
				];
				if($act === 'nuevo') $tsBWA['reason'] = $_POST['reason'];
				$smarty->assign("tsBW", $tsBWA);
         } else $smarty->assign("tsBW", $tsAdmin->getBadWord());
		}

	// Sesiones
	} elseif($action === 'sesiones'){
		$tsTitle = 'Todos las sesiones';
		if(!$act) $smarty->assign("tsAdminSessions",$tsAdmin->GetSessions());

   // Medallas
   } elseif($action === 'medals') {
		$tsTitle = 'Todas las medallas';
    	// CLASE MEDAL
    	require_once TS_CLASS . "c.medals.php";
    	$tsMedal = new tsMedal();
    	//
      if(empty($act)) $smarty->assign("tsMedals", $tsMedal->adGetMedals());
      elseif(in_array($act, ['nueva', 'editar'])) {
			$tsTitle = ucfirst($act) . ' medalla';
         if($_POST['save'] OR $_POST['edit']) {
				$status = ($act === 'nueva') ? $tsMedal->adNewMedal() : $tsMedal->editMedal();
				//$param = ($act === 'editar') ? "act=editar&mid={$_GET['mid']}&" : "";
				if($status == 1) $tsCore->redireccionar('admin', $action, 'save=true');
				else $smarty->assign("tsError", $status); 
				$smarty->assign("tsMed", [
					'm_title' => $_POST['med_title'], 
					'm_description' => $_POST['med_desc'], 
					'm_image' => $_POST['med_img'], 
					'm_cant' => $_POST['med_cant'], 
					'm_type' => $_POST['med_type'], 
					'm_cond_user' => $_POST['med_cond_user'], 
					'm_cond_user_rango' => $_POST['med_cond_user_rango'], 
					'm_cond_post' => $_POST['med_cond_post'], 
					'm_cond_foto' => $_POST['med_cond_foto']
				]);
         } else {
         	//DATOS DE LA MEDALLA
         	if($act === 'editar') $smarty->assign("tsMed", $tsMedal->adGetMedal());
         }
			//ICONOS PARA LAS MEDALLAS
			$smarty->assign("tsIcons", $tsAdmin->getExtraIcons('medallas', 32));
			//RANGOS DISPONIBLES
			$smarty->assign("tsRangos",$tsAdmin->getAllRangos());
      } elseif($act === 'showassign') {
			$tsTitle = 'Mostrar las asignaciones';
			$smarty->assign("tsAsignaciones", $tsMedal->adGetAssign());
		}

	// Afiliados
	} elseif($action === 'afs'){
      // CLASS
      require_once TS_CLASS . "c.afiliado.php";
      $tsAfiliado = new tsAfiliado;
      // QUE HACER
	   if(empty($act)) $smarty->assign("tsAfiliados", $tsAfiliado->getAfiliados('admin'));
	   elseif($act === 'editar'){
         if($_POST['edit']) {
         	$aid = (int)$_GET['aid'];
            if($tsAfiliado->EditarAfiliado()) $tsCore->redireccionar('admin', $action, "act=editar&aid=$aid&save=true");
         }
         $smarty->assign("tsAf", $tsAfiliado->getAfiliado('admin'));
      }

   // Categorías
	} elseif($action === 'cats'){
		$tsTitle = 'Todas las categor&iacute;as';
		if(!empty($_GET['ordenar'])) $tsAdmin->saveOrden();
		elseif(in_array($act, ['editar', 'nueva'])){
			$tsTitle = ucfirst($act) . ' categor&iacute;a';
			if($_POST['save']){
				$both = ($act === 'editar') ? $tsAdmin->saveCat() : $tsAdmin->newCat();
				if($both) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				$smarty->assign("tsType", $_GET['t']);
				if($act === 'editar') $smarty->assign("tsCat", $tsAdmin->getCat());
				if($act === 'nueva') $smarty->assign("tsCID", $_GET['cid']);
				// SOLO LAS CATEGORIAS TIENEN ICONOS
				$smarty->assign("tsIcons", $tsAdmin->getExtraIcons());
			}
		} elseif($act === 'change'){
			$tsTitle = 'Cambiar categor&iacute;a';
			if($_POST['save']){
				if($tsAdmin->MoveCat()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
		} elseif($act === 'borrar'){
			$tsTitle = 'Borrar categor&iacute;a';
			if($_POST['save']){
				// BORRAR CATEGORIA
				if($_GET['t'] === 'cat'){
					$save = $tsAdmin->delCat();
					if($save == 1) $tsCore->redireccionar('admin', $action, 'save=true');
					else $smarty->assign("tsError",$save); 
				} 
			}
			//
			$smarty->assign("tsType", $_GET['t']);
			$smarty->assign("tsCID", $_GET['cid']);
			$smarty->assign("tsSID", $_GET['sid']);
		}

	// Rangos
	} elseif($action === 'rangos') {
		$tsTitle = 'Todos los Rangos';
		// PORTADA
		if(empty($act)) $smarty->assign("tsRangos",$tsAdmin->getRangos());
		// LISTAR USUARIOS DEPENDIENDO EL RANGO
		elseif($act === 'list') {
			$smarty->assign("tsMembers", $tsAdmin->getRangoUsers());
		// EDITAR RANGO
		} elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . " rango";
			if(!empty($_POST['save'])){
				$both = ($act === 'editar') ? $tsAdmin->saveRango() : $tsAdmin->newRango();
				if($both) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				if($act === 'editar') $smarty->assign("tsRango", $tsAdmin->getRango());
            if($act === 'nuevo') $smarty->assign("tsError", $save); 
            $smarty->assign("tsType", $_GET['t']);
				$smarty->assign("tsIcons", $tsAdmin->getExtraIcons('ran'));
				$smarty->assign('tsColor', $tsAdmin->rangoColor());
			}
		// NUEVO RANGO
		} elseif($act === 'borrar'){
			$tsTitle = ucfirst($act) . " rango";
			if(empty($_POST['save'])) $smarty->assign("tsRangos", $tsAdmin->getAllRangos());
			else {
				if($tsAdmin->delRango()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
		// CAMBIAR RANGO PREDETERMINADO DEL REGISTRO
		} elseif($act === 'setdefault') {
			if($tsAdmin->SetDefaultRango()) $tsCore->redireccionar('admin', $action, 'save=true');
		}

	// Usuarios
	} elseif($action === 'users'){
		$tsTitle = 'Todos los Usuarios';
		if(in_array($act, ['verificar', 'show'])) {
         $user_id = (int)$_GET['uid'];
		}
	   if(empty($act)) {
	   	$smarty->assign("tsMembers",$tsAdmin->getUsuarios());

	   } elseif($act == 'verificar') {
	   	var_dump($act);
         $verificar = $tsAdmin->setUsuarioVerificado();
        	if($verificar) $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
			else $smarty->assign("tsError", $update); 

	   } elseif($act === 'show') {
	      $do = (int)$_GET['t'];
         // HACER
         switch($do){
				case 5:
				case 6:
				case 7:
				case 8:
					if($do === 6) {
						require TS_EXTRA . 'datos.php';
						$smarty->assign("contentUser", $contentUser);
					}
        	      if(!empty($_POST['save'])) {
        	         $both = ($do === 5) ? $tsAdmin->setUserPrivacidad($user_id) : ($do === 7 ? $tsAdmin->setUserRango($user_id) : ($do === 8 ? $tsAdmin->setUserFirma($user_id) : $tsAdmin->deleteContent($user_id)));
        	         if($both === 'OK') $tsCore->redireccionar('admin', $action, "act=show&uid=$user_id&save=true");
                  else $smarty->assign("tsError", $both);
               }
               if($do === 7) {
               	$smarty->assign("tsUserR", $tsAdmin->getUserRango($user_id));
               } elseif($do === 8) {
               	$smarty->assign("tsUserF", $tsAdmin->getUserData());
               } else {
               	include_once TS_EXTRA . "datos.php";
	            	$smarty->assign("tsPerfil", $tsAdmin->getUserPrivacidad());
						$smarty->assign("tsPrivacidad", $tsPrivacidad);
               }
            break;
            default:
               if(!empty($_POST['save'])){
        	       	$update = $tsAdmin->setUserData($user_id);
        	       	if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                  else $smarty->assign("tsError",$update);
               }
    	       	$smarty->assign("tsUserD",$tsAdmin->getUserData());
            break;
         }
         // TIPO
         $smarty->assign("tsType",$_GET['t']);
         $smarty->assign("tsUserID",$user_id);
         $smarty->assign("tsUsername",$tsUser->getUserName($user_id));
	   }
	}

/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	// ACCION?
	$smarty->assign("tsAction",$action);
	//
	$smarty->assign("tsAct",$act);
	//
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	
	$smarty->assign("tsSave",$_GET['save']);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	
	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}