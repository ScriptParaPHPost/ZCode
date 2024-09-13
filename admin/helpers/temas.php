<?php 

$tsTitle = 'Diseños / Temas';
// VER TEMAS
if(empty($act)) {
	# Instalamos themes automaticamente
	include TS_ZCODE . 'ThemeInstall.php';
	$installer->installThemes();
	$installer->verifyThemesInstalled();
	# Mostramos
	$smarty->assign("tsTemas", $tsAdmin->getTemas());
}
// Editar o Nuevo tema
elseif(in_array($act, ['editar', 'nuevo'])) {
	$tsTitle = ucfirst($act) . ' tema';
	if(!empty($_POST['save']) OR !empty($_POST['path'])) {
		$ActTheme = ($act === 'editar') ? $tsAdmin->saveTema() : $tsAdmin->newTema();
		if($ActTheme) $tsCore->redireccionar('admin', $action, 'save=true');
	} else {
		if($act === 'editar') $smarty->assign("tsTema", $tsAdmin->getTema());
		if($act === 'nuevo') $smarty->assign("tsError", $tsAdmin->newTema());
	} 
} elseif($act === 'usar') {
	$tsTitle = 'Activar tema';
	if($tsAdmin->changeTema()) $tsCore->redireccionar('admin', $action, 'save=true');
} elseif($act === 'borrar') {
	$tsTitle = 'Borrar tema';
	if(!empty($_POST['confirm'])) {
		if($tsAdmin->deleteTema()) $tsCore->redireccionar('admin', $action, 'save=true');
	}
	$smarty->assign("tt", $_GET['tt']);
}