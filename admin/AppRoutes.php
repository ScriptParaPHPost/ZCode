<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

if (!defined('ZCODEV3')) exit('No se permite el acceso directo al script');
if(!defined('ACCESS_ROOT_PATHS')) exit('No puedes!');

/** 
 * Rutas ADMIN
 */
define('TS_ADMIN', 		BASEPATH . 'admin' . DIRECTORY_SEPARATOR);

/**
 * Rutas APP
 */
define('TS_APP', 		 	BASEPATH . 'app' . DIRECTORY_SEPARATOR);
define('TS_PLUGINS',  	TS_APP . 'plugins' . DIRECTORY_SEPARATOR);
define('TS_JUNK', 	 	TS_APP . 'junk' . DIRECTORY_SEPARATOR);
define('TS_UTILS', 	 	TS_APP . 'utils' . DIRECTORY_SEPARATOR);

/**
 * Rutas ASSETS
 */
define('TS_ASSETS', 		BASEPATH . 'assets' . DIRECTORY_SEPARATOR);
define('TS_IMAGES', 		TS_ASSETS . 'images' . DIRECTORY_SEPARATOR);
define('TS_AVATARES',	TS_IMAGES . 'avatares' . DIRECTORY_SEPARATOR);

define('TS_AUTH', 		BASEPATH . 'auth' . DIRECTORY_SEPARATOR);

/**
 * Rutas STORAGE
 */
define('TS_STORAGE', 	 BASEPATH . 'storage' . DIRECTORY_SEPARATOR);
define('TS_AVATAR', 		 TS_STORAGE . 'avatar' . DIRECTORY_SEPARATOR);
define('TS_CACHE', 		 TS_STORAGE . 'cache' . DIRECTORY_SEPARATOR);
define('TS_PORTADAS',	 TS_STORAGE . 'portadas' . DIRECTORY_SEPARATOR);
define('TS_UPLOADS', 	 TS_STORAGE . 'uploads' . DIRECTORY_SEPARATOR);
define('TS_BACKUP', 		 TS_STORAGE . 'backup' . DIRECTORY_SEPARATOR);
define('TS_AVATAR_USER', TS_AVATAR . 'user');

/**
 * Rutas THEMES
 */
define('TS_THEMES', BASEPATH . 'themes' . DIRECTORY_SEPARATOR);
define('VERSION',   BASEPATH . '.version');
define('LOCK', 	  BASEPATH . '.lock');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));