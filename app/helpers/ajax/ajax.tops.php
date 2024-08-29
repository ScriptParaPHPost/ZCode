<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.tops.php
 * @author  Miguel92
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'tops-posts' => array('n' => 0, 'p' => 'posts'),
		'tops-usuarios' => array('n' => 0, 'p' => 'usuarios'),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.tops.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	// CLASE
	require('../class/c.tops.php');
	$tsTops = new tsTops();
	// CODIGO
	switch($action) {
		case 'tops-posts':
			$posts = $tsTops->getHomeTopPosts();
			$smarty->assign('tsTopPosts', $posts[$_POST['period']]);
		break;
		case 'tops-usuarios':	
			$usuarios = $tsTops->getHomeTopUsers();
			$smarty->assign('tsTopUsers', $usuarios[$_POST['period']]);
		break;
	}