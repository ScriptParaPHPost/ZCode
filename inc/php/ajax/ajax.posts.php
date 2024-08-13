<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.posts.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'posts-genbus' => array('n' => 2, 'p' => 'genbus'),
		'posts-preview' => array('n' => 2, 'p' => 'preview'),
		'posts-borrar' =>  array('n' => 2, 'p' => ''),
		'posts-admin-borrar' =>  array('n' => 2, 'p' => ''),
		'posts-votar' =>  array('n' => 2, 'p' => ''),
		'posts-last-comentarios' =>  array('n' => 0, 'p' => 'last-comentarios'),
		//
		'posts-destacados' => array('n' => 0, 'p' => 'destacados'),
		'posts-recientes' => array('n' => 0, 'p' => 'destacados'),
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.posts.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	// CLASE
	require TS_CLASS . 'c.posts.php';
	$tsPosts = new tsPosts();
	if(in_array($action, ['posts-genbus', 'posts-preview'])) {
		require TS_CLASS . 'c.agregar.php';
		$tsAgregar = new tsAgregar();
	}
	// CODIGO
	switch($action){
		case 'posts-genbus':
			//<--
			$do = $tsCore->setSecure($_GET['do']);
			$q = $tsCore->setSecure($_POST['q']);
			//
			if($do == 'search'){
				$smarty->assign("tsPosts", $tsAgregar->simiPosts($q));   
			} elseif($do == 'generador') {
				$smarty->assign("tsTags", $tsAgregar->genTags($q));
			}
			//
			$smarty->assign("tsDo", $do);
			//-->
		break;
		case 'posts-preview':
			//<--
			$smarty->assign("tsPreview", $tsAgregar->getPreview());
			//-->
		break;
		case 'posts-borrar':
			//<--
			echo $tsPosts->deletePost();
			//-->
		break;
		case 'posts-admin-borrar':
			//<--
			echo $tsPosts->deleteAdminPost();
			//-->
		break;
		case 'posts-votar':
			//<--
			echo $tsPosts->votarPost();
			//-->
		break;
		case 'posts-last-comentarios':
			//<--
			require TS_CLASS . 'c.comentarios.php';
			$tsComentarios = new tsComentarios;
			$smarty->assign("tsComments", $tsComentarios->getLastComentarios());
			//-->
		break;
		case 'posts-destacados':
		case 'posts-recientes':	
			$fijado = ($action === 'posts-destacados') ? true : false;
			$tsLastPosts = $tsPosts->getLastPosts('', $fijado);
			$smarty->assign("tsPosts", $tsLastPosts['data']);
			$smarty->assign("tsPages", $tsLastPosts['pages']);
		break;
	}