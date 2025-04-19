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

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config/Polyfill.php';
require_once __DIR__ . '/config/AppVarsGlobal.php';
require_once __DIR__ . '/config/AppRoutesGlobal.php';

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

include TS_UTILS . 'Functions.php';

use app\models\{Core,User,Monitor,Actividad,Mensajes,Smarty};
use app\utils\{LimpiarSolicitud,OAuthentication,Theme,Zcode};


/**
 * -------------------------------------------------------------------
 *  Inicializamos los objetos principales
 * -------------------------------------------------------------------
 */
$solicitudes = new LimpiarSolicitud;
$solicitudes->run(); 

$tsCore = new Core;
$tsZCode = new Zcode($tsCore);
$tsUser = new User;
$tsMonitor = new Monitor;
$tsActividad = new Actividad;
$tsMP = new Mensajes;
$Theme = new Theme;

// Definimos el template a utilizar
$tsTema = $tsCore->settings['tema'];
if(empty($tsTema)) $tsTema = 'default';
define('TS_TEMA', $tsTema);

// Smarty
$smarty = new Smarty();
// Nueva configuraci�n
$smarty->output(false);


/**
 * -------------------------------------------------------------------
 *  Asignaci�n de variables
 * -------------------------------------------------------------------
 */
$smarty->assign('SocialMager', (new OAuthentication)->OAuth());

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

$smarty->assign('Theme', $Theme);
$smarty->assign('tsThemeBox', $Theme->getSettingPageBox());

include TS_JUNK . 'MenuUserAccount.php';
$smarty->assign('tsMenuCuenta', $MenuCuenta);

if (!extension_loaded('gd') && !function_exists('gd_info')) {
	$smarty->assign('gd_info', 'La extensi&oacute;n GD no est&aacute; habilitada en tu servidor.');
}

// Baneo por IP
$tsZCode->verifiedIP($smarty);

// Online/Offline
$tsZCode->verifiedMaintenance($smarty);

if (!isset($_SESSION['csrf'])) {
   $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$smarty->assign('csrf_token', $_SESSION['csrf'] ?? null);