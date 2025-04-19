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
 * Solo administración
**/

use app\models\Actualizacion;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

$files = [
   'github-api' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.github.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

$tsActualizacion = new Actualizacion;

// CODIGO
switch($action){
	case 'github-api':

		$tsActualizacion->BRANCH = isset($_POST['branch']) ? $tsCore->setSecure($_POST['branch']) : 'main';

		$last = $tsActualizacion->getLastCommit();
		$response = $tsActualizacion->api_response('info');
	
		echo json_encode($response->commit);

	break;
}