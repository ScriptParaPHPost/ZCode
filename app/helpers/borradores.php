<?php 

/**
 * Controlador
 *
 * @name    borradores.php
 * @author  ZCode | PHPost
*/

$tsPage = "borradores";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?

$tsContinue = true;	// CONTINUAR EL SCRIPT

include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL
	
// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1){	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

require_once TS_MODELS . "c.borradores.php";
$tsBorradores = new tsDrafts;

// Una nueva forma que aprendi hace poco
$action = filter_input(INPUT_GET, 'action', FILTER_UNSAFE_RAW) ?? '';
$bid     = filter_input(INPUT_GET, 'borrador_id', FILTER_VALIDATE_INT) ?? 0;
$id     = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$order  = filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW) ?? '';

if($tsContinue) {
	
	$smarty->assign('categorias', $tsBorradores->contarPorCategoria());
	$smarty->assign('estados', $tsBorradores->contarPorEstado());

	if($bid > 0) {
		$smarty->assign('borrador', $tsBorradores->obtenerBorrador(0, $bid));
	} else {
		$smarty->assign('borradores', $tsBorradores->obtenerBorradores($action, $order));
	}

	$smarty->assign('tsAction', $action);
	$smarty->assign('tsOrder', $order);
	$smarty->assign('tsID', $id);
	$smarty->assign('tsBID', $bid);

}

if(empty($tsAjax)) {	

	$smarty->assign("tsTitle",$tsTitle);	

	include TS_ROOT . 'footer.php';

}