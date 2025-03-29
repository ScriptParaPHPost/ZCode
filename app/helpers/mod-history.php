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

$tsPage = "mod-history";

$tsLevel = 2;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

$tsContinue = true;

include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan'];

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
	
	include TS_MODELS . "c.moderacion.php";
	$tsMod = new tsMod();

	// ACTION
	$action = htmlspecialchars($_GET['ver'] ?? '');

   // HISTORIAL
	$history = ($action === 'fotos') ? 'fotos' : 1;
	$smarty->assign("tsHistory",$tsMod->getHistory($history));
	
	// ACCION?
	$smarty->assign("tsAction",$action);
}

if(empty($tsAjax)) {	
	
	$smarty->assign("tsTitle",$tsTitle);
	
	include TS_ROOT . 'footer.php';
	
}