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

define('DISPLAY_ERRORS', ENVIRONMENT === 'development' ? true : false);
define('DEBUG_MODE', 	 ENVIRONMENT === 'development' ? true : false);

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

define('TS_ROOT', realpath(dirname(__DIR__)) . TS_PATH);

define('LOG_DIR', TS_ROOT . 'logs' . TS_PATH);

define('LOG_ERROR_SCRIPT', LOG_DIR . 'zcodeError.log');

define('LOG_ERROR_INSTALL', LOG_DIR . 'installError.log');

define('LOG_LEVEL_INFO', 'INFO');

define('LOG_LEVEL_WARNING', 'WARNING');

define('LOG_LEVEL_ERROR', 'ERROR');

define('ERROR_REPORTING_LEVEL', DEBUG_MODE ? E_ALL ^ E_WARNING : E_ALL ^ E_WARNING ^ E_NOTICE); 

define('DISPLAY_STARTUP_ERRORS', true);

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

if(!is_dir(LOG_DIR)) mkdir(LOG_DIR, 0777, true);

define('FBVERSION', 'v20.0');
define('GOOGLEVERSION', 'v3');
define('REDDITVERSION', 'v1');