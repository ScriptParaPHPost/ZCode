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

$tsPage = "pages";

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
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {
	
	$action = $_GET['action'];
	
	match($action) {
		'ayuda', 'chat', 'contacto', 'protocolo', 'terminos-y-condiciones', 'privacidad', 'dmca' => '',
		default => $tsCore->redirectTo($tsCore->settings['url']),
	};
   //
   $smarty->assign("tsAction",$action);

}

if(empty($tsAjax)) {	

	$smarty->assign("tsTitle",$tsTitle);
	
	include TS_ROOT . 'footer.php';
	
}