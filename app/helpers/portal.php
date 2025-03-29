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

$tsPage = "portal";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;
	
$tsContinue = true;
	
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

   // PORTAL
   include TS_MODELS . "c.portal.php";
   $tsPortal = new tsPortal();

   // AFILIADOS
   include TS_MODELS . "c.afiliado.php";
   $tsAfiliado = new tsAfiliado();
   
   // NOS HAN REFERIDO?
   if(!empty($_GET['ref'])) $tsAfiliado->urlIn();
   

   $smarty->assign("tsMuro", $tsPortal->getNews());
   $smarty->assign("tsInfo", ['uid' => $tsUser->uid]);
   $smarty->assign("tsType", "news");
   //
   $smarty->assign("tsCategories", $tsPortal->composeCategories());
   //$tsPosts = $tsPortal->getMyPosts();
   //$smarty->assign("tsPosts",$tsPosts['data']);
   //$smarty->assign("tsPages",$tsPosts['pages']);
   //
   $smarty->assign("tsLastPostsVisited", $tsPortal->getLastPosts());
   $smarty->assign("tsFavorites", $tsPortal->getFavorites());
   // FOTOS
   $tsImages = $tsPortal->getFotos();
	$smarty->assign("tsImages", $tsImages);
   $smarty->assign("tsImTotal", safe_count($tsImages));
   // STATS
   $smarty->assign("tsStats", $tsPortal->getStats());
   // AFILIADOS
   $smarty->assign("tsAfiliados",$tsAfiliado->getAfiliados());

}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);
   
	include TS_ROOT . "footer.php";
   
}