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

/*
 * -------------------------------------------------------------------
 *  Definiendo constantes
 * -------------------------------------------------------------------
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Polyfill.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'AppVarsGlobal.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'configs.php';
	
// Sesión
session_name('ADMIN_'.$_ENV['SESSION_NAME']);
if(!isset($_SESSION)) session_start();

ini_set('error_log', DASHBOARD_LOG);

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
	
	// Funciones
	include TS_EXTRA . 'functions.php';

	include TS_ZCODE . 'ZCode.php';

	// Nucleo
	include TS_MODELS . 'c.core.php';
	
	// Controlador de usuarios
	include TS_MODELS . 'c.user.php';

	// Monitor de usuario
	include TS_MODELS . 'c.monitor.php';
	
	// Actividad de usuario
	include TS_MODELS . 'c.actividad.php';

	// Mensajes de usuario
	include TS_MODELS.'c.mensajes.php';

	// Smarty
	include TS_SMARTY . 'autoload.php';
	
	// Crean requests
	include TS_EXTRA . 'QueryString.php';


	// Cargamos el nucleo
	$tsCore = new tsCore();

	// Usuario
	$tsUser = new tsUser();
	
	$tsZCode = new tsZCode();

	// Monitor
	$tsMonitor = new tsMonitor();

	// Actividad
	$tsActividad = new tsActividad();

	// Mensajes
	$tsMP = new tsMensajes();

	// Smarty
	$smarty = new \Smarty\Smarty;

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

	include TS_ZCODE . 'menu_user_account.php';
	$smarty->assign('tsMenuCuenta', $menu_cuenta);