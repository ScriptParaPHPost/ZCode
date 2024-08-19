<?php

/**
 * @name index.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcode-script/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.7.0
 * @description Archivo para cargar el sitio
**/

// Incluimos header
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'header.php';

// Checamos...
if($tsCore->settings['c_allow_portal'] === 1 && $tsUser->is_member == true && $_GET['do'] == 'portal') {
	include TS_PHP . 'portal.php';

} else {
	include TS_PHP . 'posts.php';
	
}