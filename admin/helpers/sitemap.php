<?php 

require_once TS_MODELS . "c.sitemap.php";
$tsSitemap = new tsSitemap();

$tsTitle = 'Administrar sitemap';
if(empty($act)) {
	$smarty->assign('tsURLs', $tsSitemap->getSitemap());

} elseif($act === 'sync') {
	if($_GET['type'] === 'robots') {
		require_once TS_MODELS . "c.seo.php";
		$tsSeo = new tsSeo();
		if($tsSeo->syncRobots()) $tsCore->redireccionar('admin', $action, 'save=true');
	}
	if($_GET['type'] === 'sitemap') {
		if($tsSitemap->syncSitemap()) $tsCore->redireccionar('admin', $action, 'save=true');
	}

} elseif($act === 'nueva') {
	if($tsSitemap->newUrlSitemap()) $tsCore->redireccionar('admin', $action, 'save=true');

} elseif($act === 'editar') {
	$smarty->assign('tsURL', $tsSitemap->SitemapEditID());
	if($tsSitemap->SitemapSaveID()) $tsCore->redireccionar('admin', $action, 'save=true');

} elseif($act === 'config') {
	$smarty->assign('tsSitemap', $tsSitemap->setSettings());
	if($tsSitemap->saveSettings()) $tsCore->redireccionar('admin', $action, 'save=true');
}