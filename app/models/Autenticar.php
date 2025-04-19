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

use app\interfaces\AutenticarInterface;
use app\models\Session;

class Autenticar implements AutenticarInterface {

   private Session $session;

   public function __construct() {
		$this->session = new Session;
		$this->setSession();
   }

   /*
		CARGA LA SESSION
		setSession()
	*/
	public function setSession() {
		if (!$this->session->read()) {
			$this->session->create();
		} else {
			$this->session->update();
		}
		return true;
	}

   /**
    * Obtener datos básicos para login
    *
    * @param string $usuario Nombre de usuario o correo
    * @return array|null
    */
   public function getUserLoginData(string $usuario = '') {
      $usuario = db_exec('real_escape_string', $usuario);

      $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_password, user_secret_2fa, user_activo, user_baneado FROM @miembros WHERE user_name = '{$usuario}' OR user_email = '{$usuario}' LIMIT 1"));

      return $data;
   }

   /**
	 * Se repiten en 3 funciones diferentes
	*/
	public function sessionUpdate(int $id = 0, bool $rem = true, ?string $twofactor = null) {
		// Si no tiene el 2fa activo, iniciamos sesión
		if(empty($twofactor)) {
			// Actualizamos la session
			$this->session->update($id, $rem, TRUE);
		}
	}

	private function redirectWithParams() {
		// Redireccionamos en caso que contenga ?redirectTo=xxxx
	   if(isset(parse_url($_SERVER["HTTP_REFERER"])["query"])) {
	   	parse_str(parse_url($_SERVER["HTTP_REFERER"])["query"], $e);
	   	return "5: " . urldecode(base64_decode($e["redirectTo"]));
	   } 
	}

   /**
    * Inicia sesión del usuario
    *
    * @param string $usuario Nombre o correo del usuario
    * @param string $password Contraseña
    * @param bool $recordar Recordar sesión (autologin)
    * @return array
    */
   public function login(string $usuario = '', string $password = '', $recordar = false, $redirectTo = false) {
   	global $tsZCode;
      $user = $this->getUserLoginData($usuario);
     
      if (!isset($user['user_id'])) exit('0: Usuario no encontrado');

      // Validar contraseña
      if(!$tsZCode->createPassword($user['user_name'], $password, $user['user_password'])) exit('2: Tu contrase&ntilde;a es incorrecta.');

      // El usuario esta activo
		if(!(int)$user['user_activo']) exit('3: Debes activar tu cuenta');
			
		$this->sessionUpdate((int)$user['user_id'], $recordar, $user['user_secret_2fa'] ?? null);

		// Comprobando 2FA
		if($user['user_secret_2fa'] === NULL) {
	   	$redireccionar = $this->redirectWithParams();
	   	if($redirectTo || $redireccionar) $tsCore->redirectTo($redireccionar ?? true);
			else return [
	         'success' => true,
	         'user_id' => $user['user_id']
	      ];
	   } else exit('4: Ingrese el código de autentificación.');
      
   }

   /**
    * Cierra la sesión actual
    */
   public function logout() {
   	$this->session = new Session();
		$this->session->read();
		$this->session->destroy();
   }

  	public function getSessionID() {
		return $this->session->ID ?? null;
	}

   public function getTimeNow() {
	   return $this->session->time_now;
	}

   public function getIpAddress() {
	   return $this->session->ip_address;
	}

	public function clearSession() {
		// Borrar variable session
		unset($this->session);
	}

}