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

use app\models\Favoritos;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

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
$tsFavoritos = new Favoritos();

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
