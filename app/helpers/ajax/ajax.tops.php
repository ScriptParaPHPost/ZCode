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

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
$files = [
	'tops-posts' => ['n' => 0, 'p' => 'posts'],
	'tops-usuarios' => ['n' => 0, 'p' => 'usuarios'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.tops.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASE
require TS_MODELS . "c.tops.php";
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