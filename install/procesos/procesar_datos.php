<?php

if(!$FN->license()) header("Location: ?action=fail");

$faster = [];
if(file_exists(__DIR__ . '/../../config/faster.php')) require_once __DIR__ . '/../../config/faster.php';

if($FN->license() && $FN->request()) {

	$prefix = strtolower($FN->setInput('sql_prefix', 'string', INPUT_POST) ?? 'zc3_');
	$prefix .= (substr($prefix, -1) !== '_') ? '_' : '';

	if($FN->isEmpty('sql_host') || $FN->isEmpty('sql_user') || $FN->isEmpty('sql_name')) {
		$message = "Todos los campos son requeridos";
		$continue = false;
	}

	try {
		$db = [
			'dbhost' => $FN->setInputPost('sql_host'),
			'dbuser' => $FN->setInputPost('sql_user'),
			'dbpass' => $FN->setInputPost('sql_pass'),
			'dbname' => $FN->setInputPost('sql_name'),
			'dbprefix' => $prefix
		];

		$mysqli = new mysqli($db['dbhost'], $db['dbuser'], $db['dbpass'], $db['dbname']);
		if ($mysqli->connect_errno) {
		   $message = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		   $continue = false;
		}
		if (!$mysqli->set_charset("utf8mb4")) {
		  	$message = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
		  	$continue = false;
		}

		# Guardamos los datos en .env
		$FN->save($db);

		# Agregamos todas las consultas a la base de datos
		require_once __DIR__ . '/procesar_database.php';

		if (!empty($success) && $success) {
	
			$usuario = ["admin_username", "admin_userpassword", "admin_confirm", "admin_useremail"];
			require_once __DIR__ . '/procesar_datos_sitio.php';
			if($continue) {
				$user = [
					"nickname" => $FN->setInputPost('admin_username'),
					"password" => $FN->setInputPost('admin_userpassword'),
					"confirm" => $FN->setInputPost('admin_confirm'),
					"email" => $FN->setInputPost('admin_useremail'),
				];
				if (in_array('', $user)) {
					$message = 'Todos los campos son requeridos';
					$continue = false;
				}
				# NOMBRE DE USUARIO SOLO ALFANUMERICA
				if(!ctype_alnum($user['nickname'])) {
			      $message = 'Introduzca un nombre de usuario alfanum&eacute;rico.';
			      $continue = false;
				}
				# VERIFICANDO QUE SEA UN CORREO
				if(!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
			      $message = 'Introduzca un email correcto.';
			      $continue = false;
				}
				# VERIFICANDO QUE LAS CONTRASEÑAS SEAN LA MISMA
				if($user['password'] !== $user['confirm']) {
			      $message = 'Las contrase&ntilde;as no coinciden.';
			      $continue = false;
				}
				# SI ESTA TODO CORRECTO, CONTINUAMOS
				if($continue) {
					# CONTRASEÑA HASHEADA
					$hashed = $FN->generate_password($user['nickname'], $user['password']);
					$time = time();
					if($mysqli->query("SELECT user_id FROM {$prefix}miembros WHERE user_id = 1 OR user_rango = 1 LIMIT 1")->num_rows > 0) {
						$message = 'No se puede registrar, ya existe un administrador.';
			         include __DIR__ . '/../../app/extras/emails/lammer.php';
			         mail('portfoliomiguel92@gmail.com', 'Lammer detectado', $plantilla, 'Content-type: text/html; charset=iso-8859-15');
			      	$continue = false;

					# INSERTAMOS USUARIO
					} else {
						$mysqli->query("INSERT INTO {$prefix}miembros (user_name, user_password, user_email, user_rango, user_registro, user_puntosxdar, user_activo) VALUES ('{$user['nickname']}', '$hashed', '{$user['email']}', 1, $time, 50, 1)");
						$uid = (int)$mysqli->insert_id;

						// CREAMOS EL AVATAR CON LAS INICIALES DEL USUARIO
						$folder = __DIR__ . '/../../storage/avatar/user' . $uid;
						if(!is_dir($folder)) mkdir($folder, 0777, true);
					  	$return_avatar = $folder . DIRECTORY_SEPARATOR . "web.webp";
			    
						# AVATAR ALEATORIO Y CONVIRTIENDO A WEBP
						$origen = __DIR__ . '/../../assets/images/avatares/none/';
						$archivos = scandir($origen);
						$total_imagenes = 0;
						foreach ($archivos as $archivo) {
						   // Incrementar el contador de imágenes
						   if (pathinfo($archivo, PATHINFO_EXTENSION) === 'webp') $total_imagenes++;
						}
					   $avatar = $origen . rand(1, $total_imagenes) . ".webp";
						copy($avatar, $return_avatar);

						# INSERTAMOS NUEVOS DATOS
						$mysqli->query("INSERT INTO {$prefix}perfil (user_id, user_sexo) VALUES ($uid, 'none')");
						$mysqli->query("INSERT INTO {$prefix}perfil_avatar (`uavatar_id`, `uavatar_use`) VALUES ($uid, 'web')");
			         $mysqli->query("INSERT INTO {$prefix}portal (user_id) VALUES ($uid)");
			         # ACTUALIZAMOS ALGUNOS DATOS
			         $mysqli->query("UPDATE {$prefix}posts SET post_user = $uid, post_category = 33, post_date = $time WHERE post_id = 1");
			         $mysqli->query("UPDATE {$prefix}stats SET stats_time_foundation = $time, stats_time_upgrade = $time WHERE stats_no = 1");
						# DAMOS BIENVENIDA POR CORREO
			         include __DIR__ . '/../../app/extras/emails/sitio_creado.php';
			         mail($user['email'], 'Su comunidad ya puede ser usada', $plantilla, 'Content-type: text/html; charset=iso-8859-15');
			         $continue = true;
			       	if($continue && $FN->license()) {
							header("Location: ?action=finalizar&uid=" . $uid);
			       	}
			      }
					
					$mysqli->close();
				}
			}
			
		} else {
			$message = $error;
			$continue = false;
		}

	} catch (Exception $e) {
		$message = $e->getMessage() . ' - code ' . $e->getCode();
		$continue = false;
	}
}