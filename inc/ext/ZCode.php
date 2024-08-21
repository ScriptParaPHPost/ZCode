<?php 

/**
 * @name ZCode.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Crearemos algunas funciones para uso global
**/

class tsZCode {

	// No quitar, ni reemplazar
	private $keygen = 'WkNvZGVVcGdyYWRl';

	private $verification;
	
	public function __construct() {
		# code...
	}
	
	public function getEndPoints(string $social = '', string $type = '') {
		$getEndPoints = [
			'github' => [
				'authorize_url' => 'https://github.com/login/oauth/authorize',
				'token' => "https://github.com/login/oauth/access_token",
				'user' => "https://api.github.com/user",
				'revoke' => "",
				'scope' => "user"
			],
			'discord' => [
				'authorize_url' => 'https://discord.com/oauth2/authorize',
				'token' => "https://discord.com/api/oauth2/token",
				'user' => "https://discord.com/api/v10/users/@me",
				'revoke' => "https://discord.com/api/oauth2/token/revoke",
				'scope' => "email identify"
			],
			'google' => [
				'authorize_url' => 'https://accounts.google.com/o/oauth2/auth',
				'token' => "https://accounts.google.com/o/oauth2/token",
				'user' => "https://www.googleapis.com/oauth2/v2/userinfo",
				'scope' => "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
			],
			'facebook' => [
				'authorize_url' => 'https://www.facebook.com/v18.0/dialog/oauth',
				'token' => "https://graph.facebook.com/oauth/access_token",
				'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
				'scope' => "email,public_profile"
			],
			'twitter' => [
				'authorize_url' => 'https://api.twitter.com/oauth/authenticate',
				'token' => "https://api.twitter.com/oauth/access_token",
				'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
				'scope' => "email,public_profile"
			]
		];
		return $getEndPoints[$social][$type];
	}

	public function OAuth() {
		$OAuths = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_id, social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social'));
		foreach($OAuths as $k => $auth) {
			$parametros = [
				'client_id' => $auth['social_client_id'],
				'scope' => $this->getEndPoints($auth['social_name'], 'scope'),
				//'state' => strtolower($this->settings['titulo']).date('y'),
				'response_type' => 'code',
				'redirect_uri' => $auth['social_redirect_uri']
			];
			if($auth['social_name'] === 'github') unset($parametros['response_type']);
			$parametros = http_build_query($parametros);
			$authorize = $this->getEndPoints($auth['social_name'], 'authorize_url');
			$ruta[$auth['social_name']] = "$authorize?$parametros";
		}
		return $ruta;
	}
	/**
	 * @access public
	 * @description Es solo para comprobar que fue instalado
	*/
	public function verification() {
		$encode = base64_encode("{$this->settings['url']} - {$this->settings['version']} - {$this->keygen}");
		return $encode;
	}

	/**
	 * Función para generar la contraseña
	 * y/o verificar la contraseña del usuario
	 * @param string 
	 * @param string 
	 * @return string
	*/
	public function createPassword(string $username = '', string $password = '', string $verify = '') {
		$options = ['cost' => 10];
		$create_password = htmlspecialchars(trim($username)) . htmlspecialchars(trim($password));
		# CONTRASEÑA HASHEADA
		$hashed = password_hash($create_password, PASSWORD_DEFAULT, $options);
		if(!empty($verify)) {
			return password_verify($create_password, $verify);
		}
		return $hashed;
	}

   public function nobbcode($nobbcode = '') {
    	// Elimina los códigos BBcodes
    	$nobbcode = preg_replace('/\[([^\]]*)\]/', '', $nobbcode); 
    	// Elimina las URLs
    	$nobbcode = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', ' ', $nobbcode);
    	return $nobbcode;
	}


	public function truncate($string = '', $can = NULL){
		$stc = ($can == '') ? 150 : $can;
		$str = wordwrap($string, $stc);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = explode("\n", $str);
		$str = $str[0] . '...';
		return $str;
	}

	public function getAvatar(int $uid = 0, string $type = 'img') {
		// Verificamos si el gif es global
		$query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_avatar, p.user_id, p.user_gif, p.user_gif_active, p.p_avatar FROM @configuracion c, @perfil p WHERE c.tscript_id = 1 AND p.user_id = '$uid'"));
		//
		$who = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_avatar_type as aType, user_avatar_social as aName FROM @perfil WHERE user_id = '$uid'"));
		// Avatar
		$avatar_root = "{$this->settings['avatar']}/user$uid";
		$image_name =  ($who['aType'] === 0 OR empty($who['aType'])) ? 'web' : $who['aName'];
		$avatar_img_type = "$avatar_root/$image_name" . uniqid('.webp?');
		$avatar_img = "$avatar_root/web" . uniqid('.webp?');
		$avatar_gif = $query['user_gif'];
		//
		switch ($type) {
			case 'use':
				return ((int)$query['user_gif_active'] === 1 && !empty($avatar_gif) ? $avatar_gif : $avatar_img_type);
			break;
			case 'img':
				return $avatar_img;
			break;
			case 'gif':
				return $avatar_gif;
			break;
			default:
				return $this->settings['images'] . '/favicon/logo-128.webp';
		}

	}

	public function createLink(string $type = 'post', array $isArray = [], string $param = ''): string {
	   // Verificar si los datos necesarios están presentes en el array
	   if (empty($isArray) || !isset($this->settings['url'])) {
	      throw new InvalidArgumentException('Los parámetros necesarios no están presentes.');
	   }
	   switch ($type) {
	      case 'post':
	         if (!isset($isArray['c_seo'], $isArray['post_id'], $isArray['post_title'])) {
	            throw new InvalidArgumentException('Los parámetros necesarios para el tipo "post" no están presentes.');
	         }
	         $post_category = $isArray['c_seo'];
	         $post_id = $isArray['post_id'];
	         $post_title = $this->setSEO($isArray['post_title'], true);
	         $urlCreate = "/posts/$post_category/$post_id/$post_title.html$param";
	      break;
	      case 'perfil':
	         if (!isset($isArray['nick'], $isArray['extra'])) {
	            throw new InvalidArgumentException('Los parámetros necesarios para el tipo "perfil" no están presentes.');
	         }
	         $urlCreate = "/perfil/{$isArray['nick']}{$isArray['extra']}";
	      break;
	      case 'foto':
	         if (!isset($isArray['user_name'], $isArray['foto_id'], $isArray['f_title'])) {
	            throw new InvalidArgumentException('Los parámetros necesarios para el tipo "foto" no están presentes.');
	         }
	         $user_name = $isArray['user_name'];
	         $foto_id = $isArray['foto_id'];
	         $foto_title = $this->setSEO($isArray['f_title'], true);
	         $urlCreate = "/fotos/$user_name/$foto_id/$foto_title.html$param";
	      break;
	      default:
	        	throw new InvalidArgumentException('Tipo de enlace desconocido: ' . $type);
	   }
	   return $this->settings['url'] . $urlCreate;
	}

	public function readingTime(string $content = '', int $wpm = 250 ) {
		// Eliminar los BBCode usando una expresión regular
		$content = $this->nobbcode($content);
	  	// Contar las palabras después de eliminar los BBCode y URLs
	  	$word_count = str_word_count($content);
		// Calcular el tiempo estimado de lectura en minutos
	   $total_minutes = $word_count / $wpm;
	   // Calcular el tiempo en minutos y segundos
	   $minutes = floor($total_minutes);
	   $seconds = round(($total_minutes - $minutes) * 60);
	   // Formatear el resultado
	   if ($minutes > 0) {
	      $reading_time = "Tiempo de lectura {$minutes}";
	      $reading_time .= ($seconds > 0 ? ":{$seconds} " : "") . " min";
	   } else {
	      $reading_time = "Tiempo de lectura {$seconds} segundos";
	   }

	   return $reading_time;
	}

	public function getFormatImage($match, $source, $data = '') {
		// Create an image resource from the source image
		switch ($match) {
			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($source);
			break;
			case IMAGETYPE_PNG:
				return imagecreatefrompng($source);
			break;
			case IMAGETYPE_GIF:
				return imagecreatefromgif($source);
			break;
			case IMAGETYPE_WEBP:
				return imagecreatefromwebp($source);
			break;
			
			default:
				if(!empty($data)) die("Tipo de imagen no admitido: $data");
        		return false;
			break;
		}
	}

}