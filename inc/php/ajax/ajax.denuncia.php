<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.denuncia.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
$files = [
   'denuncia-post'    => ['n' => 2, 'p' => 'form'],
   'denuncia-foto'    => ['n' => 2, 'p' => 'form'],
   'denuncia-mensaje' => ['n' => 2, 'p' => 'form'],
   'denuncia-usuario' => ['n' => 2, 'p' => 'form'],
];

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.denuncia.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die();
}

// SWAT
include TS_CLASS . "c.swat.php";
$tsSwat = new tsSwat();
// VARS
$obj_id = $tsCore->setSecure($_POST['obj_id']);
$tsData = [];

// Manejo de las diferentes acciones de denuncia
if (in_array($action, ['denuncia-post', 'denuncia-foto', 'denuncia-mensaje', 'denuncia-usuario'])) {
   if ($_POST['razon']) {
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
include TS_EXTRA . "datos.php";
$smarty->assign("tsData", $tsData);
$smarty->assign("tsDenuncias", $tsDenuncias[$type]);
// ACCION
$smarty->assign("tsAction", $action);