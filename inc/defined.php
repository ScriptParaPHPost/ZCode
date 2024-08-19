<?php if ( ! defined('TS_HEADER')) exit('No direct script access allowed');

/**
 * @name define.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcode-script/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.7.0
 * @description Se definen todas las rutas
**/

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

define('TS_ROOT', realpath(dirname(__DIR__)) . TS_PATH);

define('TS_ACCESS', 		TS_ROOT . 'access' . TS_PATH);
define('TS_ASSETS', 		TS_ROOT . 'assets' . TS_PATH);
define('TS_CACHE', 		TS_ROOT . 'cache' . TS_PATH);
define('TS_DASHBOARD', 	TS_ROOT . 'dashboard' . TS_PATH);
define('TS_INCLUDES', 	TS_ROOT . 'inc' . TS_PATH);
define('TS_THEMES', 		TS_ROOT . 'themes' . TS_PATH);
define('TS_UPDATE', 		TS_ROOT . 'update' . TS_PATH);

define('TS_CALLBACK',	TS_INCLUDES . 'callback' . TS_PATH);
define('TS_CLASS',	TS_INCLUDES . 'class' . TS_PATH);
define('TS_PHP',		TS_INCLUDES . 'php' . TS_PATH);
define('TS_EXTRA', 	TS_INCLUDES . 'ext' . TS_PATH);
define('TS_PLUGINS', TS_INCLUDES . 'plugins' . TS_PATH);
define('TS_SMARTY', 	TS_INCLUDES . 'smarty' . TS_PATH);

define('TS_IMAGES', 	 TS_ASSETS . 'images' . TS_PATH);
define('TS_AVATAR', 	 TS_IMAGES . 'avatar' . TS_PATH);
define('TS_AVATARES', TS_IMAGES . 'avatares' . TS_PATH);
define('TS_UPLOADS',  TS_IMAGES . 'uploads' . TS_PATH);
define('TS_PORTADAS', TS_IMAGES . 'portadas' . TS_PATH);

define('GOOGLE2FA', 		 TS_EXTRA . 'google' . TS_PATH);
define('DATABASE', 		 TS_EXTRA . 'database.php');
define('CONFIG_EXAMPLE', TS_INCLUDES . 'example.config.php');
define('CONFIG', 			 TS_ROOT . 'config.inc.php');
define('LICENSE', 		 TS_ROOT . 'LICENSE');
define('LOCK', 		 	 TS_ROOT . '.lock');
define('VERSION', 		 TS_ROOT . '.version');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));

$menu_cuenta = [
	'' => ["icon" => "clipboard", "name" => "Cuenta"],
	'perfil' => ["icon" => "clipboard-notes", "name" => "Perfil"],
	'apariencia' => ["icon" => "table-header", "name" => "Apariencia & Avatar"],
	'block' =>["icon" => "no-sign", "name" => "Bloqueados"],
	'clave' =>["icon" => "fingerprint", "name" => "Cambiar Clave"],
	'nick' => ["icon" => "keyboard", "name" => "Cambiar Nick"],
	'privacidad' => ["icon" => "nut", "name" => "Privacidad"]
];