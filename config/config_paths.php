<?php

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');
if(!defined('ACCESS_ROOT_PATHS')) exit('No puedes!');

/** 
 * Rutas ADMIN
 */
define('TS_ADMIN', 		TS_ROOT . 'admin' . TS_PATH);

/**
 * Rutas APP
 */
define('TS_APP', 		 TS_ROOT . 'app' . TS_PATH);
define('TS_CALLBACK', TS_APP . 'callback' . TS_PATH);
define('TS_MODELS',	 TS_APP . 'models' . TS_PATH);
define('TS_HELPERS',	 TS_APP . 'helpers' . TS_PATH);
define('TS_EXTRA', 	 TS_APP . 'extras' . TS_PATH);
define('TS_PLUGINS',  TS_APP . 'plugins' . TS_PATH);
define('TS_SMARTY', 	 TS_APP . 'smarty' . TS_PATH);
define('TS_ZCODE', 	 TS_APP . 'zcode' . TS_PATH);

define('GOOGLE2FA', 	 TS_EXTRA . 'google' . TS_PATH);
define('DATABASE', 	 TS_ZCODE . 'database.php');

/**
 * Rutas ASSETS
 */
define('TS_ASSETS', 		TS_ROOT . 'assets' . TS_PATH);
define('TS_IMAGES', 		TS_ASSETS . 'images' . TS_PATH);
define('TS_AVATARES',	TS_IMAGES . 'avatares' . TS_PATH);

define('TS_AUTH', 		TS_ROOT . 'auth' . TS_PATH);

/**
 * Rutas CONFIG
 */
define('TS_CONFIG', 		   TS_ROOT . 'config' . TS_PATH);
define('CONFIG_EXAMPLE',   TS_CONFIG . 'example.php');
define('DATABASE_CONNECT', TS_CONFIG . 'config.inc.php');

/**
 * Rutas STORAGE
 */
define('TS_STORAGE', 	 TS_ROOT . 'storage' . TS_PATH);
define('TS_AVATAR', 		 TS_STORAGE . 'avatar' . TS_PATH);
define('TS_CACHE', 		 TS_STORAGE . 'cache' . TS_PATH);
define('TS_PORTADAS',	 TS_STORAGE . 'portadas' . TS_PATH);
define('TS_UPLOADS', 	 TS_STORAGE . 'uploads' . TS_PATH);
define('TS_BACKUP', 		 TS_STORAGE . 'backup' . TS_PATH);
define('LOCK', 		 	 TS_STORAGE . '.lock');
define('VERSION', 		 TS_STORAGE . '.version');
define('TS_AVATAR_USER', TS_AVATAR . 'user');

/**
 * Rutas THEMES
 */
define('TS_THEMES', TS_ROOT . 'themes' . TS_PATH);

define('LICENSE',   TS_ROOT . 'LICENSE');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));