<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.moderacion.php
 * @author  ZCode | PHPost
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'moderacion-posts' => array('n' => 3, 'p' => 'main'),
		'moderacion-fotos' => array('n' => 3, 'p' => 'main'),
		'moderacion-users' => array('n' => 3, 'p' => 'main'),
		'moderacion-mps' => array('n' => 3, 'p' => 'main'),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	 $tsPage = 'php_files/p.moderacion.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { 
		echo '0: '.$tsLevelMsg['mensaje']; 
		die();
	}
	// CLASE
	require TS_MODELS . 'c.moderacion.php';
	$tsMod = new tsMod();
	//
	$do = htmlspecialchars($_GET['do'] ?? '');
	// CODIGO
	switch($action){
		case 'moderacion-posts':
			//<--
			// POST ID
			$pid = (int)$_POST['postid'];
			// ACCIONES SECUNDARIAS
			switch($do){
				case 'view':
					$tsPage = 'php_files/p.posts.preview';
					$preview = $tsMod->getPreview($pid);
					$smarty->assign("tsPreview", $preview);
				break;
				case 'ocultar':
				case 'reboot':
				case 'sticky':
				case 'openclosed':
					$tsAjax = 1;
					echo $tsMod->multiAction($do);
				break;
				case 'borrar':
					if($_POST['razon']) {
						$tsAjax = 1;
						echo $tsMod->deletePost($pid);
					} else {
						include TS_ZCODE . "datos.php";
						$tsPage = 'php_files/p.posts.mod';
						$smarty->assign("tsDenuncias", $tsDenuncias['posts']);   
					}
				break;
			}
			//-->
		break;
		case 'moderacion-users':
			//<--
			// POST ID
			$user_id = (int)$_POST['id'];
			$username = $tsUser->getUserName($user_id);
			// ACCIONES SECUNDARIAS
			switch($do){
				case 'aviso':
					if(isset($_POST['av_body'])) {
						$avBody = $tsCore->setSecure($_POST['av_body']);
						$tsAjax = 1;
						$aviso = "$avBody\n\nStaff: <span class=\"fw-semibold\">{$tsUser->nick}</span>";
						$aviso_resp = $tsMonitor->setAviso($user_id, $_POST['av_subject'], $aviso, $_POST['av_type']);
						echo (!$aviso_resp ? '0: Error al enviar el aviso' : '1: El aviso fue enviado con &eacute;xito') . " a <strong>$username</strong>.";
					} else $smarty->assign("tsUsername", $username);
				break;
				case 'ban':
					if($_POST['b_causa']){
						$tsAjax = 1;
						echo $tsMod->banUser($user_id);
					}  else $smarty->assign("tsUsername", $username);
				break;
				case 'unban':
				case 'reboot':
				case 'editar':
					$tsAjax = 1;
					echo ($do === 'editar') ? $tsMod->EditarUser($_POST['user_id']) : $tsMod->rebootUser($user_id, $do);
				break;
				case 'info':
					$tsAjax = 1;
					echo $smarty->assign("tsIUser", $tsMod->InfoUser($_POST['user_id']));
				break;
			}
			$smarty->assign("tsDo", $do);
			//-->
		break;
		case 'moderacion-mps':
			//<--
			// MP ID
			$mid = $_POST['mpid'];
			// ACCIONES SECUNDARIAS
			switch($do){
				case 'reboot':
					$tsAjax = 1;
					echo $tsMod->rebootMps($_POST['id']);
				break;
				case 'borrar':
					 $tsAjax = 1;
					 echo $tsMod->deleteMps($mid);
				break;
			}
			//-->
		break;
		case 'moderacion-fotos':
			//<--
			$fid = (int)$_POST['fid'];
			// ACCIONES SECUNDARIAS
			switch($do){
				case 'reboot':
					$tsAjax = 1;
					echo $tsMod->rebootFoto($_POST['id']);
				break;
				case 'borrar':
					if($_POST['razon']) {
						$tsAjax = 1;
						echo $tsMod->deleteFoto($fid);
					} else {
						include('../ext/datos.php');
						$tsPage = 'php_files/p.fotos.mod';
						$smarty->assign("tsDenuncias",$tsDenuncias['fotos']);   
					}
				break;
			}
			//-->
		break;

	}