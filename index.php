<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

// Incluimos header
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'header.php';

// Checamos...
if((int)$tsCore->settings['c_allow_portal'] === 1 && $tsUser->is_member == true && $_GET['do'] == 'portal') {
	include TS_HELPERS . 'portal.php';

} else {
	include TS_HELPERS . 'posts.php';
	
}