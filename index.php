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

if(!file_exists(__DIR__ . '/.env')) {

	header("Location: ./install/index.php?action=licencia&version=3.1.18");
	die;
	
} else {

	// Incluimos header
	include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'header.php';

	$doPage = filter_input(INPUT_GET, 'do', FILTER_UNSAFE_RAW) === 'portal';
	// Checamos...
	if((int)$tsCore->settings['c_allow_portal'] === 1 && $tsUser->is_member && $doPage) {
		include __DIR__ . '/app/controller/portal.php';

	} else {
		include __DIR__ . '/app/controller/posts.php';
		
	}
}