<?php

if (!defined('ZCODE2')) exit('No se permite el acceso directo al script');
if(!defined('ACCESS_ROOT_PATHS')) exit('No puedes!');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

/** 
 * Rutas ADMIN
 */
define('TS_ADMIN', 		TS_ROOT . 'admin' . DIRECTORY_SEPARATOR);

/**
 * Rutas APP
 */
define('TS_APP', 		 TS_ROOT . 'app' . DIRECTORY_SEPARATOR);
define('TS_CALLBACK', TS_APP . 'callback' . DIRECTORY_SEPARATOR);
define('TS_MODELS',	 TS_APP . 'models' . DIRECTORY_SEPARATOR);
define('TS_HELPERS',	 TS_APP . 'helpers' . DIRECTORY_SEPARATOR);
define('TS_EXTRA', 	 TS_APP . 'extras' . DIRECTORY_SEPARATOR);
define('TS_PLUGINS',  TS_APP . 'plugins' . DIRECTORY_SEPARATOR);
define('TS_SMARTY', 	 TS_APP . 'smarty' . DIRECTORY_SEPARATOR);
define('TS_ZCODE', 	 TS_APP . 'zcode' . DIRECTORY_SEPARATOR);

define('GOOGLE2FA', 	 TS_EXTRA . 'google' . DIRECTORY_SEPARATOR);
define('DATABASE', 	 TS_ZCODE . 'database.php');

/**
 * Rutas ASSETS
 */
define('TS_ASSETS', 		TS_ROOT . 'assets' . DIRECTORY_SEPARATOR);
define('TS_IMAGES', 		TS_ASSETS . 'images' . DIRECTORY_SEPARATOR);
define('TS_AVATARES',	TS_IMAGES . 'avatares' . DIRECTORY_SEPARATOR);

define('TS_AUTH', 		TS_ROOT . 'auth' . DIRECTORY_SEPARATOR);

/**
 * Rutas STORAGE
 */
define('TS_STORAGE', 	 TS_ROOT . 'storage' . DIRECTORY_SEPARATOR);
define('TS_AVATAR', 		 TS_STORAGE . 'avatar' . DIRECTORY_SEPARATOR);
define('TS_CACHE', 		 TS_STORAGE . 'cache' . DIRECTORY_SEPARATOR);
define('TS_PORTADAS',	 TS_STORAGE . 'portadas' . DIRECTORY_SEPARATOR);
define('TS_UPLOADS', 	 TS_STORAGE . 'uploads' . DIRECTORY_SEPARATOR);
define('TS_BACKUP', 		 TS_STORAGE . 'backup' . DIRECTORY_SEPARATOR);
define('TS_AVATAR_USER', TS_AVATAR . 'user');

/**
 * Rutas THEMES
 */
define('TS_THEMES', TS_ROOT . 'themes' . DIRECTORY_SEPARATOR);
define('VERSION',   TS_ROOT . '.version');
define('LOCK', 	  TS_ROOT . '.lock');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));