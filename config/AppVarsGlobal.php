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

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

define('TS_ROOT', realpath(dirname(__DIR__)) . TS_PATH);

define('DIR_ERROR_LOG', TS_ROOT . 'logs' . TS_PATH);

//
define('SCRIPT_NAME', 'ZCode');

define('SCRIPT_AUTHOR', 'Miguel92');

define('SCRIPT_VERSION', file_get_contents(TS_ROOT . '.version'));

// Reporte de errores
error_reporting(($_ENV['DEBUG_MODE'] === 'true' ? E_ALL ^ E_WARNING ^ E_NOTICE : 0));

ini_set('display_errors', ($_ENV['DEBUG_MODE'] === 'true'));

ini_set('display_startup_errors', ($_ENV['DEBUG_MODE'] === 'true'));

ini_set('log_errors', ($_ENV['DEBUG_MODE'] === 'true'));