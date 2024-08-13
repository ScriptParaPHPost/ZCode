<?php 
/**
 * Controlador
 *
 * @name    buscador.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "buscador";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

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
/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	$query = htmlspecialchars($_GET['query'] ?? '');
   $engine = htmlspecialchars($_GET['engine'] ?? '');
   $author = htmlspecialchars($_GET['autor'] ?? '');
   $category = (int)$_GET['category'] ?? '-1';
   
	//
	include_once TS_CLASS . "c.buscador.php";
	$tsBuscador = new tsBuscador();

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/

	if($engine !== 'google') {
	   $smarty->assign("tsResults", $tsBuscador->getQuery());
	}
	//
    $smarty->assign("tsQuery", $query);
    $smarty->assign("tsEngine", $engine);
    $smarty->assign("tsCategory", $category);
    $smarty->assign("tsAutor", $author);
/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}