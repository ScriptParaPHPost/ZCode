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

$tsPage = "usuarios";

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

   // PAICES
   include TS_JUNK . "Paises.php";
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
		$query[$rid]['r_image'] = $tsCore->setRoutes('assets', 'images') . "/rangos/{$rango['r_image']}";
	}
   $smarty->assign("tsRangos", $query);
    
}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);
	
	include BASEPATH . 'footer.php';
	
}