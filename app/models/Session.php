<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\models;

if (!defined('ZCODEV3')) exit('No se permite el acceso directo al script');

use app\interfaces\SessionInterface;

class Session implements SessionInterface {

	public $ID                 = '';

	public $sess_expiration    = 7200;

	public $sess_match_ip      = FALSE;

	public $sess_time_online   = 300;

	public $cookie_prefix      = 'zcode_';

	public $cookie_name        = '';

	public $cookie_path        = '/';

	public $cookie_domain      = '';

	public $userdata;

	public $ip_address;

	public $time_now;

	public $db;

	public function __construct() {
		global $tsCore;
		// Tiempo
		$this->time_now = time();
		// Obtener el dominio o subdominio para la cookie
		$host = parse_url($tsCore->settings['url']);
		$host = str_replace('www.', '' , strtolower($host['host']));
		// Establecer variables
		$this->cookie_domain = ($host == 'localhost') ? '' : '.' . $host;
		$this->cookie_name = $this->cookie_prefix . substr(md5($host), 0, 6);
		// IP
		$this->ip_address = $tsCore->getIP();
		// Cada que un usuario cambie de IP, requerir nueva session?
		$this->sess_match_ip = empty($tsCore->settings['c_allow_sess_ip']) ? FALSE : TRUE;
		// Cada cuanto actualizar la sesión? && Expires
		$this->sess_time_online = empty($tsCore->settings['c_last_active']) ? $this->sess_time_online : ($tsCore->settings['c_last_active'] * 60);
	}

	/**
	 * Leer session activa
	 *
	 * @access	public
	 * @return	bool
	*/
	public function read() {
		$this->ID = $_COOKIE[$this->cookie_name . '_sid'] ?? null;
		// Es un ID válido?
		if(!$this->ID || strlen($this->ID) != 32) {
			return FALSE;
		}
		// ** Obtener session desde la base de datos
		$session = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT session_id, session_user_id, session_ip, session_token, session_time, session_autologin FROM @sessions WHERE session_id = '{$this->ID}'"));
		// Existe en la DB?
		if(!isset($session['session_id'])) {
			$this->destroy();
			return FALSE;
		}
		// Is the session current?
		if (($session['session_time'] + $this->sess_expiration) < $this->time_now AND empty($session['session_autologin'])) {
			$this->destroy();
			return FALSE;
		}
		// Si cambió de IP creamos una nueva session
		if($this->sess_match_ip == TRUE && $session['session_ip'] != $this->ip_address) {
			$this->destroy();
			return FALSE;
		}
		// Listo guardamos y retornamos
		$this->userdata = $session;
		unset($session);
		return TRUE;
	}

	/**
	 * Create a new session
	 *
	 * @access	public
	 * @return	void
	*/
	public function create() {
		// Generar ID de sesión
		$this->ID = $this->gen_session_id();
		// Guardar en la base de datos, session_user_id siemrpe será 0 aquí | si inicia sesión se "actualiza"
		db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @sessions (session_id, session_user_id, session_ip, session_time) VALUES ('{$this->ID}', 0, '{$this->ip_address}', {$this->time_now})");
		// Establecemos la cookie
		$this->set_cookie('sid', $this->ID, $this->sess_expiration);
	}

	/**
	 * Update an existing session
	 *
	 * @access	public
	 * @return	void
	 */
	public function update($user_id = 0, $autologin = FALSE, $force_update = FALSE) {
		// Actualizar la sesión cada x tiempo, esto es configurado en el panel de Admin
		if(($this->userdata['session_time'] + $this->sess_time_online) >= $this->time_now AND $force_update == FALSE) {
			return;
		}
		// Datos para actualizar
		$this->userdata['session_user_id'] = empty($user_id) ? $this->userdata['session_user_id'] : $user_id;
		$this->userdata['session_ip'] = $this->ip_address;
		$this->userdata['session_time'] = $this->time_now;
		$this->userdata['session_token'] = bin2hex(random_bytes(32));
		// Autologin requiere una comprovación doble
		$autologin = ($autologin == FALSE) ? 0 : 1;
		$this->userdata['session_autologin'] = empty($this->userdata['session_autologin']) ? $autologin : $this->userdata['session_autologin'];
		// Actualizar en la DB
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @sessions SET session_user_id = '{$this->userdata['session_user_id']}', session_ip = '{$this->userdata['session_ip']}', session_token = '{$this->userdata['session_token']}', session_time = {$this->userdata['session_time']}, session_autologin = '{$this->userdata['session_autologin']}' WHERE session_id = '{$this->ID}'");
		// Limpiar sesiones
		$this->sess_gc();
		// Actualizar cookie | Si el usuario quiere recordar su sesión, se guardará por 1 año
		$expiration = (!empty($this->userdata['session_autologin'])) ? 31500000 : $this->sess_expiration;
		//
		$this->set_cookie('sid', $this->ID, $expiration);
	}

	/**
	 * Destroy the current session
	 *
	 * @access	public
	 * @return	void
	 */
	public function destroy() {
		// Elminar de la DB
		db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @sessions WHERE session_id = '{$this->ID}'");
		// Reset a la cookie
		$this->set_cookie('sid', '', -31500000);
	}

	 /**
	  * Crear cookie
	  * @access public
	  * @param string
	  * @param string
	  * @param int
	  */
	public function set_cookie($name, $cookiedata, $cookietime) {
		$cookiename = rawurlencode($this->cookie_name . '_' . $name);
		$cookiedata = rawurlencode($cookiedata);
		// Establecer la cookie
		setcookie($cookiename, $cookiedata, ($this->time_now + $cookietime), '/', $this->cookie_domain);
	}
	/**
	 * Generar un ID de sesión
	 *
	 * @access public
	 * @param void
	*/
	public function gen_session_id() {
		$sessid = '';
		while (strlen($sessid) < 32) {
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= $this->ip_address;
		return md5(uniqid($sessid, TRUE));
	}

	/**
	 * Eliminar sesiones expiradas
	 *
	 * @access	public
	 * @return	void
	*/
	public function sess_gc() {
		// Esto es para no eliminar con cada llamada a esta función
		// sólo si se cumple la siguiente sentencia se eliminan las sesiones
		if ((rand() % 100) < 30) {
			// Usuario sin actividad
			$expire = $this->time_now - $this->sess_time_online;
			db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @sessions WHERE session_time < $expire AND session_autologin = 0");
		  }
	}
}