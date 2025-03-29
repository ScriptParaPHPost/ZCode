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
	'favoritos' => ['n' => 2, 'p' => 'home'],
	'favoritos-agregar' => ['n' => 2, 'p' => ''],
	'favoritos-borrar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.favoritos.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASE
require TS_MODELS . 'c.favoritos.php';
$tsFavoritos = new tsFavoritos();

// CODIGO
switch($action){
	case 'favoritos':
		$smarty->assign("tsFavoritos", $tsFavoritos->getPostFavoritos());
	break;
	case 'favoritos-agregar':
		echo $tsFavoritos->savePostFavorito();
	break;
	case 'favoritos-borrar':
		echo $tsFavoritos->delPostFavorito();
	break;
}
