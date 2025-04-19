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

use app\models\{Agregar,Comentarios,Posts};

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');


// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'posts-genbus' => ['n' => 2, 'p' => 'genbus'],
	'posts-preview' => ['n' => 2, 'p' => 'preview'],
	'posts-borrar' =>  ['n' => 2, 'p' => ''],
	'posts-admin-borrar' =>  ['n' => 2, 'p' => ''],
	'posts-votar' =>  ['n' => 2, 'p' => ''],
	'posts-last-comentarios' =>  ['n' => 0, 'p' => 'last-comentarios'],
	'posts-destacados' => ['n' => 0, 'p' => 'destacados'],
	'posts-recientes' => ['n' => 0, 'p' => 'destacados'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.posts.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASE
$tsPosts = new Posts();

if(in_array($action, ['posts-genbus', 'posts-preview'])) {
	$tsAgregar = new Agregar();
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
		$tsComentarios = new Comentarios;
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