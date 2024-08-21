<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.upload.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'upload-avatar' => array('n' => 2, 'p' => ''),
		'upload-crop' => array('n' => 2, 'p' => ''),
		'upload-images' => array('n' => 2, 'p' => ''),
		'upload-portada' => array('n' => 2, 'p' => ''),
		'upload-imagen' => array('n' => 2, 'p' => '')
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.upload.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	// CLASE
	require TS_CLASS . 'c.upload.php';
	$tsUpload = new tsUpload();
	
	// CODIGO
	switch($action) {
		case 'upload-avatar':
			// <--
			$tsUpload->image_scale = true;
			$tsUpload->image_size['w'] = 640;
			$tsUpload->image_size['h'] = 480;
			//
			$tsUpload->file_url = $_POST['url'];
			//
			echo json_encode($tsUpload->newUpload(3));
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