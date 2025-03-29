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
	'mensajes-validar' => ['n' => 2, 'p' => ''],
   'mensajes-enviar' => ['n' => 2, 'p' => ''],
   'mensajes-respuesta' => ['n' => 2, 'p' => 'resp'],
   'mensajes-lista' => ['n' => 2, 'p' => 'lista'],
   'mensajes-editar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.mensajes.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CODIGO
switch($action){
	case 'mensajes-validar':
       echo $tsMP->getValid();
	break;
    case 'mensajes-enviar':
		echo $tsMP->newMensaje();
	break;
   case 'mensajes-respuesta':
		$smarty->assign("mp",$tsMP->newRespuesta());
	break;
   case 'mensajes-lista':
		$smarty->assign("tsMensajes", $tsMP->getMensajes(1, false, 'monitor'));
	break;
   case 'mensajes-editar':
		echo $tsMP->editMensajes();
	break;
}

$_GET['ts'] = true;