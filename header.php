<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

if( !defined('ZCODE2') ) define('ZCODE2', TRUE);
if( !defined('ACCESS_ROOT_PATHS') ) define('ACCESS_ROOT_PATHS', TRUE);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Polyfill.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'AppVarsGlobal.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'AppRoutesGlobal.php';

// Sesi�n
session_name($_ENV['SESSION_NAME']);
if(!isset($_SESSION)) session_start();

ini_set('error_log', ERROR_LOG);

header('Content-Type: text/html; charset=utf-8');
// Establece el encabezado Cache-Control con max-age de un a�o
header("Cache-Control: max-age=31536000");

// L�mite de ejecuci�n
set_time_limit(300);

/**
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
*/

include TS_EXTRA . 'functions.php';
include TS_MODELS . 'c.core.php';
include TS_MODELS . 'c.user.php';
include TS_MODELS . 'c.monitor.php';
include TS_MODELS . 'c.actividad.php';
include TS_MODELS . 'c.mensajes.php';
include TS_MODELS . 'c.smarty.php';
include TS_EXTRA . 'QueryString.php';
include TS_ZCODE . 'Images.php';
include TS_ZCODE . 'ZCode.php';

include TS_ZCODE . 'Avatar.php';
$Avatar = new Avatar(new tsZCode);

include TS_ZCODE . 'Theme.php';
$Theme = new Theme;

include TS_ZCODE . 'menu_user_account.php';

/**
 * -------------------------------------------------------------------
 *  Inicializamos los objetos principales
 * -------------------------------------------------------------------
 */
$tsCore = new tsCore;
$tsZCode = new tsZCode;
$tsUser = new tsUser;
$tsImages = new Images;
$tsMonitor = new tsMonitor;
$tsActividad = new tsActividad;
$tsMP = new tsMensajes;

// Definimos el template a utilizar
$tsTema = $tsCore->settings['tema'];
if(empty($tsTema)) $tsTema = 'default';
define('TS_TEMA', $tsTema);

// Smarty
$smarty = new tsSmarty();
// Nueva configuraci�n
$smarty->output(false);

/**
 * -------------------------------------------------------------------
 *  Asignaci�n de variables
 * -------------------------------------------------------------------
 */
require_once TS_ZCODE . 'Authentication.php';
$OAuthentication = new OAuthentication;
$smarty->assign('SocialMager', $OAuthentication->OAuth());

// Configuraciones
$smarty->assign('tsConfig', $tsCore->settings);
$smarty->assign('tsCategorias', $tsCore->getCategorias());
$smarty->assign('tsRoutes', $tsCore->setRoutes());

// Noticias
$smarty->assign('tsNews', $tsCore->getNews());

// Moderaci�n total
$smarty->assign('tsNovemods', $tsCore->getNovemods());

// Solo verificaci�n
$smarty->assign('tsVerification', $tsZCode->verification());

// Obtejo usuario
$smarty->assign('tsUser', $tsUser);

// Avisos
$smarty->assign('tsAvisos', $tsMonitor->avisos);

// Nofiticaciones
$smarty->assign('tsNots', $tsMonitor->notificaciones);

// Mensajes
$smarty->assign('tsMPs', $tsMP->mensajes);

$smarty->assign('tsThemeSettings', $Theme->getSettingsTheme());
$smarty->assign('tsThemeBox', $Theme->getSettingPageBox());

$smarty->assign('tsMenuCuenta', $menu_cuenta);

if (!extension_loaded('gd') && !function_exists('gd_info')) {
	$smarty->assign('gd_info', 'La extensi&oacute;n GD no est&aacute; habilitada en tu servidor.');
}

// Baneo por IP
$tsZCode->verifiedIP($smarty);

// Online/Offline
$tsZCode->verifiedMaintenance($smarty);