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

use app\models\Upload;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'upload-avatar' => ['n' => 2, 'p' => ''],
	'upload-crop' => ['n' => 2, 'p' => ''],
	'upload-images' => ['n' => 2, 'p' => ''],
	'upload-portada' => ['n' => 2, 'p' => ''],
	'upload-imagen' => ['n' => 2, 'p' => '']
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.upload.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);

if(!$tsLevelMsg) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}

// CLASE
$tsUpload = new Upload();

// CODIGO
switch($action) {
	case 'upload-avatar':
		//
		$type = isset($_POST['url']) ? 3 : 1;
		$tsUpload->file_url = $_POST['url'] ?? $_FILES['url'];
		//
		echo json_encode($tsUpload->newUpload($type));
		 // -->
	break;
	case 'upload-crop':
		// <--
		echo json_encode($tsUpload->cropAvatar($tsUser->uid));
		// PARA EL PERFIL
		$total = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT p_total FROM @perfil WHERE user_id = \''.$tsUser->uid.'\' LIMIT 1'));
		$total = unserialize($total['p_total']);
		$total[5] = 1;
		$total = serialize($total);
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET p_avatar = 1, p_total = '$total' WHERE user_id = {$tsUser->uid}");
			// -->
	break;
	case 'upload-images':
	case 'upload-imagen':
		echo json_encode($tsUpload->newUpload(1));
	break;
	case 'upload-portada':
		$portada = $tsCore->setSecure($_POST['portada']);
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_portada = '$portada' WHERE user_id = {$tsUser->uid}")) return true;
	break;
}