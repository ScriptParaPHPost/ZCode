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
   'denuncia-post'    => ['n' => 2, 'p' => 'form'],
   'denuncia-foto'    => ['n' => 2, 'p' => 'form'],
   'denuncia-mensaje' => ['n' => 2, 'p' => 'form'],
   'denuncia-usuario' => ['n' => 2, 'p' => 'form'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.denuncia.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die();
}

// SWAT
include TS_MODELS . "c.swat.php";
$tsSwat = new tsSwat();

// VARS
$obj_id = $tsCore->setSecure($_POST['obj_id']);
$tsData = [];

// Manejo de las diferentes acciones de denuncia
if (in_array($action, ['denuncia-post', 'denuncia-foto', 'denuncia-mensaje', 'denuncia-usuario'])) {
   if (isset($_POST['razon'])) {
      $tsAjax = 1;
      $tipo = str_replace('denuncia-', '', $action);
      echo $tsSwat->setDenuncia($obj_id, $tipo === 'post' ? 'posts' : $tipo);
   } else {
     	if ($action === 'denuncia-usuario') {
         $tsData['obj_user'] = $tsCore->setSecure($_POST['obj_user']);
         $type = 'users';
      } else {
         $tsData = [
            'obj_id'    => $obj_id,
            'obj_title' => $tsCore->setSecure($_POST['obj_title']),
            'obj_user'  => $tsCore->setSecure($_POST['obj_user'])
         ];
         $type = $action === 'denuncia-post' ? 'posts' : 'fotos';
      }
   }
}

// DATOS
include TS_ZCODE . "Denuncias.php";
$smarty->assign("tsData", $tsData);
$smarty->assign("tsDenuncias", $tsDenuncias[$type]);
// ACCION
$smarty->assign("tsAction", $action);