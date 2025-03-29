<?php 

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

$tsPage = "fotos";

$tsLevel = 2;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

$tsContinue = true;

include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan'];

// PARA LAS FOTOS...
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8') : '';
$tsLevel = ((int)$tsCore->settings['c_fotos_private'] === 0 && ($action === '' || $action === 'ver')) ? 0 : 2;

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) {	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {

	include TS_MODELS . "c.fotos.php";
	$tsFotos = new tsFotos();

	switch($action) {
		case '':
			$smarty->assign("tsLastFotos", $tsFotos->getLastFotos());
			$smarty->assign("tsLastComments", $tsFotos->getLastComments());
			$smarty->assign("tsStats", $tsFotos->getStats());
		break;
		case 'agregar':
			if (!empty($_POST['titulo'])) {
				$result = $tsFotos->newFoto();
				if (is_array($result) || $result <= 0) {
					$smarty->assign("tsAviso", [
						'titulo' => 'Opps...', 
						'mensaje' => $result, 
						'but' => 'Volver', 
						'link' => "{$tsCore->settings['url']}/fotos/agregar.php"
					]);
				} else {
					$titulo = $tsCore->setSecure($tsCore->setSEO($_POST['titulo']));
					header("Location: {$tsCore->settings['url']}/fotos/{$tsUser->nick}/$result/$titulo.html");
					exit;
				}
			}
		break;
		 case 'editar':
			$tsFoto = empty($_POST['titulo']) ? $tsFotos->getFotoEdit() : $tsFotos->editFoto();
			if (!is_array($tsFoto)) {
				$tsPage = 'aviso';
				$smarty->assign("tsAviso", [
					'titulo' => 'Opps...', 
					'mensaje' => $tsFoto, 
					'but' => 'Ir a Fotos', 
					'link' => "{$tsCore->settings['url']}/fotos/"
				]);
			} else {
				$smarty->assign("tsFoto", $tsFoto);
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
			$fotoExiste = (int)$tsFoto['foto']['exist'] === 0;
			if(((int)$tsFoto['foto']['f_status'] === 1 && (!$tsUser->is_admod && $tsUser->permisos['moacp'] == false)) OR $fotoExiste) {
				$message = 'Esta foto ' . ($fotoExiste ? 'no existe' : 'se encuentra en revisi&oacute;n por acumulaci&oacute;n de denuncias');
			
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
				$smarty->assign("tsAviso", [
					'titulo' => 'Opps...', 
					'mensaje' => 
					'Este usuario no existe.', 
					'but' => 'Ir a Fotos', 
					'link' => "{$tsCore->settings['url']}/fotos/"
				]);
			} else {
				$tsFotox = $tsFotos->getFotos($user_id);
				$smarty->assign("tsFotos", $tsFotox);
				$smarty->assign("tsFUser", array($user_id, $username));
			}
		break;
	}
	$smarty->assign("tsAction",$action);
 
}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}