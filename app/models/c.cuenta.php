<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control y edición de la cuenta de usuario
 *
 * @name    c.cuenta.php
 * @author  ZCode | PHPost
 */
require_once TS_ZCODE . "datos.php";
class tsCuenta {

	private function getSocialUser(int $user_id = 0) {
		// Redes viculadas
		$socials = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT social_name as name FROM @miembros_social WHERE social_user_id = $user_id"));
		$array_social = [
			'discord' => false,
			'facebook' => false,
			'github' => false,
			'google' => false
		];
		foreach($socials as $sn) {
    		$name = $sn['name'];
    		if (isset($array_social[$name])) {
        		$array_social[$name] = true;
    		}
		}
		return $array_social;
	}

   /**
    * @name loadPerfil()
    * @access public
    * @uses Cargamos el perfil de un usuario
    * @param int
    * @return array
   */
	public function loadPerfil($user_id = 0){
		global $tsUser;
		//
		if(empty($user_id)) $user_id = $tsUser->uid;
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT p.*, u.user_registro, u.user_lastactive, u.user_outtime_type FROM @perfil AS p LEFT JOIN @miembros AS u ON p.user_id = u.user_id WHERE p.user_id = \''.(int)$user_id.'\' LIMIT 1');
		$perfilInfo = db_exec('fetch_assoc', $query);
		$fecha = "{$perfilInfo['user_dia']}-{$perfilInfo['user_mes']}-{$perfilInfo['user_ano']}";
		$perfilInfo['nacimiento'] = date("Y-m-d", strtotime($fecha));
		// CAMBIOS
      $perfilInfo = $this->unData($perfilInfo);
		$perfilInfo['socials'] = $this->getSocialUser($user_id);
		$custom = explode(';', $perfilInfo['user_customize']);
		$perfilInfo['custom'] = [
			'light' => $custom[0] ?? '#F4F4F4',
			'dark' => $custom[1] ?? '#212121'
		];
		// PORCENTAJE
      $total = safe_unserialize($perfilInfo['p_total']);
		//
		return $perfilInfo;
	}
   /*
       loadExtras()
   */
   private function unData($data){
   	global $redes;
      //
      $data['p_configs'] = safe_unserialize($data['p_configs']);
		// Redes sociales
      $data["redes"] = $redes;
		$data['p_socials'] = ($data['p_socials'] != NULL) ? json_decode($data['p_socials'], true) : [];
		foreach ($redes as $name => $valor) $data['p_socials'][$name];
      //
      return $data;
   }
	/*
		loadHeadInfo($user_id)
	*/
	public function loadHeadInfo(int $user_id = 0){
		global $tsUser, $tsCore;
		// INFORMACION GENERAL
		$new = "u.user_verificado, p.user_gif, p.user_gif_active, p.user_portada, p.user_scheme, p.user_color, p.user_customize, p.p_socials";
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_registro, u.user_lastactive, u.user_activo, u.user_baneado, $new, p.user_sexo, p.user_pais, p.p_nombre, p.p_avatar, p.p_mensaje, p.p_configs FROM @miembros AS u, @perfil AS p WHERE u.user_id = $user_id AND p.user_id = $user_id"));
      //
		$data['avatar'] = $tsCore->getAvatar($user_id, 'use');
      $data['p_nombre'] = $tsCore->setSecure($tsCore->parseBadWords($data['p_nombre']), true);
      $data['p_mensaje'] = $tsCore->setSecure($tsCore->parseBBCode($tsCore->parseBadWords($data['p_mensaje']), 'firma'));
      // Redes Sociales
		if(!empty($data['p_socials'])) {
			$data['p_socials'] = json_decode($data['p_socials'], true);
			foreach ($redes as $name => $valor) $data['p_socials'][$name];
   	}
		$data['p_configs'] = safe_unserialize($data['p_configs']);
		//
		if((int)$data['p_configs']['hits'] == 0){
			$data['can_hits'] = false;
		} elseif((int)$data['p_configs']['hits'] == 3 && ($this->iyfollow($user_id, 'iFollow') || $tsUser->is_admod)){
			$data['can_hits'] = true;
		} elseif((int)$data['p_configs']['hits'] == 4 && ($this->iyfollow($user_id, 'yFollow') || $tsUser->is_admod)){
			$data['can_hits'] = true;
		} elseif((int)$data['p_configs']['hits'] == 5 && $tsUser->is_member){
			$data['can_hits'] = true;
		} elseif((int)$data['p_configs']['hits'] == 6){
			$data['can_hits'] = true;
		}
		// PUEDE RECIBIR VISITAS
		if($data['can_hits']){
			$data['visitas'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT v.*, u.user_id, u.user_name FROM @visitas AS v LEFT JOIN @miembros AS u ON v.user = u.user_id WHERE v.for = \''.(int)$user_id.'\' && v.type = \'1\' && user > 0 ORDER BY v.date DESC LIMIT 7'));
			$q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(u.user_id) AS a FROM @visitas AS v LEFT JOIN @miembros AS u ON v.user = u.user_id WHERE v.for = \''.(int)$user_id.'\' && v.type = \'1\''));
			$data['visitas_total'] = $q1[0];
	      foreach($data['visitas'] as $uid => $user) {
	      	$data['visitas'][$uid]['avatar'] = $tsCore->getAvatar($user['user_id'], 'use');
	      }
      }
      $time = time();
		$this->loadHeadInfoVisitado($user_id);
		$data['stats'] = $this->loadHeadInfoEstadisticas($user_id);
		// BLOQUEADO
		$data['block'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM @bloqueos WHERE b_user = {$tsUser->uid} AND b_auser = $user_id LIMIT 1"));
      //
		return $data;
	}

	public function getAvatarSocials() {
		global $tsCore, $tsUser, $Avatar;
		$uid = (int)$tsUser->uid;
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT social_name, social_avatar, user_avatar_type, user_avatar_social FROM zc_miembros_social LEFT JOIN zc_perfil ON social_user_id = user_id WHERE social_user_id = $uid"));
		foreach($data as $key => $user) {
			if($user['user_avatar_social'] !== 'web' AND $user['user_avatar_type'] > 0) {
				$social = $user['social_name'];
				$folder = "user$uid";
				$Avatar->createAvatarSocial($uid, $social, $user['social_avatar']);
				$data[$key]['social_avatar'] = $tsCore->settings['avatar'] . "/$folder/$social.webp";
			}
		}
		return $data;
	}

	public function activeAvatarSocial() {
		global $tsCore, $tsUser;
		if($tsUser->is_member) {
			$name = $tsCore->setSecure($_POST['name']);
			$active = (int)$_POST['active'] ?? 0;
			if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_avatar_type = $active, user_avatar_social = '$name' WHERE user_id = {$tsUser->uid}")) {
				return '1: Activado correctamente.';
			}
			return '0: Hubo un error al activar';
		}
	}

	public function saveColorCustomizer() {
		global $tsCore, $tsUser;
		if($tsUser->is_member) {
			$light = empty($_POST['light']) ? '#212121' : $tsCore->setSecure($_POST['light']);
			$dark = empty($_POST['dark']) ? '#F4F4F4' : $tsCore->setSecure($_POST['dark']);
			return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_customize = '$light;$dark' WHERE user_id = {$tsUser->uid}"));
		}
		return false;
	}

	private function loadHeadInfoVisitado($user_id) {
		global $tsCore, $tsUser;
		$time = time();
		// YA FUE VISITADO?...
		$ip = $_SERVER['REMOTE_ADDR'];
		$isMember = ($tsUser->is_member) ? "(`user` = {$tsUser->uid} OR `ip` LIKE '$ip')" : "`ip` LIKE '$ip'";
		$visitado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @visitas WHERE `for` = $user_id && `type` = 1 && $isMember LIMIT 1"));
		if(($tsUser->is_member && $visitado == 0 && $tsUser->uid != $user_id) || ($tsCore->settings['c_hits_guest'] == 1 && !$tsUser->is_member && !$visitado)) {
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @visitas (`user`, `for`, `type`, `date`, `ip`) VALUES ({$tsUser->uid}, $user_id, 1, $time, '$ip')");
		} else {
			// Por que razón tenia la variable $post_id?
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @visitas SET `date` = $time, `ip` = '$ip' WHERE `for` = $user_id && `type` = 1");
		}
	}

	private function loadHeadInfoEstadisticas($user_id) {
		global $tsCore;
		// REAL STATS
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_rango, u.user_puntos, u.user_posts, u.user_comentarios, u.user_seguidores, u.user_cache, r.r_name, r.r_color FROM @miembros AS u LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id WHERE u.user_id = $user_id"));
		//
		#if((int)$data['user_cache'] > time() - ((int)$tsCore->settings['c_stats_cache'] * 60)) {
      	// POSTS
        	$q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(post_id) AS p FROM @posts WHERE post_user = $user_id && post_status = 0"));
        	$data['user_posts'] = $q1[0];
        	// SEGUIDORES
        	$q2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS s FROM @follows WHERE f_id =$user_id && f_type = 1"));
			$data['user_seguidores'] = $q2[0];
			// COMENTARIOS
        	$q3 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) AS c FROM @posts_comentarios WHERE c_user = $user_id && c_status = 0"));
			$data['user_comentarios'] = $q3[0];
        	// SEGUIDORES
        	$q4 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS s FROM @follows WHERE f_user = $user_id && f_type = 1"));
			$data['user_seguidos'] = $q4[0];
        	// Amigos
        	$q5 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(f1.follow_id) AS total FROM @follows AS f1 JOIN @follows AS f2 ON f1.f_id = f2.f_user AND f1.f_user = f2.f_id WHERE f1.f_user = $user_id AND f1.f_type = 1 AND f2.f_type = 1;"));
			$data['user_amigos'] = $q5[0];
        	// ACTUALIZAMOS
        	$user = $tsCore->getIUP([
        		'posts' => $q1[0],
        		'comentarios' => $q3[0],
        		'seguidores' => $q2[0],
        		'seguidos' => $q4[0],
        		'amigos' => $q5[0],
        		'cache' => time()
        	], 'user_');
        	db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET $user WHERE user_id = $user_id");
      #}
      $data['user_fotos'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) AS f FROM @fotos WHERE f_user = $user_id && f_status = 0"))[0];
      return $data;
	}

	/*
		loadGeneral($user_id)
	*/
	public function loadGeneral(int $user_id = 0){
		global $tsCore;
		// MEDALLAS
		$data['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, a.* FROM @medallas AS m LEFT JOIN @medallas_assign AS a ON a.medal_id = m.medal_id WHERE a.medal_for = $user_id AND m.m_type = 1 ORDER BY a.medal_date DESC LIMIT 21"));
      $data['m_total'] = safe_count($data['medallas']);
      foreach($data['medallas'] as $mid => $medalla) {
      	$data['medallas'][$mid]['m_image'] = $tsCore->settings['assets'] . "/images/medallas/{$medalla['m_image']}";
      }
		// SEGUIDORES
      $data['seguidores']['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.follow_id, u.user_id, u.user_name FROM @follows AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.f_id = $user_id && f.f_type = 1 && u.user_activo = 1 && u.user_baneado = 0 ORDER BY f.f_date DESC LIMIT 21"));
      $data['seguidores']['total'] = safe_count($data['seguidores']['data']);
      foreach($data['seguidores']['data'] as $uid => $user) {
      	$data['seguidores']['data'][$uid]['avatar'] = $tsCore->getAvatar($user['user_id'], 'use');
      }
		// SIGUIENDO
      $data['siguiendo']['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.follow_id, u.user_id, u.user_name FROM @follows AS f LEFT JOIN @miembros AS u ON f.f_id = u.user_id WHERE f.f_user = $user_id AND f.f_type = 1 && u.user_activo = 1 && u.user_baneado = 0 ORDER BY f.f_date DESC LIMIT 21"));
      $data['siguiendo']['total'] = safe_count($data['siguiendo']['data']);
      foreach($data['siguiendo']['data'] as $uid => $user) {
      	$data['siguiendo']['data'][$uid]['avatar'] = $tsCore->getAvatar($user['user_id'], 'use');
      }
      // ULTIMAS FOTOS
      if(empty($_GET['pid'])){
		  	$data['fotos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT foto_id, f_title, f_url FROM @fotos WHERE f_user = $user_id ORDER BY RAND() DESC LIMIT 6"));
			$data['fotos_total'] = safe_count($data['fotos']);
      }
      //
		return $data;
	}
	/**
	 * Private function
	 * iyfollow() Casi son lo mismo
	*/
	public function iyfollow(int $user_id = 0, string $type = 'iFollow') {
      global $tsUser;

      $id = ($type === 'iFollow') ? $user_id : $tsUser->uid;
      $user = ($type === 'iFollow') ? $tsUser->uid : $user_id;
     
      // SEGUIR
      $data = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM @follows WHERE f_id = $id AND f_user = $user AND f_type = 1 LIMIT 1"));
      return ($data > 0) ? true : false;
   }
   /*
      loadPosts($user_id)
   */
   public function loadPosts(int $user_id = 0){
      global $tsUser, $tsCore;
      $data['posts'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_title, p.post_puntos, c.c_seo, c.c_img, c.c_nombre FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 AND p.post_user = $user_id ORDER BY p.post_date DESC LIMIT 18"));
      $data['total'] = safe_count($data['posts']);
      foreach($data['posts'] as $pid => $post) {
      	$data['posts'][$pid]["post_url"] = $tsCore->createLink('post', $post['post_id']);
         $data['posts'][$pid]['c_img'] = $tsCore->imageCat($post['c_img']);
      }
      // USUARIO
      $data['username'] = $tsUser->getUserName($user_id);
      //
      return $data;
   }
	/*
      loadMedallas($user_id)
   */
   public function loadMedallas(int $user_id = 0){
   	global $tsCore;
      $data['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, a.* FROM @medallas AS m LEFT JOIN @medallas_assign AS a ON a.medal_id = m.medal_id WHERE a.medal_for = $user_id AND m.m_type = 1 ORDER BY a.medal_date DESC"));
      $data['total'] = safe_count($data['medallas']);
      foreach($data['medallas'] as $mid => $medalla) {
      	$data['medallas'][$mid]['m_image'] = $tsCore->settings['assets'] . "/images/medallas/{$medalla['m_image']}";
      }
      return $data;
   }
   public function saveAvatarGif() {
		global $tsCore, $tsUser;
		$avatar = $tsCore->setSecure($_POST['gif']);
      $active = $_POST['active'] === 'false' ? 0 : 1;
      if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET `user_gif_active` = $active, `user_gif` = '$avatar' WHERE user_id = {$tsUser->uid}")) {
      	return '1: Se guardo correctamente';
      }
      return '0: Hubo un error.';
   }

   public function regenerateToken() {
   	global $tsUser;
   	$cadena = '0123456789abcdef';
   	$limit = 3;
   	$count = 0;
   	for ($count = 0; $count < 9; $count++) { 
			$key1 = substr(str_shuffle($cadena), 0, $limit);
			$key2 = substr(str_shuffle($cadena), 0, $limit);
		   $block_code[] = "{$key1}{$key2}";
   	}
		$codes = base64_encode(json_encode($block_code));
	  	if(db_exec([__FILE__, __LINE__], "query", "UPDATE @miembros SET user_recovery = '{$codes}' WHERE user_id = {$tsUser->uid}")) {
	  		return json_encode(["status" => true, "message" => implode(',', $block_code)]);
	  	}
	  	return json_encode(["status" => false, "message" => "No se pudo generar nuevos token"]);
   }

   # Para activar el doble factor
	public function activeTwoFactor() {
		global $tsUser;
		include GOOGLE2FA . "GoogleAuthStart.php";
		$authenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
	  	# Clave secreta
	  	if(!$authenticator->checkCode($_POST['secret'], $_POST['code'])) {
	  		return json_encode(["status" => false, "message" => "No se pudo activar 2FA"]);
	  	}
	  	if(db_exec([__FILE__, __LINE__], "query", "UPDATE @miembros SET user_secret_2fa = '{$_POST['secret']}' WHERE user_id = {$tsUser->uid}")) {
	  		$this->regenerateToken();
	  	} else return json_encode(["status" => false, "message" => "No se guardar"]);
	}

	# Para desactivar el doble factor
	public function removeTwoFactor() {
	  global $tsUser;
	  return (db_exec([__FILE__, __LINE__], "query", "UPDATE @miembros SET user_secret_2fa = '', user_recovery = '' WHERE user_id = {$tsUser->uid}")) ? '1: Ha sido desactivado correctamente.' : '0: Hubo un problema al querer desactivar.';
	}

   public function saveCuenta() {
		global $tsCore, $tsUser;
   	// NUEVOS DATOS
		$nac = explode('-', $_POST['nacimiento']);
		$perfilData = [
			'email' => $tsCore->setSecure($_POST['email'], true),
			'pais' => $tsCore->setSecure($_POST['pais']),
			'estado' => $tsCore->setSecure($_POST['estado']),
			'sexo' => $tsCore->setSecure($_POST['sexo']),
			'dia' => (int)$nac[2],
			'mes' => (int)$nac[1],
			'ano' =>  (int)$nac[0],
			'firma' => $tsCore->setSecure($tsCore->parseBadWords($_POST['firma']), true),
		];
      $year = date("Y", time());
      // ANTIGUOS DATOS
		$info = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_dia, user_mes, user_ano, user_pais, user_estado, user_sexo, user_firma FROM @perfil WHERE user_id = {$tsUser->uid} LIMIT 1"));
		// EMAIL
       $email_ok = $this->isEmail($perfilData['email']);
       // CORRECCIONES
		if(!$email_ok){
			$message = 'El formato de email ingresado no es v&aacute;lido.';
			// EL ANTERIOR
			$perfilData['email'] = $tsUser->info['user_email'];
		// CHEQUEAMOS FECHA DE NACIMIENTO
		} elseif(!checkdate($perfilData['mes'], $perfilData['dia'], $perfilData['ano']) || ($perfilData['ano'] > $year || $perfilData['ano'] < ($year - 100))){
			$message = 'La fecha de nacimiento no es v&aacute;lida.';
			// LOS ANTERIORES
			$perfilData['mes'] = $info['user_mes'];
			$perfilData['dia'] = $info['user_dia'];
			$perfilData['ano'] = $info['user_ano'];
		// SEXO / GÉNERO
		} elseif(empty($perfilData['sexo'])) {
			$message = 'Especifica un g&eacute;nero sexual.';
			$perfilData['sexo'] = $info['user_sexo'];
		// PAÍS
		} elseif(empty($perfilData['pais'])){
			$message = 'Por favor, especifica tu pa&iacute;s.';
			$perfilData['pais'] = $info['user_pais'];
		// ESTADO / PROVINCIA
		} elseif(empty($perfilData['estado'])){
			$message = 'Por favor, especifica tu estado.'.$_POST['estado'];
			$perfilData['estado'] = $info['user_estado'];
		// FIRMA DEL USUARIO
		} elseif(strlen($perfilData['firma']) > 300){
         $message = 'La firma no puede superar los 300 caracteres.';
         $perfilData['firma'] = $info['user_firma'];
       // ES EL MISMO CORREO?
      } elseif($tsUser->info['user_email'] != $perfilData['email']) {
		   $exists = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_email = '{$perfilData['email']}' LIMIT 1"));
		   // EXISTE?...
		   if($exists) {
            $message = 'Este email ya existe, ingresa uno distinto.';
           	$perfilData['email'] = $tsUser->info['user_email'];
         // NO EXISTE?
         } else $message = 'Los cambios fueron aceptados y ser&aacute;n aplicados en los pr&oacute;ximos minutos. NO OBSTANTE, la nueva direcci&oacute;n de correo electr&oacute;nico especificada debe ser comprobada. '.$tsCore->settings['titulo'].' envi&oacute; un mensaje de correo electr&oacute;nico con las instrucciones necesarias';
		}
		
		// Siempre guardaremos el email
		db_exec([__FILE__, __LINE__], "query", "UPDATE @miembros SET user_email = '{$perfilData['email']}' WHERE user_id = {$tsUser->uid}");
		// Eliminaremos el email del array
		array_splice($perfilData, 0, 1);
		// Guardamos los datos restantes
		$updates = $tsCore->getIUP($perfilData, 'user_');
		if(db_exec([__FILE__, __LINE__], "query", "UPDATE @perfil SET {$updates} WHERE user_id = {$tsUser->uid}") OR show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'Base de datos')) $message = 'Los cambios fueron aplicados.';
		return $message;
   }

   public function saveSeguridad() {
		global $tsCore, $tsUser;
   	$passwd = $tsCore->setSecure($_POST['passwd']);
      $new_passwd = $tsCore->setSecure($_POST['new_passwd']);
      $confirm_passwd = $tsCore->setSecure($_POST['confirm_passwd']);
      // Los campos estan vacios?
      if(empty($new_passwd) || empty($confirm_passwd)) 
      	$message = 'Debes ingresar una contrase&ntilde;a.';
      // La nueva contraseña es corta?
      if(strlen($new_passwd) < 5) 
       	$message = 'Contrase&ntilde;a no v&aacute;lida.';
      // Las contraseñas coinciden?
      if($new_passwd != $confirm_passwd) 
      	$message = 'Tu nueva contrase&ntilde;a debe ser igual a la confirmaci&oacute;n de la misma.';
      	// Verificamos que la contraseña sea correcta
      if(!$tsCore->createPassword($tsUser->nick, $passwd, $tsUser->info['user_password'])) 
      	$message = 'Tu contrase&ntilde;a actual no es correcta.';
      // Guardamos la nueva contraseña
      $new_key = $tsCore->createPassword($tsUser->nick, $new_passwd);
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_password = '$new_key' WHERE user_id = {$tsUser->uid}")) $message = 'Tu contrase&ntilde;a se actualizó correctamente.';
		return $message;
   }

   public function savePrivacidad() {
   	global $tsUser;
   	$configs = serialize([
			'm' => (int)$_POST['muro'], 
			'mf' => (((int)$_POST['muro_firm'] > 4) ? 5 : (int)$_POST['muro_firm']), 
			'rmp' => (((int)$_POST['rec_mps'] > 6) ? 5 : (int)$_POST['rec_mps']), 
			'hits' => (((int)$_POST['last_hits'] == 1 || (int)$_POST['last_hits'] == 2) ? 0 : (int)$_POST['last_hits'])
		]);
		if(db_exec([__FILE__, __LINE__], "query", "UPDATE @perfil SET p_configs = '$configs' WHERE user_id = {$tsUser->uid}")) {
			return 'Los cambios fueron aplicados.';
		}
   }

   public function saveNick() {   	
		global $tsCore, $tsUser;
   	$nuevo_nick = $tsCore->setSecure($_POST['new_nick']);
		// Hay un nick en la lista negra?...
		if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @blacklist WHERE type = 4 && value = '$nuevo_nick' LIMIT 1"))) 
      	$message = 'Nick no permitido';          	
      // El nick esta en uso?
      if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_name = '$nuevo_nick' LIMIT 1"))) 
      	$message = 'Nombre en uso';
      // Buscamos al usuario, para verificar si ha hecho un cambio
		$data = db_exec("fetch_assoc", db_exec([__FILE__, __LINE__], "query", "SELECT id, user_id, time FROM @nicks WHERE user_id = {$tsUser->uid} AND estado = 0 LIMIT 1"));
		if($data !== NULL) {
			if(!empty((int)$data['id'])) $message = 'Ya tiene una petici&oacute;n de cambio en curso';
			// Realizamos petición
			elseif(time() - $data['time'] >= 31536000) db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_name_changes = 3 WHERE user_id = {$data['user_id']}");
		}
		// Verificamos la contraseña
		$key = $tsCore->createPassword($tsUser->nick, $_POST['password']);
		$message = 'Tu contrase&ntilde;a actual no es correcta.';
		// Verificamos el correo	
		$email_ok = $this->isEmail($_POST['pemail']);
		if(!$email_ok) 
			return ['field' => 'email', 'error' => 'El formato de email ingresado no es v&aacute;lido.'];
		$email = empty($_POST['pemail']) ? $tsUser->info['user_email'] : $_POST['pemail'];
		// Si el nick tiene más de 4 y menos de 20 carácteres
		if(strlen($nuevo_nick) < 4 || strlen($nuevo_nick) > 20) 
			$message = 'El nick debe tener entre 4 y 20 car&aacute;cteres';
		// Que no tenga espacios, ni carácteres especiales
		if(!preg_match('/^([A-Za-z0-9]+)$/', $nuevo_nick)) 
			$message = 'El nick debe ser alfanum&eacute;rico';
		// Creamos la nueva contraseña
		$key = $tsCore->createPassword($nuevo_nick, $_POST['password']);
		// Verificamos la IP
		$myIP = $tsCore->validarIP();
		if(!filter_var($myIP, FILTER_VALIDATE_IP)) $message = 'Su IP no se pudo validar';
      $datos = [
      	'user_id' => $tsUser->uid, 
      	'user_email' => $tsCore->setSecure($email), 
      	'name_1' => $tsUser->nick, 
      	'name_2' => $nuevo_nick, 
      	'hash' => $key, 
      	'time' => time(), 
      	'ip' => $myIP
      ];
		if(insertDataInBase([__FILE__, __LINE__], '@nicks', $datos)) {
			$message = 'Proceso iniciado, recibir&aacute; la respuesta en el correo indicado cuando valoremos el cambio.';
		}
		return $message;
   }

   public function savePerfil() {
		global $tsCore, $tsUser;

      // INTERNOS
      $sitio = trim($_POST['sitio']);
      if(!empty($sitio)) $sitio = substr($sitio, 0, 4) == 'http' ? $sitio : 'http://'.$sitio;
		// EXTERNAS, Redes sociales
		$redsocial = [];
		foreach ($_POST["red"] as $key => $value) {
			$redsocial[$key] = $tsCore->setSecure($tsCore->parseBadWords($value), true);
		}
		$perfilData = array(
			'nombre' => $tsCore->setSecure($tsCore->parseBadWords($_POST['nombre']), true),
			'mensaje' => $tsCore->setSecure($tsCore->parseBadWords($_POST['mensaje']), true),
			'sitio' => $tsCore->setSecure($tsCore->parseBadWords($sitio), true),
			'socials' => json_encode($redsocial),
		);
		// COMPROBACIONES
      if(!empty($perfilData['sitio']) && !filter_var($perfilData['sitio'], FILTER_VALIDATE_URL)) 
      	$message = 'El sitio web introducido no es correcto.';

		$updates = $tsCore->getIUP($perfilData, 'p_');

		if(db_exec([__FILE__, __LINE__], "query", "UPDATE @perfil SET {$updates} WHERE user_id = {$tsUser->uid}") || show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'Base de datos')) $message = 'Los cambios fueron aplicados.';
	
		return $message;
   }

   public function saveAppearence() {
		global $tsCore, $tsUser;
		$avatar = $tsCore->setSecure($_POST['gif']);
      $active = (int)$_POST['active'];
      // p.user_scheme, p.user_color, 
      if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_avatar_gif = $active, user_avatar = '$avatar' WHERE user_id = {$tsUser->uid}")) {
      	return '1: Se guardo correctamente';
      }
      return '0: Hubo un error.';
   }

   public function saveColorScheme(string $type = 'user_color') {
		global $tsUser;
		$selected = (int)$_POST['selected'];
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET $type = $selected WHERE `user_id` = {$tsUser->uid}")) {
			return true;
		}
		return false;
   }

	/*
		savePerfil()
	*/
	public function saveSettings(string $save = ''){
		// GUARDAR...
		switch ($save) {
			case '':
				return $this->saveCuenta();
			break;
			case 'seguridad':
				return $this->saveSeguridad();
			break;
			case 'privacidad':
				return $this->savePrivacidad();
			break;
			case 'nick':
				return $this->saveNick();
			break;
			case 'perfil':
				return $this->savePerfil();
			break;
		}
	}

	public function getAvatarImages() {
		global $tsCore, $tsUser;
		# ID del usuario
		$uid = (int)$tsUser->uid;
		# Obtenemos el sexo del usuario
		$sexo = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_sexo FROM @perfil WHERE user_id = $uid"))['user_sexo'];

		$search = scandir(TS_AVATARES . $sexo);
		foreach($search as $avatar) {
			if($avatar == '.' || $avatar == '..') continue;
			$image = $tsCore->settings['assets'] . '/images/avatares/'. $sexo .'/' . $avatar;
			$data[] = [
				'id' => pathinfo($image, PATHINFO_BASENAME),
				'image' => $image,
				'avatar' => $avatar
			];
		}
		return $data;
	}

	public function changeAvatar() {
		global $tsCore, $tsUser;
		$image = $tsCore->setSecure($_POST['image']);
		# ID del usuario
		$uid = (int)$tsUser->uid;
		# Obtenemos el sexo del usuario
		$sexo = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_sexo FROM @perfil WHERE user_id = $uid"))['user_sexo'];
		if($tsUser->is_member) {
			$image_new = "user$uid/web.webp";
			$from_avatar = TS_AVATARES . $sexo . TS_PATH . $image;
			copy($from_avatar, TS_AVATAR . TS_PATH . $image_new);
			return $this->settings['avatar'] . "/$image_new";
		}
	}

	/**
    * Verifica si una cadena es una dirección de correo electrónico válida.
    *
    * @param string $email La dirección de correo electrónico a validar.
    * @return bool `true` si la dirección de correo electrónico es válida, `false` en caso contrario.
   */
	public function isEmail(string $email = ''):bool {
    	$regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    	return preg_match($regex, $email) === 1 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}
	
	public function desCuenta() {
		global $tsUser, $tsCore;
		if(db_exec([__FILE__, __LINE__], 'query', 'UPDATE @miembros SET user_activo = 0 WHERE user_id = ' . $tsUser->uid)) $tsCore->redirectTo($tsCore->settings['url'].'/login-salir.php');
	 	return 1;
	}
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
							// MANEJAR BLOQUEOS \\
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function bloqueosCambiar(){
        global $tsCore, $tsUser;
        //
        $auser = $tsCore->setSecure($_POST['user']);
        $bloquear = empty($_POST['bloquear']) ? 0 : 1;
        // EXISTE?
        $exists = $tsUser->getUserName($auser);
        // SI EXISTE Y NO SOY YO
        if($exists && $tsUser->uid != $auser){
            if($bloquear == 1){
                // YA BLOQUEADO?
				$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT bid FROM @bloqueos WHERE b_user = \''.$tsUser->uid.'\' AND b_auser = \''.(int)$auser.'\' LIMIT 1');
                $noexists = db_exec('num_rows', $query);
                
                // NO HA SIDO BLOQUEADO
                if(empty($noexists)) {
				    if(db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @bloqueos (b_user, b_auser) VALUES (\''.$tsUser->uid.'\', \''.(int)$auser.'\')'))
                    return "1: El usuario fue bloqueado satisfactoriamente."; 
                } else return '0: Ya has bloqueado a este usuario.';
                // 
            } else{
			    if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @bloqueos WHERE b_user = \''.$tsUser->uid.'\'  AND b_auser = \''.(int)$auser.'\''))
                return "1: El usuario fue desbloqueado satisfactoriamente.";
            }
        } else return '0: El usuario seleccionado no existe.';
    }
    /*
        loadBloqueos()
    */
   public function loadBloqueos(){
      global $tsUser;
      $data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT b.*, u.user_name FROM @miembros AS u LEFT JOIN @bloqueos AS b ON u.user_id = b.b_auser WHERE b.b_user = ' . (int)$tsUser->uid));
      //
      return $data;
   }
}
