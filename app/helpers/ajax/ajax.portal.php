<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'portal-posts_config' => ['n' => 2, 'p' => ''],
	'portal-posts_pages' => ['n' => 2, 'p' => 'posts'],
	'portal-favs_pages' => ['n' => 2, 'p' => 'posts'],
	'portal-activity_pages' => ['n' => 2, 'p' => 'actividad'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.portal.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASS
include TS_MODELS . "c.portal.php";
$tsPortal = new tsPortal();

// CODIGO
switch($action){
	case 'portal-posts_config':
		echo $tsPortal->savePostsConfig();
	break;
	case 'portal-posts_pages':
	case 'portal-favs_pages':
		$tsPosts = ($action === 'portal-posts_pages') ? $tsPortal->getMyPosts() : $tsPortal->getFavorites();
		$smarty->assign("tsPosts",$tsPosts['data']);
		$smarty->assign("tsPages",$tsPosts['pages']);
		$smarty->assign("tsType", ($action === 'portal-posts_pages' ? 'posts' : 'favs'));
	break;
	case 'portal-activity_pages':
		$actividad = $tsActividad->getActividadFollows();
		if(!is_array($actividad)) die('<div class="empty">'.$actividad.'</div>');
		$smarty->assign("tsActividad", $actividad);
		$smarty->assign("tsUserID", $user_id);
	break;
	default:
		die('0: Este archivo no existe.');
	break;
}
