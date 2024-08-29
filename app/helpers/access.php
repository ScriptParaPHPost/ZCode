<?php 
/**
 * Controlador
 *
 * @name    admin.php
 * @author  ZCode | PHPost
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

$tsPage = "access";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

$tsLevel = 0;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

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

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	
	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}