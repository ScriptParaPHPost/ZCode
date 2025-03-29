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

$files = [
   'github-api' => ['n' => 4, 'p' => ''],
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

		$user_repo = "ScriptParaPHPost/ZCode";
		$ch = curl_init("https://api.github.com/repos/$user_repo/branches/main");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub requiere un User-Agent válido
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
    		"User-Agent: 'Updates for files'"
		]);

		$rqsCurl = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($rqsCurl);
		
		echo json_encode($response->commit);

	break;
}