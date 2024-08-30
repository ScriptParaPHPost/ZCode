
<?php if ( ! defined('TS_HEADER')) exit('No direct script access allowed');

/**
 * @name define.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Se definen todas las rutas
**/

define('SCRIPT_NAME', 'ZCode');

define('SCRIPT_VERSION', '2.0.0');

define('SCRIPT_KEY', 'WkNvZGVVcGdyYWRl');

define('SCRIPT_AUTHOR', 'Miguel92');

define('ENVIRONMENT', 'development'); // o 'production'

define('DISPLAY_ERRORS', (ENVIRONMENT === 'development'));

define('DEBUG_MODE', 	 (ENVIRONMENT === 'development'));

define('DISPLAY_STARTUP_ERRORS', (ENVIRONMENT === 'development'));

define('ERROR_REPORTING_LEVEL', DEBUG_MODE ? E_ALL ^ E_WARNING : E_ALL ^ E_WARNING ^ E_NOTICE); 

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

define('TS_ROOT', realpath(dirname(__DIR__)) . TS_PATH);

define('TS_ADMIN', TS_ROOT . 'admin' . TS_PATH);

define('LOG_DIR', TS_ROOT . 'logs' . TS_PATH);

define('LOG_ERROR_SCRIPT', LOG_DIR . 'adminError.log');

define('LOG_LEVEL_INFO', 'INFO');

define('LOG_LEVEL_WARNING', 'WARNING');

define('LOG_LEVEL_ERROR', 'ERROR');

define('ENCRYPTION_KEY', 'b3974372f0b53a49d0dc972ae5bf8d04');

define('SESSION_NAME', 'ZCODESESSID');

define('CSRF_TOKEN_LIFETIME', 3600); // 1 hora

define('SET_LIFETIME', 300); // 5 minutos

define('FEED_CONNECTION', (DEBUG_MODE ? "http://localhost" : "https://zcode.newluckies.com") . '/feed');

// Reporte de errores
error_reporting(ERROR_REPORTING_LEVEL);

ini_set('display_errors', DISPLAY_ERRORS);

ini_set('display_startup_errors', DISPLAY_STARTUP_ERRORS);

ini_set('log_errors', DEBUG_MODE);

/**
 * Rutas APP
 */
define('TS_MODELS',	 TS_ADMIN . 'models' . TS_PATH);
define('TS_HELPERS',	 TS_ADMIN . 'helpers' . TS_PATH);


define('TS_APP', 	 	 TS_ROOT . 'app' . TS_PATH);
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