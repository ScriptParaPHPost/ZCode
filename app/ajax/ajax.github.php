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

		$rama = $tsCore->setSecure(filter_input(INPUT_POST, 'branch', FILTER_UNSAFE_RAW) ?? 'main');

		$url = 'https://api.github.com/repos/ScriptParaPHPost/zcode/commits?sha=' . $rama;
		$options = [
			 'http' => [
				  'method' => 'GET',
				  'header' => [
						'User-Agent: ZCodeApp', // GitHub requiere un User-Agent personalizado
						'Accept: application/vnd.github.v3+json'
				  ]
			 ]
		];

		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);

		if ($response === FALSE) {
			 die('Error al conectarse a la API de GitHub');
		}

		$data = json_decode($response, true);
		// Mostrar el último commit
		if (!empty($data)) {
		   $ultimoCommit = $data[0];
		   $state = 1;
		   
		   $newData = [
		   	'sha' => $ultimoCommit['sha'],
		   	'html_url' => $ultimoCommit['html_url'],
		   	'author' => $ultimoCommit['commit']['author']['name'],
		   	'message' => $ultimoCommit['commit']['message'],
		   	'date' => $ultimoCommit['commit']['author']['date'],
		   	'verified' => $ultimoCommit['commit']['verification']['verified'],
		   	'reason' => $ultimoCommit['commit']['verification']['reason']
		   ];
		} else {
		   $state = 0;
		   $newData = 'No hay commits en la rama.';
		}
		echo json_encode([
			'state' => $state, 
			'data' => $newData
		]);

	break;
}