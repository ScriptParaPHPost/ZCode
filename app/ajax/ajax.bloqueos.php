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

use app\models\Cuenta;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'bloqueos-cambiar' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.bloqueos.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg; 
	die();
}

// CLASE
$tsCuenta = new Cuenta();

//echo $tsUser->getUserName($_GET['user']);

// CODIGO
switch($action){
	case 'bloqueos-cambiar':
		echo $tsCuenta->bloqueosCambiar();
	break;
}
