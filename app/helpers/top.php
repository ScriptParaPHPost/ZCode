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

$tsPage = "tops";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;
	
$tsContinue = true;
	
include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan'];

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1){	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// CLASE TOPS
	include TS_MODELS . "c.tops.php";
	$tsTops = new tsTops();
	//
	$action = empty($_GET['action']) ? 'posts' : (string)$_GET['action'];
	$fecha = empty($_GET['fecha']) || $_GET['fecha'] > 5 || !ctype_digit($_GET['fecha']) ? 5 : (int)$_GET['fecha'];
	$categoria = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
	//
	$smarty->assign("tsFecha", $fecha);
	$smarty->assign("tsCat", $categoria);
	$smarty->assign("tsAction",$action);
	

	switch ($action) {
		case 'posts':
			$smarty->assign("tsTops", $tsTops->getTopPosts($fecha, $categoria));
		break;
		case 'usuarios':
			$smarty->assign("tsTops", $tsTops->getTopUsers($fecha, $categoria));
		break;
		
		default:
			echo 'Acción no válida'.
		break;
	}
}

if(empty($tsAjax)) {
	
	$smarty->assign("tsTitle",$tsTitle);
	
	include TS_ROOT . 'footer.php';
	
}