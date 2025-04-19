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

use app\models\Afiliado;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'afiliado-nuevo-form' => ['n' => 0, 'p' => 'nuevo-form'],
	'afiliado-enviando' => ['n' => 0, 'p' => ''],
	'afiliado-borrar' => ['n' => 0, 'p' => ''],
	'afiliado-setaction' => ['n' => 0, 'p' => ''],
   'afiliado-url' => ['n' => 0, 'p' => ''],
   'afiliado-detalles' => ['n' => 0, 'p' => 'detalles'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.afiliado.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASS
$tsAfiliado = new Afiliado;

// CODIGO
switch($action){
	case 'afiliado-nuevo-form':
	break;
	case 'afiliado-enviando':
      echo $tsAfiliado->newAfiliado();
	break;
	case 'afiliado-borrar':
      echo $tsAfiliado->DeleteAfiliado($aid);
	break;
	case 'afiliado-setactive':
      echo $tsAfiliado->SetActionAfiliado();
	break;
	case 'afiliado-url':
      $tsAfiliado->urlOut();
	break;
	case 'afiliado-detalles':
      $smarty->assign("tsAf",$tsAfiliado->getAfiliado());
	break;
   default:
      die('0: Este archivo no existe.');
   break;
}