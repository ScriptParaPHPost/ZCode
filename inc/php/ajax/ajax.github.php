<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.github.php
 * @author  Miguel92
*/


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

// CODIGO
switch($action){
	case 'github-api':
		$token = (file_exists(TS_ROOT . '.env')) ? getenv('USER_GITHUB_TOKEN') : '';

		$branch = isset($_POST['branch']) ? $tsCore->setSecure($_POST['branch']) : 'main';

		$url = "https://api.github.com/repos/ScriptParaPHPost/PHPost/commits/$branch";

		$ch = curl_init($url);

		if(file_exists(TS_ROOT . '.env')) $header[] = 'Authorization: token ' . $token;
		$header[] = 'User-Agent: PHPost App';

		// Configura la cabecera de autenticación con el token
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Establece algunas opciones adicionales de cURL si es necesario
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Ejecuta la solicitud y obtiene la respuesta
		$response = curl_exec($ch);

		// Verifica si hubo un error en la solicitud o La respuesta de la API se encuentra en $response
		echo (curl_errno($ch)) ? curl_error($ch) : $response;

		// Cierra la sesión cURL
		curl_close($ch);

	break;
}