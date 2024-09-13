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

	require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Polyfill.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'AppVarsGlobal.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'AppRoutesGlobal.php';

	
	// Sesión
	session_name($_ENV['SESSION_NAME']);
	if(!isset($_SESSION)) session_start();

	ini_set('error_log', DIR_ERROR_LOG . 'zcode_error.log');

	header('Content-Type: text/html; charset=utf-8');
	// Establece el encabezado Cache-Control con max-age de un año
	header("Cache-Control: max-age=31536000");

	// Límite de ejecución
	set_time_limit(300);

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
	include TS_MODELS . 'c.smarty.php';
	
	// Crean requests
	include TS_EXTRA . 'QueryString.php';

	// Usando un gestor de imagenes
	include TS_ZCODE . 'Images.php';
	
	include TS_ZCODE . 'Avatar.php';
	$Avatar = new Avatar(new tsCore);
	$Avatar->moveAvatars();

	include TS_ZCODE . 'Theme.php';
	$Theme = new Theme;

	include TS_ZCODE . 'menu_user_account.php';

/*
 * -------------------------------------------------------------------
 *  Inicializamos los objetos principales
 * -------------------------------------------------------------------
 */

	// Cargamos el nucleo
	$tsCore = new tsCore();

	// Usuario
	$tsUser = new tsUser();

	// Función para generar imagenes
	$tsImages = new Images;

	// Monitor
	$tsMonitor = new tsMonitor();

	// Actividad
	$tsActividad = new tsActividad();

	// Mensajes
	$tsMP = new tsMensajes();

	// Definimos el template a utilizar
	$tsTema = $tsCore->settings['tema'];
	if(empty($tsTema)) $tsTema = 'default';
	define('TS_TEMA', $tsTema);

	// Smarty
	$smarty = new tsSmarty();
	// Nueva configuración
	$smarty->output(false);
	
/*
 * -------------------------------------------------------------------
 *  Asignación de variables
 * -------------------------------------------------------------------
 */
	 
	$smarty->assign('SocialMager', $tsCore->OAuth());
	
	// Configuraciones
	$smarty->assign('tsConfig', $tsCore->settings);

	// Noticias
	$smarty->assign('tsNews', $tsCore->getNews());

	// Moderación total
	$smarty->assign('tsNovemods', $tsCore->getNovemods());

	// Solo verificación
	$smarty->assign('tsVerification', $tsCore->verification());

	// Obtejo usuario
	$smarty->assign('tsUser', $tsUser);
	
	// Avisos
	$smarty->assign('tsAvisos', $tsMonitor->avisos);
	
	// Nofiticaciones
	$smarty->assign('tsNots', $tsMonitor->notificaciones);
	
	// Mensajes
	$smarty->assign('tsMPs', $tsMP->mensajes);

	$smarty->assign('tsSchemeColor', $Theme->setSchemeColor());
	$smarty->assign('tsThemeFont', $Theme->setThemeFont());

	$smarty->assign('tsMenuCuenta', $menu_cuenta);

	if (!extension_loaded('gd') && !function_exists('gd_info')) {
		$smarty->assign('gd_info', 'La extensi&oacute;n GD no est&aacute; habilitada en tu servidor.');
	}	 

/*
 * -------------------------------------------------------------------
 *  Validaciones extra
 * -------------------------------------------------------------------
 */
// Baneo por IP
$tsCore->verifiedIP($smarty);

// Online/Offline
$tsCore->verifiedMaintenance($smarty);