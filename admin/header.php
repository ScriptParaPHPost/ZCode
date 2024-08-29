<?php
/**
 * Archivo de Inicialización del Sistema
 *
 * Carga las clases base y ejecuta la solicitud.
 *
 * @name    header.php
 * @author  Miguel92 
 */

/*
 * -------------------------------------------------------------------
 *  Estableciendo variables importantes
 * -------------------------------------------------------------------
 */

	if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);
	if( !defined('ACCESS_ROOT_PATHS') ) define('ACCESS_ROOT_PATHS', TRUE);

/*
 * -------------------------------------------------------------------
 *  Definiendo constantes
 * -------------------------------------------------------------------
 */
	
	require realpath(__DIR__) . DIRECTORY_SEPARATOR . 'configs.php';
	
	// Sesión
	session_name(SESSION_NAME);
	if(!isset($_SESSION)) session_start();

	ini_set('error_log', LOG_ERROR_SCRIPT);

	header('Content-Type: text/html; charset=utf-8');
	// Establece el encabezado Cache-Control con max-age de un año
	header("Cache-Control: max-age=31536000");

	// Límite de ejecución
	set_time_limit(SET_LIFETIME);

/*
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
 */
	
	// Funciones
	include TS_EXTRA . 'functions.php';

	include TS_ZCODE . 'Polyfill.php';

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
	include TS_SMARTY . 'bootstrap.php';
	
	// Crean requests
	include TS_EXTRA . 'QueryString.php';

/*
 * -------------------------------------------------------------------
 *  Inicializamos los objetos principales
 * -------------------------------------------------------------------
 */

	// Cargamos el nucleo
	$tsCore = new tsCore();

	// Usuario
	$tsUser = new tsUser();

	// Monitor
	$tsMonitor = new tsMonitor();

	// Actividad
	$tsActividad = new tsActividad();

	// Mensajes
	$tsMP = new tsMensajes();

	// Definimos el template a utilizar
	$tsTema = $tsCore->settings['tema']['t_path'];
	if(empty($tsTema)) $tsTema = 'default';
	define('TS_TEMA', $tsTema);

	// Smarty
	$smarty = new Smarty();

	// Configuraciones
	$smarty->assign('tsConfig', $tsCore->settings);

	// Obtejo usuario
	$smarty->assign('tsUser', $tsUser);
	
	// Avisos
	$smarty->assign('tsAvisos', $tsMonitor->avisos);
	
	// Nofiticaciones
	$smarty->assign('tsNots', $tsMonitor->notificaciones);
	
	// Mensajes
	$smarty->assign('tsMPs', $tsMP->mensajes);