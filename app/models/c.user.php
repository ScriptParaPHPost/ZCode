<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los usuarios
 *
 * @name    c.user.php
 * @author  ZCode | PHPost
 */

class tsUser  {

	// SI EL USUARIO ES MIEMBRO CARGAMOS DATOS DE LA TABLA
	public $info = [];
	
	// EL USUARIO ESTA LOGUEADO?
	public $is_member = 0;
	
	// ES USUARIO ES ADMINISTRADOR
	public $is_admod = 0;
	
	// EL USUARIO ESTA BANEADO
	public $is_banned = 0;

	// NOMBRE A MOSTRAR
	public $nick = 'Anonymous';
	
	// USER ID
	public $uid = 0;
	
	// SI OCURRE UN ERROR ESTA VARIABLE CONTENDRA EL NUMERO DE ERROR
	public $is_error;
	
	public $session;
	
	public $permisos;
	
	public $email;
	
	public $avatar = [];

	public $use_avatar;

	public $avatar_folder;

	// Usado por el login
	public $is_type;
	public $response;

	public function __construct() {
		global $tsCore, $tsMedal;
		/* CARGAR SESSION */
		$this->session = new tsSession();
		$this->setSession();
		// ACTUALIZAR PUNTOS POR DIA :D
		if($this->is_member) $this->puntos_actualizados();
	}

	/*
		CARGA LA SESSION
		setSession()
	*/
	public function setSession() {
		// Si no existe una sessión la creamos, si existe la actualizamos...
		if ( ! $this->session->read()) $this->session->create();
		else {
			// Actualizamos sesión
			$this->session->update();
			// Cargamos información
			$this->loadUser();
		}
	}
	/*
	 * Puntos Actualizados
	*/
	public function puntos_actualizados() {
		// HORA EN LA CUAL RECARGAR PUNTOS 0 = MEDIA NOCHE DEL SERVIDOR
		$ultimaRecarga = $this->info['user_nextpuntos'];
		$tiempoActual = time();
		// SI YA SE PASO EL TIEMPO RECARGAMOS...
		if ($ultimaRecarga < $tiempoActual) {
			// CALCULAR LA SIGUIENTE RECARGA A LAS 24 HRS
			$sigRecarga = strtotime('tomorrow', $tiempoActual);
			// ACTUALIZAR LA BASE DE DATOS
			$puntosxdar = $tsCore->settings['c_keep_points'] == 0 ? $this->permisos['gopfd'] : 'user_puntosxdar + '.$this->permisos['gopfd'];
			db_exec([__FILE__, __LINE__], 'query', 'UPDATE @miembros SET user_puntosxdar = '.$puntosxdar.', user_nextpuntos = '.$sigRecarga.' WHERE user_id = \''.$this->uid.'\'');
			// VAMONOS
			return true;
		}
	}
	
	/*
		DarMedalla()
	*/
	public function DarMedalla(){
		//
		$q1 = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT wm.medal_id FROM @medallas AS wm LEFT JOIN @medallas_assign AS wma ON wm.medal_id = wma.medal_id WHERE wm.m_type = 1 AND wma.medal_for = {$this->uid}"));        
		$q2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS f FROM @follows WHERE f_id = {$this->uid} && f_type = 1"));
		$q3 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS s FROM @follows WHERE f_user = {$this->uid} && f_type = 1"));
		$q4 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) AS c FROM @posts_comentarios WHERE c_user = {$this->uid} && c_status = 0"));
		$q5 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) AS cf FROM @fotos_comentarios WHERE c_user = {$this->uid}"));
		$q6 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) AS fo FROM @fotos WHERE f_status = 0 && f_user = {$this->uid}"));
		$q7 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(post_id) AS p FROM @posts WHERE post_user = {$this->uid} && post_status = 0"));
		  // MEDALLAS
		$datamedal = result_array($query = db_exec([__FILE__, __LINE__], 'query', "SELECT medal_id, m_cant, m_cond_user, m_cond_user_rango FROM @medallas WHERE m_type = 1 ORDER BY m_cant DESC"));
		//		
		foreach($datamedal as $medalla){
			// DarMedalla
			if($medalla['m_cond_user'] == 1 && !empty($this->info['user_puntos']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $this->info['user_puntos']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 2 && !empty($q2[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q2[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 3 && !empty($q3[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q3[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 4 && !empty($q4[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q4[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 5 && !empty($q5[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q5[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 6 && !empty($q7[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q7[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 7 && !empty($q6[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q6[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 8 && !empty($q1) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q1){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_user'] == 9 && !empty($this->info['user_rango']) && $medalla['m_cant'] > 0 && $medalla['m_cond_user_rango'] == $this->info['user_rango']){
				$newmedalla = $medalla['medal_id'];
			}
			//SI HAY NUEVA MEDALLA, HACEMOS LAS CONSULTAS
			if(!empty($newmedalla)) {
				if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT id FROM @medallas_assign WHERE medal_id = \''.(int)$newmedalla.'\' && medal_for = \''.$this->uid.'\''))) {
					db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @medallas_assign (`medal_id`, `medal_for`, `medal_date`, `medal_ip`) VALUES (\''.(int)$newmedalla.'\', \''.$this->uid.'\', \''.time().'\', \''.$_SERVER['REMOTE_ADDR'].'\')');
					db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @monitor (user_id, obj_uno, not_type, not_date) VALUES (\''.$this->uid.'\', \''.(int)$newmedalla.'\', \'15\', \''.time().'\')');
					db_exec([__FILE__, __LINE__], 'query', 'UPDATE @medallas SET m_total = m_total + 1 WHERE medal_id = \''.(int)$newmedalla.'\'');
				}
			}
		}
	}
	/*
		CARGAR USUARIO POR SU ID
		loadUser()
	*/
	public function loadUser($login = FALSE) {
		global $tsCore;
		$time = time();
		// Cargar datos
		$sql = "SELECT u.*, s.* FROM @sessions s, @miembros u WHERE s.session_id = '{$this->session->ID}' AND u.user_id = s.session_user_id";
		$query = db_exec([__FILE__, __LINE__], 'query', $sql);
		$this->info = db_exec('fetch_assoc', $query);

		// Existe el usuario?
		if(!isset($this->info['user_id'])) return FALSE;
		// PERMISOS SEGUN RANGO
		$this->info['rango'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT r_name, r_color, r_image, r_allows FROM @rangos WHERE rango_id = {$this->info['user_id']} LIMIT 1"));
		// PERMISOS SEGUN RANGO
		$datis = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT r_allows FROM @rangos WHERE rango_id = {$this->info['user_rango']} LIMIT 1"));
		$this->permisos = unserialize($datis['r_allows']);
		/* ES MIEMBRO */
		$this->is_member = 1;
		if($this->permisos['sumo'] == false && $this->permisos['suad'] == true) {
			$this->is_admod = 1;
		} elseif($this->permisos['sumo'] == true && $this->permisos['suad'] == false) {
			$this->is_admod = 2;
		} elseif($this->permisos['sumo'] || $this->permisos['suad']) {
			$this->is_admod = true;
		} else {
			$this->is_admod = 0;
		}
	
		// NOMBRE
		$this->nick = $this->info['user_name'];
		$this->uid = $this->info['user_id'];
		$this->email = $this->ProtectedEmail();
		$this->is_banned = $this->info['user_baneado'];
		$this->use_avatar = $tsCore->getAvatar($this->uid, 'use');
		
		$this->avatar = [
			'img' => $tsCore->getAvatar($this->uid, 'img'),
			'gif' => $tsCore->getAvatar($this->uid, 'gif')
		];
		$this->deleteUserOutTime($this->info['user_outtime_type'] ?? 0, $time);
		
		// ULTIMA ACCION
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_lastactive = $time WHERE user_id = {$this->uid}");
		// Si ha iniciado sesión cargamos estos datos.
		if($login) {
			// Last login
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_lastlogin = {$this->session->time_now} WHERE user_id = {$this->uid}");
			/* REGISTAR IP */
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_last_ip = '{$this->session->ip_address}' WHERE user_id = {$this->uid}");
		}
		// Borrar variable session
		unset($this->session);
	}

	public function deleteUserOutTime(int $opcion = 0, int $time = 0) {
		$userId = (int)$this->uid;
		// Validar el userId
    	if ($userId <= 0) {
        	return "0: ID de usuario inválido.";
    	}
    	// Obtener la fecha actual
    	$ahora = time();
	   // Calcular la fecha de eliminación basada en la opción seleccionada
	   if($opcion === 0) {
	   	$outtime = 0;
	   } elseif($opcion >= 1 && $opcion <= 4) {
        	$totime = 3 * $opcion;
         $outtime = strtotime("+$totime months", $ahora);
	   } else {
      	return "0: Opción inválida.";
    	}
	   // Esto detecta si el usuario inicio sesion otra vez 'user_outtime_start = $time'
	   db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_outtime = $outtime, user_outtime_type = $opcion, user_outtime_start = $time WHERE user_id = {$userId}");
	   // Iniciamos proceso para eliminar
	   $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_outtime_type, user_outtime_start, user_outtime FROM @miembros WHERE user_id = {$userId}"));
	   if((int)$data['user_outtime'] !== 0 && (int)$data['user_outtime_type'] !== 0) {
	    	// Verificar si se debe proceder con la eliminación
	    	if ($data && (int)$data['user_outtime'] >= (int)$data['user_outtime_start'] && (int)$data['user_outtime'] === $ahora) {
		   	// Acá aplicamos consulta para eliminar cuenta
		   	$this->deleteContent($userId);
		   }
	   }
	   return "1: Guardado correctamente";
	}

	private function deleteContent(int $user_id = 0){
		global $tsCore, $tsUser;
		
		$tablas = [
			['@posts', "post_user"],
			['@fotos', "f_user"],
			['@muro', "p_user_pub"],
			['@posts_comentarios', "c_user"],
			['@fotos_comentarios', "c_user"],
			['@muro_comentarios', "c_user"],
			['@muro_likes', "user_id"],
			['@follows', "f_id"],
			['@follows', "f_user"],
			['@posts_favoritos', "fav_user"], 
			['@posts_votos', "tuser"],
			['@fotos_votos', "v_user"],
			['@actividad', "user_id"],
			['@avisos', "user_id"],
			['@bloqueos', "b_user"],
			['@mensajes', "mp_from"], 
			['@respuestas', "mr_from"],
			['@sessions', "session_user_id"],
			['@visitas', "user"],
			['@miembros', "user_id"],
			['@perfil', "user_id"],
			['@portal', "user_id"],
			['@denuncias', "d_user"],
			['@bloqueos', "b_auser"],
			['@mensajes', "mp_to"],
			['@visitas', "`for`"]
		];
		foreach($tablas as $k => $tabla) deleteFromId([__FILE__, __LINE__], $tabla[0], "{$tabla[1]} = $user_id");
		$data = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT user_name FROM @miembros WHERE user_id = $user_id"));
		$admin = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT user_email FROM @miembros WHERE user_id = 1"));
		  
		$avBody = "Hola, le informamos su cuenta ha sido eliminada con todo su contenido por inactividad elegida por {$data['user_name']}.";
		include_once TS_MODELS . 'c.emails.php';
		$tsEmail = new tsEmail(); 

		$tsEmail->emailTemplate = 'delete';
		$tsEmail->emailTo = $admin[0];
		$tsEmail->emailSubject = 'Cuenta eliminada';
		$tsEmail->emailBody = "Tu cuenta ha sido eliminada!<br>$avBody";
		$tsEmail->sendEmail() or die('0: Hubo un error al intentar procesar lo solicitado');
		return true;
	}

	private function ProtectedEmail() { 
		$charrandom = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$random = str_shuffle($charrandom); 
		$text = ''; 
		$email = $this->info['user_email'];
		for ( $i = 0; $i < strlen($email); $i += 1) $text .= $random[strpos($charrandom, $email[$i])];
		$data = [
			'key'	=>	$random,
			'public' => $text
		];
		return $data;
	}

	/*
	 * 
	*/
	public function unlinkAccount() {
		global $tsCore;
		# Buscamos para desactivar
		$delete = $tsCore->setSecure($_POST['social']);
		if($this->is_member) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, m.social_id, m.social_name FROM @miembros AS u LEFT JOIN @miembros_social AS m ON m.social_user_id = u.user_id WHERE u.user_id = {$this->uid} AND m.social_name = '$delete' LIMIT 1"));
			$sid = (int)$data['social_id'];
			// Actualizamos la tabla
			return (db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @miembros_social WHERE social_id = $sid AND social_name = '$delete' AND social_user_id = {$this->uid}")) ? true : false;
		}
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
		// Cargamos la información del usuario
		$this->loadUser(true);
		// COMPROBAMOS SI TENEMOS QUE ASIGNAR MEDALLAS
		$this->DarMedalla();
	}

	/*
		HACEMOS LOGIN
		loginUser($username, $password, $remember = false, $redirectTo = NULL);
	*/
	function loginUser(string $username = '', string $password = '', bool $remember = false, bool $redirectTo = false){
		global $tsCore;
		/* ARMAR VARIABLES */
		$filter = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		/* CONSULTA */  
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_password, user_secret_2fa, user_activo, user_baneado FROM @miembros WHERE user_$filter = '$username' LIMIT 1"));
		// Existe el usuario
		if(empty($data)) return '0: El usuario no existe.';
		// Solo cuando inicia sesion, no cuando activa la cuenta
		if(!$tsCore->createPassword($data['user_name'], $password, $data['user_password'])) return '2: Tu contrase&ntilde;a es incorrecta.';
		// El usuario esta activo
		if((int)$data['user_activo'] === 0) return '3: Debes activar tu cuenta';
		// Comprobando 2FA
		$this->sessionUpdate($data['user_id'], $remember, $data['user_secret_2fa'] ?? '');
		if($data['user_secret_2fa'] === NULL) {
	   	// Redireccionamos en caso que contenga ?redirectTo=xxxx
	   	if(isset(parse_url($_SERVER["HTTP_REFERER"])["query"])) {
	   		parse_str(parse_url($_SERVER["HTTP_REFERER"])["query"], $e);
	   		return "5: " . urldecode(base64_decode($e["redirectTo"]));
	   	} 
	   	if($redirectTo) $tsCore->redirectTo(true);
			else return TRUE;
	   } else return '4: Ingrese el código de autentificación.';
	}

	/**
	 * Función para validar el código de autentificación 
	*/
	public function validateTwoFactor() {
		global $tsCore;

		include GOOGLE2FA . "GoogleAuthStart.php";
		$authenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

		$nick = $tsCore->setSecure($_POST['nick']);
		$rem = ($_POST['rem'] === 'true');
		# Buscar datos del usuario
		$data = db_exec('fetch_assoc', db_exec(array(__FILE__, __LINE__), 'query', "SELECT user_id, user_secret_2fa, user_recovery FROM @miembros WHERE user_name = '$nick'"));
		$recovery = json_decode(base64_decode($data["user_recovery"]), true);
		if($authenticator->checkCode($data['user_secret_2fa'], $_POST['code']) || in_array($_POST['code'], $recovery)) {
		   $this->session->update($data['user_id'], $rem, TRUE);
		   return '1: Código 2FA correcto.';
		} 
		return '0: No se pudo comprobar la doble autentificación.';		
	}

	/*
		CERRAR SESSION
		logoutUser($redirectTo)
	*/
	public function logoutUser(int $user_id = 0, bool $redirectTo = false){
		global $tsCore;
		/* BORRAR SESSION */
		$this->session = new tsSession();
		$this->session->read();
		$this->session->destroy();
		/* LIMPIAR VARIABLES */
		$this->info = '';
		$this->is_member = 0;
		# UPDATE
		$last_active = ((int)$tsCore->settings['c_last_active'] * 60);
		$last_active = time() - ($last_active * 3);
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_lastactive = $last_active WHERE user_id = $user_id");
		/* REDERIGIR */
		if($redirectTo) header("Location: {$tsCore->settings['url']}");	// REDIRIGIR
		return true;
	}
	/*
		userActivate()
	*/
	public function userActivate(int $tsUserID = 0, string $tsKey = '') {
	   global $tsCore;
	   // Obtener userID y key de $_GET si no se proporcionan
	   if ($tsUserID === 0) $tsUserID = (int)$_GET['uid'];
	   if (empty($tsKey)) $tsKey = $tsCore->setSecure($_GET['key']);
	   // Consulta para obtener datos del usuario
	   $query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_name, user_password, user_registro FROM @miembros WHERE user_id = $tsUserID LIMIT 1");
	   $tsData = db_exec('fetch_assoc', $query);
	   // Verificar si se encontraron datos y si la clave coincide
	   if ($tsData && $tsKey === md5($tsData['user_registro'])) {
	      // Actualizar el estado del usuario a activo
	      if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_activo = 1 WHERE user_id = $tsUserID")) {
	         return $tsData;
	      }
	   }
	   return false;
	}
	/*
		getUserBanned()
	*/
	public function getUserBanned() {
		$uid = (int)$this->uid;
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT susp_id, user_id, susp_causa, susp_date, susp_termina, susp_mod, susp_ip FROM @suspension WHERE user_id = $uid LIMIT 1"));
		$now = time();
		if((int)$data['susp_termina'] > 1 && (int)$data['susp_termina'] < $now){
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_baneado = 0 WHERE user_id = $uid");
			db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @suspension WHERE user_id = $uid");
			return false;
		} else return $data;
	}
	/*
		getUserID($tsUsername)
	*/
	public function getUserID(string $tsUser = ''): int {
		global $tsCore;
		$tsUser = $tsCore->setSecure($tsUser);
		$tsUser = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_name = '$tsUser' LIMIT 1"));
		$tsUserID = (int)$tsUser['user_id'] ?? 0;
		return $tsUserID;
	}
	/*
		  getUserName($user_id)
	 */
	public function getUserName(int $user_id = 0): string {
		$tsUser = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_name FROM @miembros WHERE user_id = $user_id LIMIT 1"));
		return $tsUser['user_name'];
	}
	/*
		  getUserIsVerified($user_nick)
	 */
	public function getUserIsVerified(string $user_name = ''): bool {
		$tsUser = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_verificado FROM @miembros WHERE user_name = '$user_name' LIMIT 1"));
		return ((int)$tsUser['user_verificado'] === 1);
	}
	/*
		  getUserName($user_id)
	 */
	public function getUserRango(int $user_id = 0, string $type = 'r_name') {
		$UserRango = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT rango_id, r_name, r_color, r_image, user_rango FROM @rangos LEFT JOIN @miembros ON user_rango = rango_id WHERE user_id = $user_id LIMIT 1;"));
		return $UserRango[$type];
	}
	/**
	 * @name iFollow
	 * @access public
	 * @param int
	 * @return void
	 */
	public function iFollow(int $user_id = 0): bool {
		# SIGO A ESTE USUARIO
		$data = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM @follows WHERE f_id = $user_id AND f_user = {$this->uid} AND f_type = 1 LIMIT 1"));
		//
		return ($data > 0) ? true : false;
	}

	public function isUserBloqued(int $b_user = 0, int $b_auser = 0) {
		db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT bid, b_user, b_auser FROM @bloqueos WHERE b_user = $b_user AND b_auser = $b_auser LIMIT 1"));
	}

	/*
		getUsuarios()
	*/
	public function getUsuarios(){
		global $tsCore;
		// FILTROS ||
		$filter = '';
		$active = $tsCore->lastActive();
		foreach($_GET as $newVar => $valueOfGet) $$newVar = $tsCore->setSecure($valueOfGet);
		// ONLINE?
		if($online === 'true') $filter .= "AND u.user_lastactive > {$active['online']}";
		// CON FOTO O SIN FOTO
		if(!empty($avatar)) $filter .= 'AND p.p_avatar = ' . ($avatar === 'true' ? 1 : 0);
		// SEXO
		if(!empty($sexo)) $filter .= "AND p.user_sexo = '$sexo'";
		// PAIS
		if(!empty($pais)) $filter .= "AND p.user_pais = '$pais'";
		// STAFF
		if(!empty($rango)) $filter .= "AND u.user_rango = $rango";
		// TOTAL Y PAGINAS
		$total = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(u.user_id) AS total FROM @miembros AS u LEFT JOIN @perfil AS p ON u.user_id = p.user_id WHERE u.user_activo = 1 && u.user_baneado = 0 $filter"));
		$total = $total['total'];
		  
		$pages = $tsCore->getPagination($total, 12);
		// CONSULTA
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, p.user_pais, p.user_sexo, p.p_avatar, p.p_mensaje, u.user_rango, u.user_puntos, u.user_comentarios, u.user_posts, u.user_lastactive, u.user_baneado, r.r_name, r.r_color, r.r_image FROM @miembros AS u LEFT JOIN @perfil AS p ON u.user_id = p.user_id LEFT JOIN @rangos AS r ON r.rango_id = u.user_rango WHERE u.user_activo = 1 && u.user_baneado = 0 $filter ORDER BY u.user_id DESC LIMIT {$pages['limit']}");
		// PARA ASIGNAR SI ESTA ONLINE HACEMOS LO SIGUIENTE
		$SVG_FLAGS_ALL = json_decode(file_get_contents(TS_ASSETS . 'icons/flags.json'), true);
		while($row = db_exec('fetch_assoc', $query)) {
			$row['status'] = $tsCore->statusUser($row['user_id']);
			// RANGO
			$row['rango'] = [
				'title' => $row['r_name'], 
				'color' => $row['r_color'], 
				'image' => $tsCore->settings['assets'] . "/images/rangos/{$row['r_image']}"
			];
			$row['pais'] = strtolower($row['user_pais'] ?? 'xx');
			$row['pais_image'] = $SVG_FLAGS_ALL[$row['pais']];
			$row['avatar'] = $tsCore->getAvatar($row['user_id'], 'use');
			// CARGAMOS
			$data[] = $row;
		}
		// ACTUALES
		$total = explode(',', $pages['limit']);
		$total = ($total[0]) + safe_count($data);
		//
		return array('data' => $data, 'pages' => $pages, 'total' => $total);
	}
		

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
}

// --------------------------------------------------------------------

class tsSession {

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
		$this->ID = $_COOKIE[$this->cookie_name . '_sid'];
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