<?php 

/**
 * @name ZCode.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Crearemos algunas funciones para uso global
**/

class tsZCode {

	private $verification;
	
	public function __construct() {
		# code...
	}
	
	public function getEndPoints(string $social = '', string $type = '') {
		$getEndPoints = [
			'github' => [
				'authorize' => 'https://github.com/login/oauth/authorize',
				'token' => "https://github.com/login/oauth/access_token",
				'revoke' => "",
				'user' => "https://api.github.com/user",
				'scope' => "user"
			],
			'discord' => [
				'authorize' => 'https://discord.com/oauth2/authorize',
				'token' => "https://discord.com/api/oauth2/token",
				'revoke' => "https://discord.com/api/oauth2/token/revoke",
				'user' => "https://discord.com/api/v10/users/@me",
				'scope' => "email identify"
			],
			'google' => [
				'authorize' => 'https://accounts.google.com/o/oauth2/auth',
				'token' => "https://accounts.google.com/o/oauth2/token",
				'user' => "https://www.googleapis.com/oauth2/".GOOGLEVERSION."/userinfo",
				'revoke' => "",
				'scope' => "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
			],
			'facebook' => [
				'authorize' => "https://www.facebook.com/".FBVERSION."/dialog/oauth",
				'token' => "https://graph.facebook.com/oauth/access_token",
				'revoke' => "",
				'user' => "https://graph.facebook.com/".FBVERSION."/me?fields=id,name,email,picture,short_name",
				'scope' => "email,public_profile"
			],
			'reddit' => [
				'authorize' => "https://ssl.reddit.com/api/".REDDITVERSION."/authorize",
				'token' => "https://ssl.reddit.com/api/".REDDITVERSION."/access_token",
				'revoke' => "",
				'user' => "https://www.reddit.com/user/",
				'scope' => "identity"
			]
		];
		return $getEndPoints[$social][$type];
	}

	/**
	 * Genera URLs de autorización OAuth para diferentes proveedores sociales.
	 *
	 * @return array Un array asociativo con el nombre del proveedor como clave y la URL de autorización como valor.
	 */
	public function OAuth(string $redirect = ''): array {
	   // Obtener la lista de proveedores OAuth
	   $OAuths = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social'));
	   $routes = [];
	    
	   foreach ($OAuths as $auth) {
	      // Preparar los parámetros para la solicitud OAuth
	      $parameters['client_id'] = $auth['social_client_id'];
	      $parameters['scope'] = $this->getEndPoints($auth['social_name'], 'scope');
	      $parameters['response_type'] = ($auth['social_name'] === 'github') ? '' : 'code';
	      $parameters['redirect_uri'] = $auth['social_redirect_uri'];
	      // Eliminar el parámetro response_type si es 'github'
	      if ($auth['social_name'] === 'github') {
	         unset($parameters['response_type']);
	      }
	      if(in_array($auth['social_name'], ['google', 'discord'])) {
				$parameters['prompt'] = 'consent';
			}
	      // Construir la URL de autorización
	      $queryString = http_build_query(array_filter($parameters));
	      $authorizeUrl = $this->getEndPoints($auth['social_name'], 'authorize');
	      $routes[$auth['social_name']] = "$authorizeUrl?$queryString";
	   }
	   return $routes;
	}

	/**
	 * @access public
	 * @description Es solo para comprobar que fue instalado
	*/
	public function verification() {
		$encode = base64_encode("{$this->settings['url']} - " . SCRIPT_VERSION . " - " . SCRIPT_KEY);
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

	/**
	 * Elimina BBcodes y URLs de una cadena de texto.
	 *
	 * @param string $text La cadena de texto de la que se eliminarán los BBcodes y URLs.
	 * @return string La cadena de texto sin BBcodes ni URLs.
	 */
	public function nobbcode(string $text = ''): string {
	   // Elimina los códigos BBcodes
	   $text = preg_replace('/\[.*?\]/', '', $text);
	   // Elimina las URLs
	   $text = preg_replace('@https?://[^\s]+@', ' ', $text);
	   return trim($text);
	}

	/**
	 * Trunca una cadena de texto a una longitud específica y añade puntos suspensivos al final.
	 *
	 * @param string $string La cadena de texto que se va a truncar.
	 * @param int|null $length La longitud máxima de la cadena truncada. Si es null, se usa 150 como valor predeterminado.
	 * @return string La cadena truncada con puntos suspensivos al final.
	 */
	public function truncate(string $string = '', int $length = 150): string {
	   // Usa la longitud proporcionada o el valor por defecto
	   $length = $length <= 0 ? 150 : $length;
	   
	   // Envuelve la cadena en líneas de longitud máxima
	   $wrapped = wordwrap($string, $length, "\n", true);
	   
	   // Toma la primera línea y añade puntos suspensivos si es necesario
	   $truncated = explode("\n", $wrapped)[0] . '...';

	   return $truncated;
	}


	/**
	 * Obtiene el avatar de un usuario según el tipo especificado.
	 *
	 * @param int $uid El ID del usuario cuyo avatar se va a obtener.
	 * @param string $type El tipo de avatar a obtener. Puede ser 'img', 'gif', o 'use'.
	 *                     - 'use': Devuelve el GIF si está activo, de lo contrario, la imagen webp.
	 *                     - 'img': Devuelve la imagen webp del avatar.
	 *                     - 'gif': Devuelve el GIF del avatar.
	 * @return string La URL del avatar del usuario.
	 */
	public function getAvatar(int $uid = 0, string $type = 'img'): string {
	   // Consultas para obtener los datos del avatar
	   $query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_avatar, user_gif, user_gif_active FROM @configuracion c JOIN @perfil p ON p.user_id = '$uid' WHERE c.tscript_id = 1"
	   ));
	   $who = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_avatar_type as aType, user_avatar_social as aName FROM @perfil WHERE user_id = '$uid'"));

	   // Configuración del avatar
	   $avatar_root = "{$this->settings['avatar']}/user$uid";
	   $image_name = empty($who['aType']) ? 'web' : $who['aName'];
	   $avatar_img_type = "$avatar_root/$image_name" . uniqid('.webp?');
	   $avatar_img = "$avatar_root/web" . uniqid('.webp?');
	   $avatar_gif = $query['user_gif'] ?? '';
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

	/**
	 * Creates a URL based on the specified type and ID.
	 *
	 * @param string $type  The type of link to create ('post', 'perfil', 'foto').
	 * @param mixed  $id    The ID associated with the link (post ID, user ID, etc.).
	 * @param string $param Additional URL parameters.
	 * @return string The generated URL.
	 */
	public function createLink(string $type = 'post', mixed $id = null, string $param = ''): string {
	   $url = '';
	   $id = (int)$id; // Ensure $id is an integer to prevent SQL injection.

	   switch ($type) {
	      case 'post':
	         $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_title, c_seo FROM @posts LEFT JOIN @posts_categorias ON cid = post_category WHERE post_id = $id"));
	         if ($data) {
	            $url = "/posts/{$data['c_seo']}/{$data['post_id']}/" . $this->setSEO($data['post_title'], true) . ".html{$param}";
	         }
	      break;
	      case 'foto':
	         $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_name, foto_id, f_title FROM @miembros LEFT JOIN @fotos ON f_user = user_id WHERE foto_id = $id"));
	         if ($data) {
	            $url = "/fotos/{$data['user_name']}/{$data['foto_id']}/" . $this->setSEO($data['f_title'], true) . ".html{$param}";
	         }
	      break;
	      case 'perfil':
	         $url = "/perfil/$id$param";
	      break;
	   }
	   return $this->settings['url'] . $url;
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