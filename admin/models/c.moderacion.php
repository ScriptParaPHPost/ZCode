<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de las funciones de la moderación
 *
 * @name    c.moderacion.php
 * @author  ZCode | PHPost
 */
class tsMod {

	public function multiAction(string $action = '') {
		global $tsCore;
		if($action === 'ocultar') {
			var_dump($_POST);
			return $this->OcultarPost($_POST['pid'], $tsCore->setSecure($_POST['razon']));
		} elseif($action === 'reboot') {
			return $this->rebootPost($_POST['id']);
		} elseif($action === 'sticky') {
			return $this->setSticky($_POST['id']);
		} elseif($action === 'openclosed') {
			return $this->setOpenClosed($_POST['id']);
		}
	}

	/*
	 getMods()
	*/
	public function getMods() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name FROM @miembros WHERE user_rango = 2 ORDER BY user_id"));
		return $data;
	}

	/*
	 getDenuncias()
	*/
	public function getDenuncias($type = 'posts') {
		global $tsCore;
		// TIPO DE DENUNCIAS
		switch ($type) {
			case 'posts':
				$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT r.*, SUM(d_total) AS total, p.post_id, p.post_title, p.post_status, c.c_nombre, c.c_seo, c.c_img FROM @denuncias AS r LEFT JOIN @posts AS p ON r.obj_id = p.post_id LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE d_type = 1 AND p.post_status < 2 GROUP BY r.obj_id ORDER BY total DESC, r.d_date DESC'));
				foreach ($data as $pid => $post) {
			      $data[$pid]['post_title'] = stripslashes($post['post_title']);
			      $data[$pid]["post_url"] = $tsCore->createLink('post', [
			         'c_seo' => $post['c_seo'],
			         'post_id' => $post['post_id'],
			         'post_title' => $post['post_title']
			      ]);
				}
			break;
			case 'fotos':
				$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT r.*, SUM(d_total) AS total, f.foto_id, f.f_title, f.f_status, u.user_id, u.user_name FROM @denuncias AS r LEFT JOIN @fotos AS f ON r.obj_id = f.foto_id LEFT JOIN @miembros AS u ON f.f_user = u.user_id  WHERE d_type = \'4\' && f.f_status < 2 GROUP BY r.obj_id ORDER BY total DESC, r.d_date DESC'));
			break;
			case 'users':
				$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT r.*, SUM(d_total) AS total, u.user_name FROM @denuncias AS r LEFT JOIN @miembros AS u ON r.obj_id = u.user_id WHERE d_type = 3 AND u.user_baneado = 0 GROUP BY r.obj_id ORDER BY total, r.d_date DESC'));
			break;
			case 'mps':
				$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT r.*, m.mp_id, m.mp_to, m.mp_from, m.mp_subject, m.mp_preview, m.mp_date FROM @denuncias AS r LEFT JOIN @mensajes AS m ON r.obj_id = m.mp_id WHERE d_type = 2 GROUP BY r.obj_id ORDER BY r.d_date DESC'));
			break;
		}
		//
		return $data;
	}

	/*
	 getDenuncia()
	*/
	public function getDenuncia($type = 'posts') {
		global $tsCore;
		// VARIABLES
		$obj = (int)$_GET['obj'];
		// TIPO DE DENUNCIA
		switch ($type) {
			case 'posts':
				$d_type = 1;
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_title, p.post_status, c.c_nombre, c.c_seo, c.c_img, u.user_name FROM @posts AS p LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE p.post_id = $obj LIMIT 1");
			break;
			case 'fotos':
				$d_type = 4;
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT f.foto_id, f.f_title, f.f_status, u.user_name FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.foto_id = $obj LIMIT 1");
			break;
			case 'users':
				$d_type = 3;
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name FROM @miembros WHERE user_id = $obj LIMIT 1");
			break;
			case 'mps':
				$d_type = 2;
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name FROM @miembros WHERE user_id = $obj LIMIT 1");
			break;
		}
		// CARGAMOS AL ARRAY...
		$data['data'] = db_exec('fetch_assoc', $query);
		// DENUNCIAS
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT d.*, u.user_id, u.user_name FROM @denuncias AS d LEFT JOIN @miembros AS u ON d.d_user = u.user_id WHERE d.obj_id = $obj AND d.d_type = $d_type");
		$data['denun'] = result_array($query);
		return $data;
	}

	public function getContenido() {
		global $tsCore, $tsUser;
		//
		$texto = $tsCore->setSecure($_GET['texto']);
		$tipo = (int)$_GET['t'];
		$metodo = (int)$_GET['m'];
		if (empty($texto) || empty($texto)) $tsCore->redirectTo($tsCore->settings['url'] . '/moderacion/buscador');
		$met =  ($metodo == 1) ? "LIKE '%$texto%'" : " = '$texto'";
		//
		$wtype = 'm.p_' . ($tipo === 1 ? 'ip' : 'body');
		$data['muro'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.pub_id, m.p_user, m.p_user_pub, m.p_ip, m.p_date, m.p_body, u.user_id, u.user_name FROM @muro AS m LEFT JOIN @miembros AS u ON m.p_user_pub = u.user_id WHERE $wtype $met"));
		$data['m_total'] = safe_count($data['muro']);
		//
		$wtype = 'user_' . ($tipo === 1 ? 'last_ip' : 'name');
		$data['usuarios'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_last_ip, user_lastlogin, user_lastactive FROM @miembros WHERE $wtype $met ORDER BY user_lastactive DESC"));
		$data['u_total'] = safe_count($data['usuarios']);
		//
		$wtype = ($tipo === 1) ? "p.post_ip $met" : "p.post_title $met OR p.post_body $met";
		$data['posts'] = result_array( db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_title, p.post_date, p.post_ip, u.user_name, c.c_nombre, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE $wtype"));
		$data['p_total'] = safe_count($data['posts']);
		//
		$wtype = ($tipo === 1) ? "f.f_ip $met" : "f.f_title $met OR f.f_description $met";
		$data['fotos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.foto_id, f.f_title, f.f_user, f.f_date, f.f_ip, u.user_name FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE $wtype"));
		$data['f_total'] = safe_count($data['fotos']);
		//
		$wtype = ($tipo === 1) ? "c.c_ip $met" : "c.c_user $met OR c.c_body $met";
		$data['posts_comentarios'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, c.* FROM @posts_comentarios AS c LEFT JOIN @miembros AS u ON u.user_id = c.c_user WHERE $wtype"));
		$data['c_p_total'] = safe_count($data['posts_comentarios']);
		//
		$wtype = ($tipo === 1) ? "c.c_ip $met" : "c.c_user $met OR c.c_body $met";
		$data['fotos_comentarios'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, f.* , c.* FROM @fotos_comentarios AS c LEFT JOIN @miembros AS u ON u.user_id = c.c_user LEFT JOIN @fotos AS f ON f.foto_id = c.c_foto_id WHERE $wtype"));
		$data['c_f_total'] = safe_count($data['fotos_comentarios']);
		//
		$data['contenido'] = $texto;
		$data['metodo'] = $metodo;
		$data['tipo'] = $tipo;
		//
		return $data;
	}

	/*
	 getPreview()
	*/
	public function getPreview(int $pid = 0) {
		global $tsCore;
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_title, post_body FROM @posts WHERE post_id = $pid LIMIT 1"));
		//
		return [
			'titulo' => $data['post_title'], 
			'cuerpo' => $tsCore->parseBBCode($data['post_body'])
		];
	}

	/**
	 * @name rebootPost()
	 * @access public
	 * @param int
	 * @return string
	*/
	public function rebootPost(int $pid = 0) {
		global $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['mocdp']) {
			// PRIMERO COMPROBAMOS SI ESTÁ OCULTO
			$datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_status FROM @posts WHERE post_id = $pid LIMIT 1"));
			if ($datos['post_status'] == 3) {
				if (!db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @historial WHERE `pofid` = $pid && `type` = 1 && `action` = 3")) return '0: No se pudo restaurar el post.';
			} else {
				//BORRAMOS LA DENUNCIAS
				if (!db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @denuncias WHERE `obj_id` = $pid AND `d_type` = 1")) return '0: No se pudo restaurar el post.';
			}
			// REGRESAMOS EL POST
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_status` = 0 WHERE `post_id` = $pid")) {
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET `stats_posts` = stats_posts + 1 WHERE `stats_no` = 1");
				return '1: El post ha sido restaurado.';
			} else return '0: No se pudo restaurar el post.';
		} else return '0: No sigas haciendo el rid&iacute;culo';
	}

	public function OcultarPost(int $pid = 0, string $razon = null) {
		global $tsUser;
		$time = time();
		if ($tsUser->is_admod || $tsUser->permisos['moop']) {
			if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id FROM @posts WHERE post_id = $pid && post_status = 3"))) return '0: El post... ya est&aacute; oculto.';
			if (!db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_status = 3 WHERE post_id = $pid")) return '0: No se pudo ocultar el post.';
			if (db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @historial (`pofid`, `action`, `type`, `mod`, `reason`, `date`, `mod_ip`) VALUES ($pid, 3, 1, {$tsUser->uid}, '$razon', $time, '{$_SERVER['REMOTE_ADDR']}')")) {
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET `stats_posts` = stats_posts - 1 WHERE `stats_no` = 1");
				return '1: El post ha sido ocultado.';
			} else return '0: No se pudo registrar la acci&oacute;n.';
		} else return '0: No contin&ueacute;s por aqu&iacute;.';
	}

	/** 
	 * Deshacer denuncia 
	**/
	private function reboots(int $id = 0, int $d_type = 0, string $perm = '', string $table = '', string $upset = '', string $where = '') {
		global $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos[$perm]) {
			// BORRAMOS LA DENUNCIA
			if (db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @denuncias WHERE `obj_id` = $id AND `d_type` = $d_type")) {
				db_exec([__FILE__, __LINE__], 'query', "UPDATE $table SET $upset WHERE $where = $id");
				return '1: Denuncia eliminada';
			} else return '0: No se pudo eliminar la denuncia';
		} else return '0: No contin&uacute;e por aqu&iacute;.';
	}
	public function rebootMps(int $mid = 0) {
		$this->reboots($mid, 2, 'mocdm', "@mensajes", "mp_del_to = 0, mp_del_from = 0", "mp_id");
	}
	public function rebootFoto(int $fid = 0) {
		$this->reboots($fid, 4, 'mocdf', "@fotos", "f_status = 0", "foto_id");
	}

	/**
	 * @name deletePost($pid)
	 * @access public
	 * @param int
	 * @return string
	*/
	public function deletePost(int $pid = 0) {
		global $tsCore, $tsMonitor, $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['moep']) {
			// RAZON
			$razon = $tsCore->setSecure($_POST['razon']);
			$razon_desc = $tsCore->setSecure($_POST['razon_desc']);
			$razon_db = ($razon != 13) ? $razon : $razon_desc;
			$time = time();
			//
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_status` = 2 WHERE `post_id` = $pid")) {
				// ELIMINAR DENUNCIAS
				deleteFromId([__FILE__, __LINE__], '@denuncias', "obj_id = $pid AND d_type = 1");
				// ENVIAR AVISO
				$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_user, p.post_title, p.post_body, p.post_tags, p.post_category, u.user_name, u.user_email FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE p.post_id = $pid LIMIT 1"));
				// RAZON
				if (is_numeric($razon_db)) {
					include TS_ZCODE . 'Denuncias.php';
					$razon_db = $tsDenuncias['posts'][$razon_db];
				}
				statsUpdate([__FILE__, __LINE__], [
					'table' => '@stats',
					'columna' => 'stats_posts',
					'donde' => 'stats_no = 1'
				]);
				//AGREGAMOS A BORRADORES si se ha marcado la casilla
				if ($_POST['send_b'] == 'yes')
					db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_borradores (b_user, b_date, b_title, b_body, b_tags, b_category, b_status, b_causa) VALUES ({$data['post_user']}, $time, '{$data['post_title']}', '{$data['post_body']}', '{$data['post_tags']}', {$data['post_category']}, 1, '$razon_db')");
					// AVISO
					$aviso = "Hola <strong>{$data['user_name']}</strong>\n\nLamento contarte que tu post titulado <strong>{$data['post_title']}</strong> ha sido eliminado.\nCausa: <strong>$razon_db</strong>\n\n- Te recomendamos leer el <a href=\"{$tsCore->settings['url']}/pages/protocolo/\" rel=\"internal\">Protocolo</a> para evitar futuras sanciones.\n\n Muchas gracias por entender!";
					$status = $tsMonitor->setAviso($data['post_user'], 'Post eliminado', $aviso, 1);
					//mail($data['user_email'], 'Post eliminado', $aviso);
					$status = $this->setHistory('borrar', 'post', $pid);
					if ($status == true) return '1: El post ha sido eliminado.';
			}
			return '0: El post NO pudo ser eliminado.';
		} else return '0: No deber&iacute;as continuar con esto.';
	}

	/**
	 * @name deleteMps($mid)
	 * @access public
	 * @param int
	 * @return string
	*/
	public function deleteMps(int $mid = 0) {
		global $tsCore, $tsMonitor, $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['moadm']) {
			// ENVIAR AVISO
			if ($query = db_exec([__FILE__, __LINE__], 'query', "SELECT m.mp_from, m.mp_subject, u.user_name FROM @mensajes AS m LEFT JOIN @miembros AS u ON m.mp_from = u.user_id WHERE m.mp_id = $mid LIMIT 1")) {
				$data = db_exec('fetch_assoc', $query);
				// AVISO
				$aviso = "Hola <strong>{$data['user_name']}</strong>\n\nLe informo de que el mensaje privado <strong>{$data['mp_subject']}</strong> ha sido eliminado.\n\n- Te recomendamos leer el <a href=\"{$tsCore->settings['url']}/pages/protocolo/\" rel=\"internal\">Protocolo</a> para evitar futuras sanciones.\nMuchas gracias por entender!";
				$status = $tsMonitor->setAviso($data['mp_from'], 'Mensaje eliminado', $aviso, 1);
				// ELIMINAR DENUNCIAS
				deleteFromId([__FILE__, __LINE__], '@denuncias', "obj_id = $mid AND d_type = 2");
				//LOS MPS SE ELIMINARAN DE LA LISTA DE MPS DEL USUARIO, PERO NO SE BORRARÁN.
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @mensajes SET mp_del_to = 1, mp_del_from = 1 WHERE `mp_id` = $mid");
				// ELIMINAR MPS (Si quiere elimninarlos en vez de ocultarlos, descomente las dos siguientes líneas y comente la anterior "UPDATE")
				/*
					db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @respuestas WHERE `mp_id` = \''.$mid.'\'');
					db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @mensajes WHERE `mp_id` = \''.$mid.'\'');
				*/
					return '1: El mensaje ha sido eliminado.';
			}
			return '0: El mensaje NO pudo ser eliminado.';
		} else return '0: No deber&iacute;as continuar con esto.';
	}

	/**
	 * @name deleteFoto($fid)
	 * @access public
	 * @param int
	 * @return string
	*/
	public function deleteFoto($fid) {
		global $tsCore, $tsMonitor, $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['moadf'] || $tsUser->permisos['moef']) {
			// RAZON
			$razon = $tsCore->setSecure($_POST['razon']);
			$razon_desc = $tsCore->setSecure($_POST['razon_desc']);
			$razon_db = ($razon != 8) ? $razon : $razon_desc;
			//
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @fotos SET `f_status` = 2 WHERE `foto_id` = $fid")) {
				statsUpdate([__FILE__, __LINE__], [
					'table' => '@stats',
					'columna' => 'stats_fotos',
					'donde' => 'stats_no = 1'
				]);
				if ($data['f_user'] != $tsUser->uid) {
					// ENVIAR AVISO
					$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT f.f_user, f.f_title, u.user_name FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.foto_id = $fid LIMIT 1"));
					// RAZON
					if (is_numeric($razon_db)) {
						include TS_ZCODE . 'Denuncias.php';
						$razon_db = $tsDenuncias['fotos'][$razon_db];
					}
					// AVISO
					$aviso = "Hola <strong>{$data['user_name']}</strong>\n\nLamento contarte que tu foto titulada <strong>{$data['f_title']}</strong> ha sido eliminada.\nCausa: <strong>$razon_db</strong>\n\n- Te recomendamos leer el <a href=\"{$tsCore->settings['url']}/pages/protocolo/\" rel=\"internal\">Protocolo</a> para evitar futuras sanciones.\nMuchas gracias por entender!";
					$status = $tsMonitor->setAviso($data['f_user'], 'Foto eliminada', $aviso, 1);
				}
				// ELIMINAR DENUNCIAS
				deleteFromId([__FILE__, __LINE__], '@denuncias', "obj_id = $fid AND d_type = 4");
				$this->setHistory('borrar', 'foto', $fid);
				return '1: La foto ha sido eliminada.';
			}
			return '0: La foto NO pudo ser eliminada.';
		} else return '0: No deber&iacute;as continuar con esto.';
	}

	/**
	 * @name setSticky
	 * @access public
	 * @param $post_id
	 * @return string
	 * @info Pone sticky un post
	 */
	public function setSticky(int $post_id = 0) {
		global $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['most']) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `post_sticky` FROM @posts WHERE `post_id` = $post_id LIMIT 1"));
			# COMPROBAMOS
			$sticky = ((int)$data['post_sticky'] === 1) ? '0' : '1';
			$sticky_f = ((int)$data['post_sticky'] === 1) ? 'quitado de la home' : 'puesto como fijo en la home';
			//
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_sticky` = $sticky WHERE `post_id` = $post_id");
			return "1: El post fue $sticky_f.";
		} else return '0: Creo que no deber&iacute;as continuar con esto';
	}

	/**
	 * @name setOpenClosed
	 * @access public
	 * @param $post_id
	 * @return string
	 * @info Abre o Cierra un post.
	*/
	public function setOpenClosed(int $post_id = 0) {
		global $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['moayca']) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `post_block_comments` FROM @posts WHERE `post_id` = $post_id LIMIT 1"));
			// COMPROBAMOS
			$pbc = ((int)$data['post_block_comments'] === 1);
			$pbcup = $pbc ? '0' : '1';
			$pbctxt = $pbc ? 'abierto' : 'cerrado';
			$pbctxt2 = $pbc ? 'abrir' : 'cerrar';
			//
			return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_block_comments` = $pbcup WHERE `post_id` = $post_id")) ? "1: El post fue $pbctxt." : "0: Hubo un error al $pbctxt2 el post";
		} else return '0: Creo que no deber&iacute;as hacer esto';
	}

	/**
	 * @name getSuspendidos
	 * @access public
	 * @param
	 * @return array
	 * @info OBTIENE LOS USUARIOS SUSPENDIDOS
	 */
	public function getSuspendidos() {
		global $tsCore, $tsUser;
		#
		if ($tsUser->is_admod || $tsUser->permisos['movub']) {
			$max = 20; // MAXIMO A MOSTRAR
			$limit = $tsCore->setPageLimit($max, true);
			//FILTROS
			if ($_GET['o'] === 'inicio') $order = 'date';
			elseif ($_GET['o'] === 'fin') $order = 'termina';
			elseif ($_GET['o'] === 'mod') $order = 'mod';
			else $order = 'id';

			$met = ($_GET['m'] === 'a') ? 'ASC' : 'DESC';

			$data['bans'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT s.*, u.user_name FROM @suspension AS s LEFT JOIN @miembros AS u ON s.user_id = u.user_id WHERE 1 ORDER BY s.susp_$order $met LIMIT $limit"));
				
			// PAGINAS
			$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @suspension WHERE user_id > \'0\'');
			list($total) = db_exec('fetch_row', $query);
			
			$data['pages'] = $tsCore->pageIndex("/moderacion/banusers?o={$_GET['o']}&m={$_GET['m']}", $total, $max);
		}
		return $data;
	}

	/**
	 * @name banUser
	 * @access public
	 * @param int
	 * @return string
	 * @info PARA SUSPENDER A UN USUARIO
	*/
	public function banUser(int $user_id = 0) {
		global $tsUser, $tsCore;
		# LOCALES
		$b_time = $tsCore->setSecure($_POST['b_time']);
		$b_cant = empty($_POST['b_cant']) ? 1 : $_POST['b_cant'];
		$b_causa = $tsCore->setSecure($_POST['b_causa']);
		$b_times = [0, 1, 3600, 86400]; // HORA, DIA
		# NO INTENTO BANEARME?
		if ($user_id == $tsUser->uid) return '0: Si quieres abandonar la web, m&aacute;ndale un mp al admin';
		# NO ES HORARIO VÁLIDO?
		if ($b_cant < 1 || !is_numeric($b_cant)) return '0: Debe introducir en n&uacute;meros una cantidad superior a 60 minutos (1)';
		# COMPROBAMOS RANGOS
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_rango`, `user_baneado` FROM @miembros WHERE `user_id` = $user_id LIMIT 1"));
		if ((int)$data['user_baneado'] !== 0) return '0: Este usuario ya fue suspendido';
		# Y SI QUIERO SUSPENDER A UN ADMIN o MOD?
		if (($tsUser->is_admod < $data['user_rango'] && (int)$tsUser->is_admod > 0) || ($tsUser->permisos['mosu'] && (int)$data['user_rango'] >= 2)) {
			// TIEMPO
			$ahora = time();
			$termina = ($b_cant * $b_times[$b_time]);
			$termina = ($b_time >= 2) ? ($ahora + $termina) : $termina;
			$myip = $tsCore->executeIP();
			// ACTUALIZAMOS
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET `user_baneado` = 1 WHERE `user_id` = $user_id");
			if (db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @suspension (`user_id`, `susp_causa`, `susp_date`, `susp_termina`, `susp_mod`, `susp_ip`) VALUES ($user_id, $b_causa, $ahora, $termina, {$tsUser->uid}, '$myip')")) {
				// ELIMINAR DENUNCIAS
				deleteFromId([__FILE__, __LINE__], '@denuncias', "obj_id = $user_id AND d_type = 3");
				// RESTAR USUARIO EN ESTADÍSTICAS
				statsUpdate([__FILE__, __LINE__], [
					'table' => '@stats',
					'columna' => 'stats_miembros',
					'donde' => 'stats_no = 1'
				]);
				// RETORNAR
				if ($b_time < 2) {
					$rdate = ($b_time == 0) ? 'Indefinidamente' : 'Permanentemente';
					$rdate = "<strong>$rdate</strong>";
				} else $rdate = "hasta el <strong>". date("d/m/Y H:i:s", $termina) . "</strong>";
				return "1: Usuario suspendido $rdate</strong>";
			}
			return '0: El usuario no pudo ser suspendido';
		} else return '0: No puedes suspender a usuarios de tu mismo rango o superior al tuyo.';
	}


	/**
	 * @name rebootUser
	 * @access public
	 * @param int
	 * @return string
	 * @info ELIMINA LAS DENUNCIAS DEL USUARIO O LE QUITA UNA SUSPENSION
	*/
	public function rebootUser(int $user_id = 0, string $type = 'unban'): string {
		global $tsUser;
		if ($tsUser->is_admod || $tsUser->permisos['modu']) {
			# PRIMERO BORRAMOS LA DENUNCIAS
			deleteFromId([__FILE__, __LINE__], '@denuncias', "obj_id = $user_id AND d_type = 3");
			// HAY QUE QUITAR LA SUSPENSION?
			if ($type === 'unban') {
				$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `susp_mod` FROM @suspension WHERE `user_id` = $user_id"));
				//
				if (empty($data)) return '0: El usuario no est&aacute; suspendido.';
				//
				if ($tsUser->is_admod == 1 || $data['susp_mod'] == $tsUser->uid) {
					deleteFromId([__FILE__, __LINE__], '@suspension', "user_id = $user_id");
					updateId([__FILE__, __LINE__], '@miembros', "user_baneado = 0", "user_id = $user_id");
					statsUpdate([__FILE__, __LINE__], [
						'table' => '@stats',
						'columna' => 'stats_miembros',
						'donde' => 'stats_no = 1'
					], true);
					return '1: El usuario fue reactivado y ahora podr&aacute; seguir activo en la web.';
				} else return '0: S&oacute;lo puedes quitar la suspensi&oacute;n a los usuarios que t&uacute; suspendiste.';
			}
			return '1: Las denuncias fueron eliminadas.';
		} else return '0: Creo que no deber&iacute;as hacer esto';
	}
	 /**
	  * @name deletePost
	  * @access public
	  * @param int
	  * @return string
	  */
	 public function setHistory($action, $type, $data)  {
		  global $tsUser, $tsMonitor, $tsCore;
		  //
		  if ($type == 'post')
		  {
				switch ($action)
				{
					 case 'borrar':
						  // RAZON
						  $razon = $tsCore->setSecure($_POST['razon']);
						  $razon_desc = $tsCore->setSecure($_POST['razon_desc']);
						  $razon_db = ($razon != 13) ? $razon : $razon_desc;
						  // DATOS
						  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `post_id`, `post_body`, `post_title`, `post_user`, `post_category` FROM @posts WHERE `post_id` = \'' .(int)$data . '\' LIMIT 1');
						  $post = db_exec('fetch_assoc', $query);
						  
						  // INSERTAR
						  if ($post['post_user'] != $tsUser->uid)
								db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @historial (`pofid`, `action`, `type`, `mod`, `reason`, `date`, `mod_ip`) VALUES (\'' .
									 (int)$post['post_id'] . '\', \'2\', \'1\', \'' . $tsUser->uid . '\', \'' .
									 $razon_db . '\', \'' . time() . '\', \'' .
									 $_SERVER['REMOTE_ADDR'] . '\')');
						  return true;
						  break;
						  // EDITAR
					 case 'editar':
						  $aviso = 'Hola <b>' . $tsUser->getUserName($data['autor']) . "</b>\n\n Te informo que tu post <b>" .
								$data['title'] . "</b> ha sido editado por <a href=\"#\" class=\"hovercard\" uid=\"" .
								$tsUser->uid . "\">" . $tsUser->nick . "</a>\n\n Causa: <b>" . $data['razon'] .
								"</b>\n\n \n\n Te recomendamos leer el <a href=\"" . $tsCore->settings['url'] .
								"/pages/protocolo/\">protocolo</a> para evitar futuras sanciones.\n\n Muchas gracias por entender!";
						  $tsMonitor->setAviso($data['autor'], 'Post editado', $aviso, 2);
						  $_SERVER['REMOTE_ADDR'] = $_SERVER['X_FORWARDED_FOR'] ? $_SERVER['X_FORWARDED_FOR'] :$_SERVER['REMOTE_ADDR'];
						  if (!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP))
						  {
								die('Su ip no se pudo validar.');
						  }
						  db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @historial (`pofid`, `action`, `type`, `mod`, `reason`, `date`, `mod_ip`) VALUES (\'' .
								(int)$data['post_id'] . '\', \'1\', \'1\', \'' . $tsUser->uid . '\', \'' . $data['razon'] .
								'\', \'' . time() . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\')');
						  return 1;
						  break;
				}

		  } elseif ($type == 'foto')
		  {
				// DATOS
				$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `foto_id`, `f_description`, `f_title`, `f_user` FROM @fotos WHERE `foto_id` = \'' .
					 (int)$data . '\' LIMIT 1');
				$foto = db_exec('fetch_assoc', $query);
				
				switch ($action)
				{
					 case 'borrar':
						  // RAZON
						  $razon = $tsCore->setSecure($_POST['razon']);
						  $razon_desc = $tsCore->setSecure($_POST['razon_desc']);
						  $razon_db = ($razon != 8) ? $razon : $razon_desc;
						  // INSERTAR
						  db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @historial (`pofid`, `action`, `type`, `mod`, `reason`, `date`, `mod_ip`) VALUES (\'' .
								(int)$foto['foto_id'] . '\', \'2\', \'2\', \'' . $tsUser->uid . '\', \'' .
								$tsCore->setSecure($razon_db) . '\', \'' . time() . '\', \'' .
								$tsCore->setSecure($_SERVER['REMOTE_ADDR']) . '\')');
						  return true;
						  break;
				}
		  }
	 }

	 public function getPospelera() {
		  global $tsUser, $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @posts AS p LEFT JOIN @miembros AS u ON u.user_id = p.post_user LEFT JOIN @historial AS h ON h.pofid = p.post_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category  WHERE h.type = \'1\' AND h.action = \'2\'');
		  list($total) = db_exec('fetch_row', $query);
		  
		  $data['pages'] = $tsCore->pageIndex("/moderacion/pospelera?", $total, $max);
		  //

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, h.*, p.post_id, p.post_title, c.c_seo, c.c_nombre FROM @posts AS p LEFT JOIN @miembros AS u ON u.user_id = p.post_user LEFT JOIN @historial AS h ON h.pofid = p.post_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category  WHERE h.type = \'1\' AND h.action = \'2\' AND p.post_status = \'2\' LIMIT ' .
				$limit);
		  // DENUNCIAS
		  include TS_ZCODE . "Denuncias.php";
		  //
		  while ($row = db_exec('fetch_assoc', $query))
		  {
				$row['mod_name'] = $tsUser->getUserName($row['mod']);
				$row['reason'] = (is_numeric($row['reason'])) ? $tsDenuncias['posts'][$row['reason']] :
					 $tsCore->setSecure($row['reason']);
				//
				$data['datos'][] = $row;
		  }
		  //
		  return $data;
	 }

	 public function getFopelera() {
		  global $tsUser, $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user LEFT JOIN @historial AS h ON h.pofid = f.foto_id WHERE h.type = \'2\' AND h.action = \'2\' AND f.f_status = \'2\'');

		  list($total) = db_exec('fetch_row', $query);
		  
		  $data['pages'] = $tsCore->pageIndex("/moderacion/fopelera?", $total, $max);
		  //

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, h.*, f.foto_id, f.f_title, f.f_user FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user LEFT JOIN @historial AS h ON h.pofid = f.foto_id WHERE h.type = \'2\' AND h.action = \'2\' AND f.f_status = \'2\' LIMIT ' .
				$limit);
		  // DENUNCIAS
		  include TS_ZCODE . "Denuncias.php";
		  //
		  while ($row = db_exec('fetch_assoc', $query))
		  {
				$row['mod_name'] = $tsUser->getUserName($row['mod']);
				$row['reason'] = (is_numeric($row['reason'])) ? $tsDenuncias['fotos'][$row['reason']] :
					 $tsCore->setSecure($row['reason']);
				//
				$data['datos'][] = $row;
		  }
		  //
		  return $data;
	 }

	 public function getComentariosD() {
		  global $tsUser, $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @posts_comentarios AS c LEFT JOIN @miembros AS u ON u.user_id = c.c_user WHERE c.c_status = \'1\'');

		  list($total) = db_exec('fetch_row', $query);
		  
		  $data['pages'] = $tsCore->pageIndex("/moderacion/comentarios?", $total, $max);
		  //

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, c.cid, c.c_user, c.c_post_id, c.c_date, c.c_body, c.c_ip, p.post_id, p.post_title, cat.c_seo, cat.c_nombre FROM @posts_comentarios AS c LEFT JOIN @posts AS p ON c.c_post_id = p.post_id LEFT JOIN @posts_categorias AS cat ON cat.cid = p.post_category  LEFT JOIN @miembros AS u ON u.user_id = c.c_user WHERE c.c_status = \'1\' ORDER BY c.c_date DESC LIMIT ' .
				$limit);
		  $data['datos'] = result_array($query);
		  

		  //
		  return $data;
	 }

	public function getPostsD() {
		global $tsUser, $tsCore;
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);

		// PAGINAS
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM @posts AS p LEFT JOIN @miembros AS u ON u.user_id = p.post_user WHERE p.post_status = 3");

		list($total) = db_exec('fetch_row', $query);
		
		$data['pages'] = $tsCore->pageIndex("/moderacion/revposts?", $total, $max);
		//
		$data['datos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, h.*, p.post_id, p.post_title, c.cid, c.c_seo, c.c_nombre FROM @posts AS p LEFT JOIN @historial AS h ON h.pofid = p.post_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @miembros AS u ON u.user_id = h.mod  WHERE h.type = 1 AND h.action = 3 AND p.post_status = 3 LIMIT $limit"));
		foreach ($data['datos'] as $pid => $post) {
		   $data['datos'][$pid]['post_title'] = stripslashes($post['post_title']);
		   $data['datos'][$pid]["post_url"] = $tsCore->createLink('post', [
		      'c_seo' => $post['c_seo'],
		      'post_id' => $post['post_id'],
		      'post_title' => $post['post_title']
		   ], '#pp_' . $post['cid']);
		}
		
		//
		return $data;
	}

	 /**
	  * @name getHistory()
	  * @access public
	  * @param
	  * @return array
	  */
	 public function getHistory($type)
	 {
		  global $tsUser, $tsCore;
		  //
		  if ($type == 1)
				$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, h.*, p.post_id, p.post_title FROM @posts AS p LEFT JOIN @miembros AS u ON u.user_id = p.post_user LEFT JOIN @historial AS h ON h.pofid = p.post_id WHERE h.type = \'1\' ORDER BY h.id DESC LIMIT 20');
		  else
				$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, h.*, f.foto_id, f.f_title, f.f_user FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user LEFT JOIN @historial AS h ON h.pofid = f.foto_id WHERE h.type = \'2\' ORDER BY h.id DESC LIMIT 20');
		  // DENUNCIAS
		  include TS_ZCODE . "Denuncias.php";
		  //
		  while ($row = db_exec('fetch_assoc', $query))
		  {
				$row['mod_name'] = $tsUser->getUserName($row['mod']);
				$row['reason'] = (is_numeric($row['reason'])) ? $tsDenuncias['posts'][$row['reason']] :
					 $tsCore->setSecure($row['reason']);
				//
				$data[] = $row;
		  }
		  //
		  return $data;
	 }
}