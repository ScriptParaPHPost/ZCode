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

$files = [
   'fotos-votar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.fotos.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CLASE
require_once TS_MODELS . "c.fotos.php";
$tsFotos = new tsFotos();

// CODIGO
switch($action){
	case 'fotos-votar':
		echo $tsFotos->votarFoto();
	break;
}