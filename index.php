<?php

/**
 * @name index.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Archivo para cargar el sitio
**/

// Incluimos header
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'header.php';

// Checamos...
if((int)$tsCore->settings['c_allow_portal'] === 1 && $tsUser->is_member == true && $_GET['do'] == 'portal') {
	include TS_HELPERS . 'portal.php';

} else {
	include TS_HELPERS . 'posts.php';
	
}