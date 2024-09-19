<?php 

require_once TS_MODELS . "c.sitemap.php";
$tsSitemap = new tsSitemap();

$tsTitle = 'Administrar sitemap';
if(empty($act)) {

} elseif($act === 'sync') {
	if($_GET['type'] === 'robots') {
		require_once TS_MODELS . "c.seo.php";
		$tsSeo = new tsSeo();
		if($tsSeo->syncRobots()) $tsCore->redireccionar('admin', $action, 'save=true');
	}
	if($_GET['type'] === 'sitemap') {
		if($tsSitemap->syncSitemap()) $tsCore->redireccionar('admin', $action, 'save=true');
	}
} elseif($act === 'generar') {
	var_dump($tsSitemap->addSitemap());
}