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

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

$files = [
   'feed-support' => ['n' => 4, 'p' => ''],
   'feed-version' => ['n' => 4, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.live.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'] ?? 0;

$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
//
$code = [
   'title' => $tsCore->settings['titulo'],
   'url' => $tsCore->settings['url'],
   'version' => $tsCore->settings['version'],
   'admin' => $tsUser->nick,
   'id' => $tsUser->uid,
   'key' => $_ENV['ZCODE_VERIFY_KEY'],
   'pin' => $_ENV['ZCODE_VERIFY_PIN']
];
$key = base64_encode(serialize($code));
$key .= '&=' . base64_encode($_ENV['ZCODE_SCRIPT_KEY']);

$params = http_build_query([
   'data' => $code,
   'license' => $_ENV['ZCODE_LICENSE'],
   'verification' => base64_encode($_ENV['ZCODE_SCRIPT_KEY']),
   'type' => explode('-', $action)[1] ?? '',
]);
// CODIGO
switch($action){
	case 'feed-support':
		$json = $tsCore->getUrlContent("http://localhost/feed/index.php?$params");
		echo $json;
	break;
	case 'feed-version':
		$time = time();
		$version_now = SCRIPT_NAME . ' ' . SCRIPT_VERSION;
		$version_code = str_replace([' ', '.'], '_', strtolower($version_now));
		# ACTUALIZAR VERSIÓN
		if($tsCore->settings['version'] != $version_now){
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET version = '$version_now', version_code = '$version_code' WHERE tscript_id = 1 LIMIT 1");
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET stats_time_upgrade = $time WHERE stats_no = 1 LIMIT 1");
		}
		$json = $tsCore->getUrlContent("http://localhost/feed/index.php?$params");
		echo $json;
	break;
	default:
		die('0: Este archivo no existe.');
	break;
}