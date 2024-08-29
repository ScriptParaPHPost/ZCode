<?php 
/**
 * Controlador
 *
 * @name    cuenta.php
 * @author  ZCode | PHPost
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

$tsPage = "cuenta";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

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
	$smarty->assign("tsAviso", $tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue){

	$action = $_GET['action'] ?? '';

	//
	include TS_MODELS . "c.cuenta.php";
	$tsCuenta = new tsCuenta();
	
	include TS_ZCODE . 'datos.php';
	if(isset($_GET['accion']) AND $_GET['accion'] === 'apariencia') {
		$smarty->assign('tsColoresValue', $tsColores);
		$smarty->assign('tsColoresTxt', $tsColoresTxt);
		$smarty->assign('tsAvatarSelect', $tsCuenta->getAvatarImages());
		$smarty->assign('tsAvatarSocials', $tsCuenta->getAvatarSocials());
	}

	# Comprobamos que tenga el 2FA activado
	$user_secret_2fa = empty($tsUser->info['user_secret_2fa']) ? false : true;

	$smarty->assign("tsG2FA", $user_secret_2fa);
	

	if(empty($action)){
		include TS_ZCODE . 'RedesDataIcon.php';
		include TS_ZCODE . 'Paises.php';
		include TS_EXTRA . 'geodata.php';

		// SOLO MENORES DE 84 AÑOS xD Y MAYORES DE...
		$now_year = date("Y", time());
		// 100años - 16años = 84años
		$edad = (int)$tsCore->settings['c_allow_edad'];
		$max_year = 100 - $edad;
		$start_year = (int)$now_year - (int)$max_year;
		$end_year = (int)$now_year - (int)$tsCore->settings['c_allow_edad'];
		//
		$smarty->assign("tsMax", (int)$max_year);
		$smarty->assign("tsMaxY", (int)$start_year);
		$smarty->assign("tsEndY", (int)$end_year);
		// PERFIL INFO
   	$tsPerfil = $tsCuenta->loadPerfil();
		$smarty->assign("tsPerfil", $tsPerfil);

		$smarty->assign("tsRedes", $redes);
		
		// PERFIL DATA
		$smarty->assign("tsPData", $tsPerfilData);
   	$smarty->assign("tsPrivacidad", $tsPrivacidad);
		// DATOS
		$smarty->assign("tsPaises", $tsPaises);
		$smarty->assign("tsEstados", $estados[$tsPerfil['user_pais']]);
		$smarty->assign("tsMeses", [
			1 => 'Enero',
			2 => 'Febrero',
			3 => 'Marzo',
			4 => 'Abril',
			5 => 'Mayo',
			6 => 'Junio',
			7 => 'Julio',
			8 => 'Agosto',
			9 => 'Septiembre',
			10 => 'Octubre',
			11 => 'Noviembre',
			12 => 'Diciembre',
		]);
   	// BLOQUEOS
   	$smarty->assign("tsBlocks", $tsCuenta->loadBloqueos());
   	  
	} elseif($action == 'desactivate'){
		if(!empty($_POST['validar'])) echo $tsCuenta->desCuenta();
	}
	$smarty->assign("tsAccion", $_GET["accion"]); 
	$smarty->assign("tsTab", $_GET["tab"]); 
	
}

if(empty($tsAjax)) {
	$smarty->assign("tsTitle",$tsTitle);

	include TS_ROOT . 'footer.php';
}