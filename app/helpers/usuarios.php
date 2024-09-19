<?php 
/**
 * Controlador
 *
 * @name    usuarios.php
 * @author  ZCode | PHPost
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "usuarios";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

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
//
if($tsContinue){

   // PAICES
   include TS_ZCODE . "Paises.php";
   $smarty->assign("tsPaises", $tsPaises);
   $smarty->assign("tsPaisesSVG", $SVG_FLAGS_ALL);
   // USUARIOS
   $tsUsers = $tsUser->getUsuarios();
   $smarty->assign("tsUsers", $tsUsers['data']);
   $smarty->assign("tsPages", $tsUsers['pages']);
   $smarty->assign("tsTotal", $tsUsers['total']);
   // FILTROS
   $smarty->assign("tsFiltro", [
   	'online' => $_GET['online'], 
   	'avatar' => $_GET['avatar'], 
   	'sex' => $_GET['sexo'], 
   	'pais' => $_GET['pais'], 
   	'rango' => $_GET['rango']
   ]);
   // RANGOS
	$query = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT rango_id, r_name, r_image FROM @rangos ORDER BY rango_id'));
	foreach($query as $rid => $rango) {
		$query[$rid]['r_image'] = $tsCore->settings['assets'] . "/images/rangos/{$rango['r_image']}";
	}
   $smarty->assign("tsRangos", $query);
    
}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}