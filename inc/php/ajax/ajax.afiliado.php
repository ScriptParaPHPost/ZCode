<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.afiliado.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'afiliado-nuevo-form' => array('n' => 0, 'p' => 'nuevo-form'),
		'afiliado-enviando' => array('n' => 0, 'p' => ''),
		'afiliado-borrar' => array('n' => 0, 'p' => ''),
		'afiliado-setaction' => array('n' => 0, 'p' => ''),
      'afiliado-url' => array('n' => 0, 'p' => ''),
      'afiliado-detalles' => array('n' => 0, 'p' => 'detalles'),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.afiliado.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
   // CLASS
   require_once TS_CLASS . "c.afiliado.php";
   $tsAfiliado = new tsAfiliado;
	// CODIGO
	switch($action){
		case 'afiliado-nuevo-form':
		break;
		case 'afiliado-enviando':
			//<---
         echo $tsAfiliado->newAfiliado();
			//--->
		break;
		case 'afiliado-borrar':
			//<---
			$aid = (int)$_POST['afid'];
         echo $tsAfiliado->DeleteAfiliado($aid);
			//--->
		break;
		case 'afiliado-setactive':
			//<---
         echo $tsAfiliado->SetActionAfiliado();
			//--->
		break;
		case 'afiliado-url':
			//<---
         $tsAfiliado->urlOut();
			//--->
		break;
		case 'afiliado-detalles':
			//<---
         $smarty->assign("tsAf",$tsAfiliado->getAfiliado());
			//--->
		break;
      default:
         die('0: Este archivo no existe.');
      break;
	}