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

$tsPage = "access";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

$tsContinue = true;
	
include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";
$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan'];

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if(!$tsLevelMsg) {	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso", $tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsUser->is_member) header("Location: ../");

//
if($tsContinue) {

	// ACTION
	$action = htmlspecialchars($_GET['action'] ?? '');

	$tsTitle = ($action === 'login' ? "Iniciar sesión" : "Crear cuenta") . " - {$tsCore->settings['titulo']}";

	if($page === 'registro') {
	   // 100años - 16años = 84años
	   $edad = (int)$tsCore->settings['c_allow_edad'];
	   $max_year = 100 - $edad;
	   $start_year = (int)$now_year - (int)$max_year;
	   $end_year = (int)$now_year - (int)$tsCore->settings['c_allow_edad'];
	   //
	   $smarty->assign("tsMax", (int)$max_year);
	   $smarty->assign("tsMaxY", (int)$start_year);
	   $smarty->assign("tsEndY", (int)$end_year);

	   // Registro abierto
	   $smarty->assign('tsAbierto', (int)$tsCore->settings["c_reg_active"]);
	   $smarty->assign('tsPublicKey', $tsCore->settings["pkey"]);
	}

	$smarty->assign("tsPass", substr(base64_encode(date('sdmYHms')), 0, 10));

	// ACCION
	$smarty->assign("tsAction", $action);
}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle", $tsTitle);
	
	include TS_ROOT . 'footer.php';
}