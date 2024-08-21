<?php
/**
 * Archivo de Inicialización del Sistema
 *
 * Carga las clases base y ejecuta la solicitud.
 *
 * @name    header.php
 * @author  Miguel92 & PHPost.es
 */

/*
 * -------------------------------------------------------------------
 *  Estableciendo variables importantes
 * -------------------------------------------------------------------
 */

	if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);

	// Sesión
	if(!isset($_SESSION)) session_start();

	// Reporte de errores
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
	ini_set('display_errors', TRUE);
	ini_set('log_errors', 1);
	ini_set('error_log', realpath(__DIR__) . DIRECTORY_SEPARATOR . 'error.log');

	// Límite de ejecución
	set_time_limit(300);

/*
 * -------------------------------------------------------------------
 *  Definiendo constantes
 * -------------------------------------------------------------------
 */
	require realpath(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'defined.php';
	header('Content-Type: text/html; charset=utf-8');
	// Establece el encabezado Cache-Control con max-age de un año
	header("Cache-Control: max-age=31536000");

/*
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
 */
	
	// Funciones
	include TS_EXTRA . 'functions.php';

	include TS_EXTRA . 'ZCode.php';

	// Nucleo
	include TS_CLASS . 'c.core.php';
	
	// Controlador de usuarios
	include TS_CLASS . 'c.user.php';

	// Monitor de usuario
	include TS_CLASS . 'c.monitor.php';
	
	// Actividad de usuario
	include TS_CLASS . 'c.actividad.php';

	// Mensajes de usuario
	include TS_CLASS.'c.mensajes.php';

	// Smarty
	include TS_CLASS . 'c.smarty.php';
	
	// Crean requests
	include TS_EXTRA . 'QueryString.php';

	// Usando un gestor de imagenes
	include TS_EXTRA . 'Images.php';
	include TS_EXTRA . 'Avatar.php';

	$Avatar = new Avatar(new tsCore);
	$Avatar->moveAvatars();

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
	$tsTema = $tsCore->settings['tema']['t_path'];
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

	$smarty->assign('tsSchemeColor', $tsCore->setColorScheme());

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
$ip = $_SERVER['X_FORWARDED_FOR'] ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
if(!filter_var($ip, FILTER_VALIDATE_IP)) die('Su ip no se pudo validar.'); 
if(db_exec('num_rows', db_exec(array(__FILE__, __LINE__), 'query', 'SELECT id FROM @blacklist WHERE type = \'1\' && value = \''.$ip.'\' LIMIT 1'))) die('Bloqueado');

// Online/Offline
if($tsCore->settings['offline'] == 1 && ($tsUser->is_admod != 1 && $tsUser->permisos['govwm'] == false) && $_GET['action'] != 'login-user'){
	$smarty->assign('tsTitle',$tsCore->settings['titulo'].' -  '.$tsCore->settings['slogan']);
	  if(empty($_GET['action'])) 
		$smarty->display('mantenimiento.tpl');
	  else die('Espera un poco...');
	exit();
// Banned
} elseif($tsUser->is_banned) {
	  $banned_data = $tsUser->getUserBanned();
	  if(!empty($banned_data)){
			// SI NO ES POR AJAX
			if(empty($_GET['action'])){
				 $smarty->assign('tsBanned',$banned_data);
				 $smarty->display('suspension.tpl');
			} 
			else die('<div class="emptyError">Usuario suspendido</div>');
			//
			exit;
	  }
}