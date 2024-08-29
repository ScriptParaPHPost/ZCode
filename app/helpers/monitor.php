<?php 
/**
 * Controlador
 *
 * @name    monitor.php
 * @author  ZCode | PHPost
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

$tsPage = "monitor";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?

$tsContinue = true;	// CONTINUAR EL SCRIPT
	
// INCLUIR EL HEADER
include_once realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  

// TITULO DE LA PAGINA ACTUAL
$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	


// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1){	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso", $tsLevelMsg);
	//
	$tsContinue = false;
}
//
if($tsContinue) {

	$action = htmlspecialchars($_GET['action'] ?? '');

	if(empty($action)){
      $tsMonitor->show_type = 2;
		$tsData = $tsMonitor->getNotificaciones();
		$smarty->assign("tsData", $tsData);
      // LIVE SOUND
      $smarty->assign("tsStatus", $_COOKIE);
   } else {
   	$tsData = $tsMonitor->getFollows($action);
   }
   $smarty->assign("tsData", $tsData);
	
	$smarty->assign("tsAction",$action);
}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);

	include_once TS_ROOT . "footer.php";
}