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

class tsZCode {

	private $verification;

	private $core;
	
	public function __construct() {
		$this->core = new tsCore;
	}

	/**
	 * Obtiene los tiempos de actividad del usuario
	 *
	 * @return array Array con el tiempo de última actividad online e inactiva
	*/
	public function lastActive(): array {
		$c_last_active = (int)$this->core->settings['c_last_active'] * 60;
		return [
			'online' => time() - $c_last_active,
			'inactive' => time() - ($c_last_active * 2)
		];
	}

	/**
	 * Obtiene el estado de un usuario
	 *
	 * @param int $uid ID del usuario
	 * @return array Array con el estado del usuario y la clase CSS correspondiente
	 */
	public function statusUser(int $uid = 0): array {
		$lastActive = $this->lastActive();
		// Obtiene la información del usuario desde la base de datos
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_lastactive, user_baneado FROM @miembros WHERE user_id = $uid"));
		
		// Determina el estado del usuario basado en la última actividad y si está baneado
		if ((int)$data['user_lastactive'] > $lastActive['online']) {
			$status = 'online';
		} elseif ((int)$data['user_lastactive'] > $lastActive['inactive']) {
			$status = 'inactive';
		} elseif ((int)$data['user_baneado'] > 0) {
			$status = 'banned';
		} else {
			$status = 'offline';
		}
		
		return [
			't' => ucfirst($status),
			'css' => $status
		];
	}

	/**
	 * Obtiene el icono y nombre del país del usuario
	 *
	 * @param string $country Código del país
	 * @return array Array con el icono y nombre del país
	*/
	public function countryUser(string $country = ''): array {
		include TS_ZCODE . "Paises.php";
		return [
			'icon' => strtolower($country ?? 'xx'),
			'name' => !empty($country) ? $tsPaises[$country] : 'unknown'
		];
	}

	public function tagsNew(int $date = 0, int $days = 2) {
		// Obtener la fecha actual como timestamp UNIX
		$currentTimestamp = time();

		// Calcular la diferencia en segundos (2 días = 2 * 24 * 60 * 60)
		$twoDaysInSeconds = $days * 24 * 60 * 60;
		$differenceInSeconds = $currentTimestamp - $date;

		return ($differenceInSeconds < $twoDaysInSeconds) ? '&iexcl;Nuevo!' : '';
	}

   /**
    * Convierte bytes a un formato legible (KB, MB, GB, etc.).
    *
    * @param int $bytes       El tamaño en bytes que se desea formatear.
    * @param int $decimales   El número de decimales para mostrar.
    * @return string          El tamaño formateado en la unidad más apropiada.
   */
	public function formatBytes($bytes, $decimales = 2) {
      $unidad = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
      $factor = floor((strlen($bytes) - 1) / 3);
      $formatted = sprintf("%.{$decimales}f", $bytes / pow(1024, $factor));
      
      return $formatted . ' ' . $unidad[$factor];
   }

	/**
	 * @access public
	 * @description Es solo para comprobar que fue instalado
	*/
	public function verification() {
		$encode = base64_encode(serialize([
   		'KEY' => $_ENV['ZCODE_VERIFY_KEY'],
   		'PIN' => $_ENV['ZCODE_VERIFY_PIN']
   	]));
		return $encode;
	}

	/**
	 * Función para generar la contraseña
	 * y/o verificar la contraseña del usuario
	 * @param string 
	 * @param string 
	 * @return string
	*/
	public function createPassword(string $username = '', string $password = '', string $verify = ''): string|bool {
		$options = ['cost' => 10];
		$pass = trim($username) . trim($password);
		$create_password = htmlspecialchars($pass);
		// Contraseña hasheada
		$hashed = password_hash($create_password, PASSWORD_DEFAULT, $options);
		// Verificar la contraseña si se proporciona un hash para verificar
		if (!empty($verify)) {
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
	   $avatarConfig = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_avatar, p.uavatar_gif, p.uavatar_gif_active FROM @configuracion c JOIN @perfil_avatar p ON p.uavatar_id = '$uid' WHERE c.tscript_id = 1"
	   ));
	   $setAvatar = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT uavatar_type as aType, uavatar_social as aName, uavatar_use FROM @perfil_avatar WHERE uavatar_id = '$uid'"));

	   // Configuración del avatar
	   $avatar_root = "{$this->core->setRoutes('storage', 'avatar')}/user$uid";
	   $image_name = empty($setAvatar['aType']) ? 'web' : $setAvatar['aName'];
	   $avatar_img = "$avatar_root/{$setAvatar['uavatar_use']}.webp";
	   $avatar_gif = $avatarConfig['uavatar_gif'] ?? '';
		//
		return match ($type) {
  		   'use' => ((int)$avatarConfig['uavatar_gif_active'] === 1 && !empty($avatar_gif) ? $avatar_gif : $avatar_img),
  		   'img' => $avatar_img,
  		   'gif' => $avatar_gif,
  		   default => $this->core->setRoutes('assets', 'images') . '/favicon/logo-128.webp',
  		};
	}

	/**
	 * Creates a URL based on the specified type and ID.
	 *
	 * @param string $type  The type of link to create ('post', 'perfil', 'foto').
	 * @param mixed  $id    The ID associated with the link (post ID, user ID, etc.).
	 * @param string $param Additional URL parameters.
	 * @return string The generated URL.
	 */
	public function createLink(string $type = 'post', $id = '', string $param = ''): string {
	   $url = '';
	   $id = (int)$id; // Ensure $id is an integer to prevent SQL injection.

	   switch ($type) {
	      case 'post':
	         $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_title, c_seo FROM @posts LEFT JOIN @posts_categorias ON cid = post_category WHERE post_id = $id"));
	         if ($data) {
	            $url = "/posts/{$data['c_seo']}/{$data['post_id']}/" . $this->core->setSEO($data['post_title'], true) . ".html{$param}";
	         }
	      break;
	      case 'foto':
	         $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_name, foto_id, f_title FROM @miembros LEFT JOIN @fotos ON f_user = user_id WHERE foto_id = $id"));
	         if ($data) {
	            $url = "/fotos/{$data['user_name']}/{$data['foto_id']}/" . $this->core->setSEO($data['f_title'], true) . ".html{$param}";
	         }
	      break;
	      case 'perfil':
	         $url = "/perfil/$id$param";
	      break;
	   }
	   return $this->core->settings['url'] . $url;
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
	   return match ($match) {
	      IMAGETYPE_JPEG => imagecreatefromjpeg($source),
	      IMAGETYPE_PNG => imagecreatefrompng($source),
	      IMAGETYPE_GIF => imagecreatefromgif($source),
	      IMAGETYPE_WEBP => imagecreatefromwebp($source),
	      default => !empty($data) ? die("Tipo de imagen no admitido: $data") : false,
	   };
	}

	public function verifiedIP(&$smarty) {
		$ip = $this->core->executeIP(); 
		if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @blacklist WHERE type = 1 && value = '$ip' LIMIT 1"))) {
			$smarty->assign('tsTitle', 'Bloqueado de ' . $this->core->settings['titulo']);
			$smarty->display('views/bloqueado.html');
		}
	}

	public function verifiedMaintenance(&$smarty) {
		global $tsUser;
		if((int)$this->core->settings['offline'] === 1 && ((int)$tsUser->is_admod === 0 && $tsUser->permisos['govwm'] === NULL) && !in_array($_GET['action'], ['login-user', 'login'])) {
			$smarty->assign('tsTitle', 'En mantenimiento | ' . $this->core->settings['titulo']);
			$smarty->display('views/mantenimiento.html');
			if(!in_array($_GET['action'], ['login-user', 'login'])) exit();
		}
	}

	public function cleanerCacheSQL() {
		$folder = TS_CACHE . 'sql' . DIRECTORY_SEPARATOR;
		$files = glob($folder . '*.json'); // Obtiene todos los archivos .json
		foreach ($files as $file) {
		   if (is_file($file)) {
		      unlink($file); // Elimina el archivo
		   }
		}
	}

}