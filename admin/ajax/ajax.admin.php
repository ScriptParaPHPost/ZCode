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
	'admin-medalla-borrar' => ['n' => 4, 'p' => ''],
	'admin-medalla-asignar' => ['n' => 4, 'p' => ''],
	'admin-foto-borrar' => ['n' => 4, 'p' => ''],
	'admin-foto-setOpenClosed' => ['n' => 4, 'p' => ''],
	'admin-foto-setShowHide' => ['n' => 4, 'p' => ''],
	'admin-medallas-borrar-asignacion' => ['n' => 4, 'p' => ''],
	'admin-users-setInActivo' => ['n' => 4, 'p' => ''],
	'admin-users-sessions' => ['n' => 4, 'p' => ''],
	'admin-noticias-setInActive' => ['n' => 4, 'p' => ''],
	'admin-sesiones-borrar' => ['n' => 4, 'p' => ''],
	'admin-nicks-change' => ['n' => 4, 'p' => ''],
   'admin-blacklist-delete' => ['n' => 4, 'p' => ''],
   'admin-badwords-delete' => ['n' => 4, 'p' => ''],
	'admin-ordenar-categorias' => ['n' => 4, 'p' => ''],
	'admin-eliminar-noticia' => ['n' => 4, 'p' => ''],
	'admin-system-update' => ['n' => 4, 'p' => ''],
	'admin-upload-favicon' => ['n' => 4, 'p' => ''],
	'admin-eliminar-categoria' => ['n' => 4, 'p' => ''],
	'admin-tema' => ['n' => 4, 'p' => ''],
	'admin-eliminar-icono' => ['n' => 4, 'p' => ''],
	'admin-subir-icono' => ['n' => 4, 'p' => ''] 
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.admin.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}
   
// CLASES
include TS_MODELS . "c.medals.php";
$tsMedal = new tsMedal();

include TS_MODELS . "c.admin.php";
$tsAdmin = new tsAdmin();

// CODIGO
switch($action) {
	case 'admin-medalla-borrar':
		echo $tsMedal->DelMedalla();
	break;
	case 'admin-medalla-asignar':
		echo $tsMedal->AsignarMedalla();
	break;
	case 'admin-medallas-borrar-asignacion':
		echo $tsMedal->delAssign();
	break;
	case 'admin-foto-borrar':
		echo $tsAdmin->DelFoto();
	break;
	case 'admin-foto-setOpenClosed':
		echo $tsAdmin->setOpenClosedFoto();
	break;
	case 'admin-foto-setShowHide':
		echo $tsAdmin->setShowHideFoto();
	break;
	case 'admin-users-InActivo':
		echo $tsAdmin->setUserInActivo();
	break;
	case 'admin-users-sessions':
		echo $tsAdmin->delSession();
	break;
	case 'admin-noticias-setInActive':
		echo $tsAdmin->setNoticiaInActive();
	break;
	case 'admin-sesiones-borrar':
		echo $tsAdmin->delSession();
	break;
	case 'admin-nicks-change':
		echo $tsAdmin->ChangeNick_o_no();
	break;
     case 'admin-blacklist-delete':
		echo $tsAdmin->deleteBlock();
	break;
     case 'admin-badwords-delete':
		echo $tsAdmin->deleteBadWord();
	break;
	case 'admin-ordenar-categorias':
		echo $tsAdmin->saveOrden();
	break;
	case 'admin-eliminar-noticia':
		echo $tsAdmin->delNoticia();
	break;
	case 'admin-tema':
		echo $tsAdmin->changeTemaNow();
	break;
	case 'admin-system-update':
		require_once TS_MODELS . "c.actualizacion.php";
		$tsActualizacion = new tsActualizacion;
		echo $tsActualizacion->updateTable(false);
	break;
	case 'admin-upload-favicon':
		require_once TS_MODELS . "c.favicon.php";
 		$tsFavicon = new tsFavicon;
		echo $tsFavicon->uploadFavicon();
	break;
	case 'admin-eliminar-categoria':
		include TS_MODELS . "c.foro.php";
		$tsForo = new tsForo();
		echo $tsForo->delCategoria();
	break;
	case 'admin-eliminar-icono':
      echo $tsAdmin->eliminar_icono_paquete();
   break;
   case 'admin-subir-icono':
      echo $tsAdmin->subir_icono();
   break; 
   default:
      die('0: Este archivo no existe.');
   break;
}