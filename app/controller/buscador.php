<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/
   
use app\models\Buscador;

$tsPage = "buscador";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;
	
$tsContinue = true;
	
include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if(!$tsLevelMsg) {	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

//
if($tsContinue) {

	$query = htmlspecialchars($_GET['query'] ?? '');
   $engine = htmlspecialchars($_GET['engine'] ?? '');
   $author = htmlspecialchars($_GET['autor'] ?? '');
   $category = (int)$_GET['category'] ?? '-1';
	$tsBuscador = new Buscador();
	
	if($engine !== 'google') $smarty->assign("tsResults", $tsBuscador->getQuery());
	//
  	$smarty->assign("tsQuery", $query);
  	$smarty->assign("tsEngine", $engine);
  	$smarty->assign("tsCategory", $category);
  	$smarty->assign("tsAutor", $author);
	
}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);
	
	include BASEPATH . 'footer.php';
	
}