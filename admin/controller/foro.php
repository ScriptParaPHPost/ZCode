<?php 

require_once TS_MODELS . "c.foro.php";
$tsForo = new tsForo;

if(empty($act)) {
	$tsTitle = 'Gestionar Foro';
	$smarty->assign('tsForos', $tsForo->getForos());

# Editar | crear nueva categoría
} elseif(in_array($act, ['editar', 'nueva'])) {
	// SOLO LAS CATEGORIAS TIENEN ICONOS
	$smarty->assign("tsIcons", $tsAdmin->getExtraIcons());
	$tsTitle = ucfirst($act) . ' categoría';
	if(isset($_POST['super_nombre'])) {
		$status = ($act === 'nueva') ? $tsForo->newCategoria() : $tsForo->saveCategoria();
		if($status == 1) $tsCore->redireccionar('admin', $action, 'save=true');
	} else {
		$smarty->assign('tsForo', $tsForo->getForo());
	}

}