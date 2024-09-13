<?php 

$tsTitle = 'Generador de actualizacion';

require_once TS_MODELS . "c.actualizacion.php";
$tsActualizacion = new tsActualizacion;

$commits = $tsActualizacion->getLastCommit();
$getUpdated = $tsActualizacion->saveIDUpdate('get');
$smarty->assign([
	'tsUserGithub' => $tsActualizacion->getUser(),
	'tsUpdated' => $getUpdated,
	'tsLastCommit' => (isset($_GET['sha']) ? $_GET['sha'] : $commits)
]);

if(is_string($commits) AND !empty($getUpdated)) {
	$smarty->assign('tsLastCommitFiles', $tsActualizacion->getLastCommitFiles()['commit']);
	$files = $tsActualizacion->filesStatus();
	$smarty->assign('tsFilesTotal', safe_count($files));
	$smarty->assign('tsFilesStatus', $files);

	if($act === 'actualizar') {
		if($tsActualizacion->getFilesUpdate()) {
			$tsActualizacion->saveIDUpdate('save', '');
			$unset = ['sha', 'commit', 'files'];
			foreach($unset as $del) unset($_SESSION[$del]);
			$tsCore->redireccionar('admin', $action);
		}
	} elseif($act === 'commits') {
		$smarty->assign('tsLastCommits', $tsActualizacion->getLastCommits());
	}
}