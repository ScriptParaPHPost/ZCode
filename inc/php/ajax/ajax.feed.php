<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.feed.php
 * @author  Miguel92 & PHPost.es
*/

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.live.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'] ?? 0;
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	//
	$code = [
      'title' => $tsCore->settings['titulo'],
      'url' => $tsCore->settings['url'],
      'version' => $tsCore->settings['version'],
      'admin' => $tsUser->nick,
      'id' => $tsUser->uid
   ];
	$key = base64_encode(serialize($code));
	$key .= '&verification=' . $tsCore->verification();
	#$conexion = "https://phpost.es/feed/";
	$conexion = "https://zcode.newluckies.com/feed/";
	// CODIGO
	switch($action){
		case 'feed-support':
			//<--- CONSULTAR ACTUALIZACIONES OFICIALES Y VERIFICAR VERSIÓN ACTUAL DE ESTE SCRIPT
				$json = $tsCore->getUrlContent($conexion . 'index.php?from=ZCode&type=support&key=' . $key);
				echo $json;
			//--->
		break;
		case 'feed-version':
			/**
			 * Versión a 11 de julio de 2024 *
			 * ZCode 1.0.0 *
			*/
			$time = time();
			$version_now = 'ZCode 1.0.0';
			$version_code = str_replace([' ', '.'], '_', strtolower($version_now));
			# ACTUALIZAR VERSIÓN
			if($tsCore->settings['version'] != $version_now){
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET version = '$version_now', version_code = '$version_code' WHERE tscript_id = 1 LIMIT 1");
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET stats_time_upgrade = $time WHERE stats_no = 1 LIMIT 1");
			}
			//<---
			$json = $tsCore->getUrlContent($conexion . 'index.php?from=ZCode&type=version&key=' . $key);
			echo $json;
			//--->
		break;
		case 'feed-system':
		case 'feed-update':
			//<---
			$URL = $conexion . 'index.php?from=ZCode&type=update&key=' . $key;
			$URL .= '&update_id=' . (int)$tsCore->settings['update_id'];
			if($action === 'feed-update') {
				$URL .= '&start=true';
			}
			$json = $tsCore->getUrlContent($URL);
			if($action === 'feed-update') {
				$update = json_decode($json);
				$basename = TS_CACHE . pathinfo($update->file, PATHINFO_BASENAME);
				$filename = pathinfo($update->file, PATHINFO_FILENAME);
				if(copy($update->file, $basename)) {
					$destino = TS_ROOT;
					$zip = new ZipArchive;
					if ($zip->open($basename) === TRUE) {
					   // Extraer el contenido a la carpeta de destino
					   $zip->extractTo($destino);
					   // Cerrar el archivo ZIP
					   $zip->close();
					   if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET update_id = '$filename' WHERE tscript_id = 1 LIMIT 1")) {
					   	echo '1: Actualizaci&oacute;n completa.';
					   	unlink($basename);
					   }
					} else {
					   echo '0: No se pudo abrir el archivo ZIP.';
					}
				}
				die;
			}
			echo $json;
		break;
		default:
			die('0: Este archivo no existe.');
		break;
	}