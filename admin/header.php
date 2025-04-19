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

if( !defined('ZCODEV3') ) define('ZCODEV3', TRUE);
if( !defined('ACCESS_ROOT_PATHS') ) define('ACCESS_ROOT_PATHS', TRUE);

require_once __DIR__ . '/../vendor/autoload.php';

//
require_once __DIR__ . '/Polyfill.php';
require_once __DIR__ . '/app-vars-global.php';
require_once __DIR__ . '/app-routes.php';
require_once __DIR__ . '/configs.php';

// Sesión
session_name('ADMIN_' . env('SESSION_NAME'));
if(!isset($_SESSION)) session_start();

header('Content-Type: text/html; charset=utf-8');

// Establece el encabezado Cache-Control con max-age de un año
header("Cache-Control: max-age=31536000");

// Límite de ejecución
set_time_limit(300);
define('TS_TEMA', '');

/*
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
 */
include TS_UTILS . 'Functions.php';

use admin\models\{Core,User,Monitor,Actividad,Mensajes};
use app\models\{Smarty};
use app\utils\{LimpiarSolicitud,OAuthentication,Theme,Zcode};


$tsCore = new Core;
$tsUser = new User;
$tsZCode = new Zcode;
$tsMonitor = new Monitor;
$tsActividad = new Actividad;
$tsMP = new Mensajes;
$smarty = new Smarty;

// Configuraciones
$smarty->assign('tsConfig', $tsCore->settings);
$smarty->assign('tsRoutes', $tsCore->setRoutes());

// Obtejo usuario
$smarty->assign('tsUser', $tsUser);

// Avisos
$smarty->assign('tsAvisos', $tsMonitor->avisos);

// Nofiticaciones
$smarty->assign('tsNots', $tsMonitor->notificaciones);

// Mensajes
$smarty->assign('tsMPs', $tsMP->mensajes);

include TS_JUNK . 'menu_user_account.php';
$smarty->assign('tsMenuCuenta', $menu_cuenta);