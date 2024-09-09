<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');
/**
 * Modelo para la adminitración
 *
 * @name    c.admin.php
 * @author  Miguel92
 */
class tsAdmin {

	# Extensiones para imagenes
	private $extension = ["jpeg", "jpg", "png", "gif", "bmp", "svg", "webp"];

	# Las opciones para los rangos (saveRango() y newRango())
	private function optionsRange($post) {
		return serialize([
			'suad' => $post['superadmin'],
			'sumo' => $post['supermod'],
			'moacp' => $post['mod-accesopanel'],
			'mocdu' => $post['mod-cancelardenunciasusuarios'],
			'moadf' => $post['mod-aceptardenunciasfotos'],
			'mocdf' => $post['mod-cancelardenunciasfotos'],
			'mocdp' => $post['mod-cancelardenunciasposts'],
			'moadm' => $post['mod-aceptardenunciasmensajes'],
			'mocdm' => $post['mod-cancelardenunciasmensajes'],
			'movub' => $post['mod-verusuariosbaneados'],
			'moub' => $post['mod-usarbuscador'],
			'morp' => $post['mod-reciclajeposts'],
			'morf' => $post['mod-reficlajefotos'],
			'mocp' => $post['mod-contenidoposts'],
			'mocc' => $post['mod-contenidocomentarios'],
			'most' => $post['mod-sticky'],
			'moayca' => $post['mod-abrirycerrarajax'],
			'movcud' => $post['mod-vercuentasdesactivadas'],
			'movcus' => $post['mod-vercuentassuspendidas'],
			'mosu' => $post['mod-suspenderusuarios'],
			'modu' => $post['mod-desbanearusuarios'],
			'moep' => $post['mod-eliminarposts'],
			'moedpo' => $post['mod-editarposts'],
			'moop' => $post['mod-ocultarposts'],
			'mocepc' => $post['mod-comentarpostcerrado'],
			'moedcopo' => $post['mod-editarcomposts'],
			'moaydcp' => $post['mod-desyaprobarcomposts'],
			'moecp' => $post['mod-eliminarcomposts'],
			'moef' => $post['mod-eliminarfotos'],
			'moedfo' => $post['mod-editarfotos'],
			'moecf' => $post['mod-eliminarcomfotos'],
			'moepm' => $post['mod-eliminarpubmuro'],
			'moecm' => $post['mod-eliminarcommuro'],
			'godp' => $post['global-darpuntos'],
			'gopp' => $post['global-publicarposts'],
			'gopcp' => $post['global-publicarcomposts'],
			'govpp' => $post['global-votarposipost'],
			'govpn' => $post['global-votarnegapost'],
			'goepc' => $post['global-editarpropioscomentarios'],
			'godpc' => $post['global-eliminarpropioscomentarios'],
			'gopf' => $post['global-publicarfotos'],
			'gopcf' => $post['global-publicarcomfotos'],
			'gorpap' => $post['global-revisarposts'],
			'govwm' => $post['global-vermantenimiento'],
			'goaf' => $post['global-antiflood'],
			'gopfp' => $post['global-pointsforposts'],
			'gopfd' => $post['global-pointsforday'],
			'goda' => $post['global-avatargif']
		]);
	}

	/** 
	 * Agregamos esta función ya que se repite 2 veces,
	 * extraemos las imagenes
	*/
	public function getExtraIcons(string $folder = 'categorias', int $size = 16) {
		$ruta = TS_ASSETS . "images" . TS_PATH  . $folder;
		# Accedemos a la carpeta de icons
		$carpeta = scandir( $ruta );
		# Recorremos la carpeta
		foreach($carpeta as $pid => $image) {
			if(in_array($image, ['.', '..'])) continue;
			# Comprobamos extension
			$ext = pathinfo($image, PATHINFO_EXTENSION);
			if (in_array($ext, $this->extension)) {
				$icons[] = $image;
			}
		}		
		# Retornamos las imagenes
		return $icons;
	}

	/**
	 * Función para obtenener a los administradores
	*/
	public function getAdmins() {
		global $tsCore;
		$admins = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, p.p_nombre FROM @miembros AS u LEFT JOIN @perfil AS p ON u.user_id = p.user_id WHERE u.user_rango = 1 ORDER BY u.user_id"));
		foreach($admins as $uid => $user) {
			$admins[$uid]['avatar'] = $tsCore->getAvatar($user['user_id']);
		}
		return $admins;
	}
	
	/**
	 * Función para obtener la fecha de instalación/actualización
	*/
	public function getInst() {
		return db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT `stats_time_foundation`, `stats_time_upgrade` FROM @stats WHERE stats_no = 1"));
	}
	/**
	 * Función para obtener versiones del sistema
	*/
	public function getVersions() {
		$temp = @gd_info();
		return [
			'php' => PHP_VERSION,
			'mysql' => db_exec('fetch_row',db_exec([__FILE__, __LINE__], 'query', 'SELECT VERSION()')),
			'server' => $_SERVER['SERVER_SOFTWARE'],
			'gd' => $temp['GD Version'] ?? 'La biblioteca GD no está instalada'
		];
	}
	/**
	 * Función para guardar la configuración
	*/
	public function saveConfig() {
		global $tsCore;
		// Recorremos y editamos el array $_POST, eliminamos el item de "SAVE", 
		// la guardamos en la misma variable $_POST
		foreach($_POST = (isset($_POST['save']) ? array_slice($_POST, 0, -1) : $_POST) as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : $tsCore->setSecure($val);
		// Guardamos
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET {$tsCore->getIUP($_POST)} WHERE tscript_id = 1")) return true;
		else exit( show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db') );
	}
	# ===================================================
	# NOTICIAS
	# * getNoticias() :: Obtenemos todas las noticias
	# * getNoticia() :: Obtenemos la noticia por ID
	# * delNoticia() :: Eliminamos la noticia por ID
	# * newNoticia() :: Creamos una nueva notica
	# * editNoticia() :: Editamos la noticia
	# ===================================================
	private function sameNoticeSave(bool $new = true) {
		global $tsCore, $tsUser;
		$not_body = $tsCore->setSecure($tsCore->parseBadWords(substr($_POST['not_body'], 0, 190)));
		$not_active = empty($_POST['not_active']) ? 0 : 1;
		$not_type = isset($_POST['not_type']) ? (int)$_POST['not_type'] : 0;
		//
		$notice = [
			'body' => $not_body,
			'autor' => (int)$tsUser->uid,
			'active' => $not_active,
			'type' => $not_type
		];
		if($new) $notice['date'] = time();
		return $notice ;
	}
	public function getNoticias() {
		global $tsCore;
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, n.not_id, n.not_body, n.not_autor, n.not_date, n.not_type, n.not_active FROM @noticias AS n LEFT JOIN @miembros AS u ON n.not_autor = u.user_id  WHERE n.not_id > 0 ORDER BY n.not_id DESC"));
		foreach($data as $nid => $noticia) {
			$data[$nid]['not_body'] = $tsCore->parseBBCode($noticia['not_body']);
		}
		return $data;
	}
	public function getNoticia() {
		global $tsCore;
		$not_id = (int)$_GET['nid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT not_id, not_body, not_autor, not_date, not_type, not_active FROM @noticias WHERE not_id = $not_id LIMIT 1"));
		return $data;
	}
	public function delNoticia() {
		$not_id = (int)$_POST['nid'];
		if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT not_id FROM @noticias WHERE not_id = $not_id LIMIT 1"))) return '0: El id ingresado no existe.';
		return (!deleteFromId([__FILE__, __LINE__], '@noticias', "not_id = $not_id")) ? '1: Noticia eliminada' : '0: No se pudo borrar noticia.';
	}
	public function newNoticia() {
		global $tsCore, $tsUser;
		//
		if (!empty($_POST['not_body'])) {
			if(insertDataInBase([__FILE__, __LINE__], '@noticias', $this->sameNoticeSave(), 'not_')) return true;
		}
		//
		return false;
	}
	public function editNoticia() {
		global $tsCore;
		$not_id = (int)$_GET['nid'];
		//
		if (!empty($_POST['not_body'])) {
			$set = $tsCore->getIUP($this->sameNoticeSave(false), 'not_');
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @noticias SET $set WHERE not_id = $not_id")) return true;
		}
	}
	# ===================================================
	# TEMAS
	# * getIdTheme() :: Evitamos repetir
	# * privateTheme() :: Evitamos repetir
	# * getTemas() :: Obtenemos todos los temas
	# * getTema() :: Obtenemos el tema por ID
	# * saveTema() :: Guardamos nuevo tema
	# * changeTema() :: Cambiamos el aspecto (tema)
	# * deleteTema() :: Eliminamos el tema (los podría eliminar!)
	# * newTema() :: Instalamos nuevo tema (los podría eliminar!)
	# ===================================================
	private function getIdTheme() {
		return (int)$_GET['tid'];
	}
	private function privateTheme(bool $getThemes = true, int $tid = 0) {
		global $installer;
		$sql = db_exec([__FILE__, __LINE__], 'query', "SELECT tid, t_name, t_url, t_path, t_copy FROM @temas WHERE tid " . ($getThemes ? '> 0' : "= $tid"));
		if($getThemes) {
			$data = result_array($sql); 
			foreach($data as $tid => $theme) $installer->getThemeInfo($data, $tid, $theme);
			return $data;
		} 
		return db_exec('fetch_assoc', $sql);		
	}
	public function getTemas() {
		return $this->privateTheme();
	}
	public function getTema() {
		return $this->privateTheme(false, $this->getIdTheme());
	}
	public function saveTema() {
		global $tsCore;
		// No require el setSecure() ya que lo aplica la misma función getIUP()
		$tema = $tsCore->getIUP(['url' => $_POST['url'], 'path' => $_POST['path']], 't_');
		//
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @temas SET $tema WHERE tid = " . $this->getIdTheme()));
	}
	public function changeTema() {
		$tema = $this->getTema();
		$id = (int)$tema['tid'];
		var_dump($tema, $id);
		//
		if (!empty($tema['tid'])) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET tema_id = $id WHERE tscript_id = 1");
			return true;
		} else return false;
	}
	public function deleteTema() {
		$tema = $this->getTema();
		$id = (int)$tema['tid'];
		if (!empty($id)) {
			deleteFromId([__FILE__, __LINE__], '@temas', "tid = $id");
			return true;
		} else return false;
	}
	public function newTema() {
		global $tsCore;
		//
		$tema_path = $tsCore->setSecure($_POST['path']);
		// ARCHIVO DE INSTALACION
		include_once TS_THEMES . $tema_path . TS_PATH . 'install.php';
		//
		if(!isset($tema)) return '0: Revisa que el nombre de la carpeta sea correcto.';
		if(in_array('', $tema)) return '0: El archivo de instalaci&oacute;n del tema es incorrecto. Recuerda utilizar temas oficiales.';
		// Comprobamos que sea seguro
		foreach ($tema as $key => $val) $tema[$key] = $tsCore->setSecure($val);
		// Instalamos...
		return (insertDataInBase([__FILE__, __LINE__], '@temas', ['name' => $tema['nombre'], 'url' => $tema['url'], 'path' => $tema_path, 'copy' => $tema['copy']], 't_')) ? '1: Tema instalado correctamente.' : '0: Ocurri&oacute; un error durante la instalaci&oacute;n.';
	}
	# ===================================================
	# PUBLICIDADES
	# * saveAds() :: Guardamos las publicidades
	# ===================================================
	public function saveAds() {
		global $tsCore;
		$ads = array_splice($_POST, 0, -1);
		foreach ($ads as $key => $value) {
			$ads[$key] = ($key === 'ads_search') ? $value : html_entity_decode($value);
		}
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET {$tsCore->getIUP($ads)} WHERE tscript_id = 1")) return true;
	}
	# ===================================================
	# CATEGORIAS
	# * dataCat() :: Armamos el array para saveCat() & newCat()
	# * saveOrden() :: Guardamos el orden de las categorias
	# * getCat() :: Obtenemos la categoria
	# * saveCat() :: Guardamos los nuevos datos de la categoría
	# * MoveCat() :: Mover de categoría
	# * newCat() :: Creamos una nueva categoría
	# * delCat() :: Eliminamos la categoría 
	# ===================================================
	private function dataCat(string $type = '', int $orden = 0) {
		global $tsCore;
		$nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		$categoria = [
			"nombre" => $nombre,
			"seo" => $tsCore->setSEO($nombre),
			"img" => $tsCore->setSecure($_POST['c_img']),
			"descripcion" => $tsCore->setSecure($_POST['c_descripcion']),
		];
		if($type === 'nueva') $categoria['orden'] = $orden;
		return $categoria;
	}
	public function saveOrden() {
		$ordenado = [];
		# Obtenemos lista con el nuevo orden
		$nuevo_orden = 1;
		foreach (explode(',', $_POST["cats"]) as $orden) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_categorias SET c_orden = $nuevo_orden WHERE cid = $orden");
			array_push($ordenado, $nuevo_orden);
			$nuevo_orden++;
		}
	}
	public function getCat() {
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		# Obtenemos la información
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT cid, c_orden, c_nombre, c_descripcion, c_seo, c_img FROM @posts_categorias WHERE cid = $cid LIMIT 1"));
		# Retornamos los daots
		return $data;
	}
	public function saveCat() {
		global $tsCore;
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		$categoria = $tsCore->getIUP($this->dataCat(), 'c_');
		# Guardamos en la tabla
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_categorias SET $categoria WHERE cid = $cid")) return true;
	}
	public function MoveCat() {
		$new_category = (int)$_POST['newcid'];
		$old_category = (int)$_POST['oldcid'];
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_category = $new_category WHERE post_category = $old_category")) return true;
	}
	public function newCat() {
		global $tsCore;
		# Valores
		$c_nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		# Orden
		$orden = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS total FROM @posts_categorias'))['total'] + 1;
		# Insertamos los datos
		$categoria = $this->dataCat('nueva', $orden);
		if (insertDataInBase([__FILE__, __LINE__], '@posts_categorias', $categoria, 'c_')) return true;
	}
	public function delCat() {
		global $tsCore;
		//
		$cid = (int)$_GET['cid'];
		$ncid = (int)$_POST['ncid'];
		// MOVER
		if (empty($ncid) and $ncid === 0) return 'Antes de eliminar una categor&iacute;a debes elegir a donde mover sus subcategor&iacute;as.';
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_category = $ncid WHERE post_category = $cid")) {
			if(deleteFromId([__FILE__, __LINE__], '@posts_categorias', "cid = $cid")) return true;
		// SI LLEGÓ HASTA AQUI HUBO UN ERROR.
		} else return 'Lo sentimos ocurri&oacute; un error';
	}
	# ===================================================
	# RANGOS
	# * getRangos() :: Obtenemos todos los rangos
	# * getRango() :: Obtenemos el rango
	# * getRangoUsers() :: Rangos de los usuarios
	# * sameArrayRango() :: Evitamos que se repitan
	# * saveRango() :: Guardamos rango
	# * newRango() :: Nuevo rango
	# * delRango() :: Eliminamos el rango 
	# * SetDefaultRango() :: Rango por defecto
	# ===================================================
	public function getRangos() {
		global $tsCore;
		// RANGOS SIN PUNTOS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM @rangos ORDER BY rango_id, r_cant');
		// ARMAR ARRAY
		while ($row = db_exec('fetch_assoc', $query))  {
			$extra = unserialize($row['r_allows']);
			$data[$row['r_type'] == 0 ? 'regular' : 'post'][$row['rango_id']] = [
				'id' => $row['rango_id'],
				'name' => $row['r_name'],
				'color' => $row['r_color'],
				'imagen' => $row['r_image'],
				'cant' => $row['r_cant'],
				'max_points' => $extra['gopfp'],
				'user_puntos' => $extra['gopfd'],
				'type' => $row['r_type'],
				'num_members' => 0
			];
		}
		db_exec('free_result', $query);
		// NUMERO DE USUARIOS EN CADA RANGO
		if (!empty($data['post'])) {
			$IN = implode(', ', array_keys($data['post']));
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_rango AS ID_GROUP, COUNT(user_id) AS num_members FROM @miembros WHERE user_rango IN ($IN) GROUP BY user_rango");
			while ($row = db_exec('fetch_assoc', $query)) {
				$data['post'][$row['ID_GROUP']]['num_members'] += $row['num_members'];
			}
			db_exec('free_result', $query);
		}
		// NUMERO DE USUARIOS EN RANGOS REGULARES
		if (!empty($data['regular'])) {
			$IN = implode(', ', array_keys($data['regular']));
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_rango AS ID_GROUP, COUNT(*) AS num_members FROM @miembros WHERE user_rango IN ($IN) GROUP BY user_rango");
			while ($row = db_exec('fetch_assoc', $query)) {
				 $data['regular'][$row['ID_GROUP']]['num_members'] += $row['num_members'];
			}
			db_exec('free_result', $query);
		}
		//
		return $data;
	}
	public function getRango() {
		$rid = (int)$_GET['rid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM @rangos WHERE rango_id = $rid LIMIT 1"));
		$data['permisos'] = unserialize($data['r_allows']);
		return $data;
	}
	public function getRangoUsers() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		$max = 10; // MAXIMO A MOSTRAR
		// TIPO DE BUSQUEDA
		$type = $_GET['t'];
		// SELECCIONAMOS
		$limit = $tsCore->setPageLimit($max, true);
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_email, u.user_registro, u.user_lastlogin FROM @miembros AS u WHERE u.user_rango = $rid LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM @miembros WHERE user_rango = $rid LIMIT $limit"));
		$data['pages'] = $tsCore->pageIndex("/admin/rangos?act=list&rid=$rid&t=$type", $total, $max);
		//
		return $data;
	}
	private function sameArrayRango(array $post = []) {
		global $tsCore;
		$retornar = [
			'name' => $tsCore->setSecure($tsCore->parseBadWords($post['rName'])),
			'color' => $tsCore->setSecure($post['rColor']),
			'image' => $tsCore->setSecure($post['r_img']),
			'cant' => empty($post['global-cantidadrequerida']) ? 0 : (int)$post['global-cantidadrequerida'],
			'type' => $post['global-type'] > 4 ? 0 : $post['global-type'],
			'allows' => self::optionsRange($post)
		];
		if (empty($retornar['name'])) return 'Debes ingresar el nombre del nuevo rango.';
		if ($post['global-pointsforposts'] > $post['global-pointsforday']) return 'El rango no puede dar m&aacute;s puntos de los que tiene al d&iacute;a.';
		return $retornar;
	}
	public function saveRango() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		$r = self::sameArrayRango($_POST);
		$set = $tsCore->getIUP($r, 'r_');
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @rangos SET $set WHERE rango_id = $rid")) return true;
		else exit( show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db') );
	}
	public function newRango() {
		global $tsCore;
		$r = self::sameArrayRango($_POST);
		// Insertamos los datos
		if (insertDataInBase([__FILE__, __LINE__], '@rangos', $r, 'r_')) return true;
	}
	public function delRango() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		//
		if ($rid > 3) {
			$new_rango = (int)$_POST['new_rango'];
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_rango = $new_rango WHERE user_rango = $rid")) {
				if (deleteFromId([__FILE__, __LINE__], '@rangos', "rango_id = $rid")) return true;
			}
		} else return 'No es posible eliminar este rango';
	}
	public function SetDefaultRango() {
		global $tsCore;
		//
		$url = $tsCore->settings['url'].'/admin/rangos';
		if($_SERVER['HTTP_REFERER'] == "$url?save=true" || $_SERVER['HTTP_REFERER'] == $url) {
			$rid = (int)$_GET['rid'];
			//
			$dato = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT rango_id, r_type FROM @rangos WHERE rango_id = $rid LIMIT 1"));
			if (!empty($dato['rango_id']) && $dato['r_type'] == 0) {
				if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET c_reg_rango = $rid WHERE tscript_id = 1")) return true;
			} else return 'El rango no existe o no es posible utilizarlo';
		} else return 'Petici&oacute;n inv&aacute;lida';
	}
	public function rangoColor() {
		return [
			"000000","000033","000066","000099","0000cc","0000ff","330000","330033","330066","330099","3300cc","3300ff","660000","660033","660066","660099","6600cc","6600ff","990000","990033","990066","990099","9900cc","9900ff","cc0000","cc0033","cc0066","cc0099","cc00cc","cc00ff","ff0000","ff0033","ff0066","ff0099","ff00cc","ff00ff","003300","003333","003366","003399","0033cc","0033ff","333300","333333","333366","333399","3333cc","3333ff","663300","663333","663366","663399","6633cc","6633ff","993300","993333","993366","993399","9933cc","9933ff","cc3300","cc3333","cc3366","cc3399","cc33cc","cc33ff","ff3300","ff3333","ff3366","ff3399","ff33cc","ff33ff","006600","006633","006666","006699","0066cc","0066ff","336600","336633","336666","336699","3366cc","3366ff","666600","666633","666666","666699","6666cc","6666ff","996600","996633","996666","996699","9966cc","9966ff","cc6600","cc6633","cc6666","cc6699","cc66cc","cc66ff","ff6600","ff6633","ff6666","ff6699","ff66cc","ff66ff","009900","009933","009966","009999","0099cc","0099ff","339900","339933","339966","339999","3399cc","3399ff","669900","669933","669966","669999","6699cc","6699ff","999900","999933","999966","999999","9999cc","9999ff","cc9900","cc9933","cc9966","cc9999","cc99cc","cc99ff","ff9900","ff9933","ff9966","ff9999","ff99cc","ff99ff","00cc00","00cc33","00cc66","00cc99","00cccc","00ccff","33cc00","33cc33","33cc66","33cc99","33cccc","33ccff","66cc00","66cc33","66cc66","66cc99","66cccc","66ccff","99cc00","99cc33","99cc66","99cc99","99cccc","99ccff","cccc00","cccc33","cccc66","cccc99","cccccc","ccccff","ffcc00","ffcc33","ffcc66","ffcc99","ffcccc","ffccff","00ff00","00ff33","00ff66","00ff99","00ffcc","00ffff","33ff00","33ff33","33ff66","33ff99","33ffcc","33ffff","66ff00","66ff33","66ff66","66ff99","66ffcc","66ffff","99ff00","99ff33","99ff66","99ff99","99ffcc","99ffff","ccff00","ccff33","ccff66","ccff99","ccffcc","ccffff","ffff00","ffff33","ffff66","ffff99","ffffcc","ffffff"
		];
	}
	# ===================================================
	# USUARIOS
	# * getUsuarios() :: Obtenemos todos los usuarios
	# * getUserPrivacidad() :: Obtener privacidad del usuario
	# * setUserPrivacidad() :: Conjunto de privacidad
	# * getUserData() :: Obtenemos datos del usuario
	# * setUserData() :: Conjunto de datos del usuario
	# * deleteContent() :: Eliminamos contenido
	# * getUserRango() :: Obtenemos el rango del usuario
	# * getAllRangos() :: Obtenemos todos los rangos
	# * setUserRango() :: Cambiamos el rango al usuario
	# * setUserFirma() :: Cambiamos la firma del usuario
	# * setUserInActivo() :: Des/Activamos usuario (AJAX)
	# * setUserAvatar() :: Añadimos gif y activamos avatar gif
	# * setUserAct() :: Activamos avatar gif global
	# ===================================================
	public function getUsuarios() {
		global $tsCore;
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);
		//
		$ord = ['' => 'id', 'e' => 'activo, u.user_baneado', 'c' => 'email', 'i' => 'last_ip', 'u' => 'lastactive'];
		$order = $ord[$_GET['o']];
		$asd = ($_GET['m'] == 'a') ? 'ASC' : 'DESC';
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.*, r.*, p.* FROM @perfil AS p LEFT JOIN @miembros AS u ON u.user_id = p.user_id LEFT JOIN @rangos AS r ON r.rango_id = u.user_rango ORDER BY u.user_$order $asd LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM @miembros WHERE user_id > 0"));
		$data['pages'] = $tsCore->pageIndex("/admin/users?o={$_GET['o']}&m={$_GET['m']}", $total, $max);
		//
		return $data;
	}
	public function getUserPrivacidad() {
		$uid = (int)$_GET['uid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT p_configs FROM @perfil WHERE user_id = $uid LIMIT 1"));
		$data['p_configs'] = unserialize($data['p_configs']);
		//
		return $data;
	}
	public function setUserPrivacidad() {
		global $tsCore;
		//
		$uid = (int)$_GET['uid'];
		$perfilData['configs'] = serialize([
			'm' => $_POST['muro'],
			'mf' => ($_POST['muro_firm'] > 4) ? 5 : $_POST['muro_firm'],
			'rmp' => $_POST['rec_mps'],
			'hits' => ($_POST['last_hits'] == 1 || $_POST['last_hits'] == 2) ? 0 : $_POST['last_hits']
		]);
		//
		$updates = $tsCore->getIUP($perfilData, 'p_');
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET $updates WHERE user_id = $uid")) return true;
	}
	public function getUserData() {
		$user_id = (int)$_GET['uid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.*, r.*, p.* FROM @perfil AS p LEFT JOIN @miembros AS u ON u.user_id = p.user_id LEFT JOIN @rangos AS r ON r.rango_id = u.user_rango WHERE u.user_id = $user_id LIMIT 1"));
		$data['p_configs'] = unserialize($data['p_configs']);
		//
		return $data;
	}
	public function setUserData(int $user_id = 0) {
		global $tsCore;
		$user_id = (int)$_GET['uid'];
		# DATA
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_name`, `user_email`, `user_password` FROM @miembros WHERE user_id = $user_id"));
		# DATOS LOCALES
		$email = empty($_POST['email']) ? $data['user_email'] : $tsCore->setSecure($_POST['email']);
		$password = $_POST['pwd'];
		$cpassword = $_POST['cpwd'];
		$user_nick = empty($_POST['nick']) ? $data['user_name'] : $tsCore->setSecure($_POST['nick']);
		$user_points = empty($_POST['points']) ? (int)$data['user_puntos'] : (int)$_POST['points'];
		$pointsxdar = empty($_POST['pointsxdar']) ? (int)$data['user_puntos'] : (int)$_POST['pointsxdar'];
		$changenames = empty($_POST['changenicks']) ? (int)$data['user_name_changes'] : (int)$_POST['changenicks'];
		// CORREO
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Correo electr&oacute;nico incorrecto';
		// PUNTOS
		if ($user_points >= 0) $apoints = ", user_puntos = $user_points";
		else return 'Los puntos del usuario no se reconocen';
		// CAMBIAR NICK
		if ($changenames >= 0) $changedis = ", user_name_changes = $changenames";
		else return 'Las disponibilidades de cambios de nombre de usuario deben ser num&eacute;ricas.';
		// PUNTOS PARA DAR
		if ($pointsxdar >= 0) $pxd = ", user_puntosxdar = $pointsxdar";
		else return 'Los puntos para dar no se reconocen';
		// CONTRASEÑA
		if (!empty($password) AND !empty($cpassword)) {
			if (strlen($user_nick) < 4) return 'Nick demasiado corto.';
			if (!preg_match('/^([A-Za-z0-9]+)$/', $user_nick)) return 'Nick inv&aacute;lido';
			$new_nick = ", user_name = '$user_nick'";
			// SI LA CONTRASEÑA ES CORTA
			if (strlen($password) < 6) return 'Contrase&ntilde;a no v&aacute;lida.';
			// SI LA CONTRASEÑA NO SON IGUALES
			if ($password != $cpassword) return 'Las contrase&ntilde;as no coinciden';
			$new_key = $tsCore->createPassword($user_nick, $password);
			$db_key = ", user_password = '$new_key'";
		}
		// ACTUALIZAMOS LA TABLA
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_email = '$email'$changedis$new_nick$pxd$apoints$db_key WHERE user_id = $user_id")) {
			if ($_POST['sendata']) {
			  	require_once TS_MODELS . "c.emails.php";
				$tsEmail = new tsEmail;

				$tsEmail->emailTo = $admin[0];
				$tsEmail->emailTemplate = 'default';
				$tsEmail->emailSubject = 'Nuevos datos de acceso';
				$tsEmail->emailBody = "Sus datos de acceso a {$tsCore->settings['titulo']} han sido cambiados por un administrador. Los nuevos datos son:<br>Usuario: <strong>$user_nick</strong><br>Contrase&ntilde;a: <strong>$password</strong><br><br> Disculpe las molestias";
				$tsEmail->sendEmail() or die('0: Hubo un error al enviar el correo.');
			}
			return 'Los cambios fueron aplicados';
		}
	}
	public function deleteContent(int $user_id = 0){
		global $tsCore, $tsUser;
		$password = $tsCore->createPassword($tsUser->nick, $_POST['password']);
		if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_id = {$tsUser->uid} && user_password = '$password'"))){
			$c = $_POST['bocuenta'];
			if($_POST['boposts'] || $c) 
		  		deleteFromId([__FILE__, __LINE__], '@posts', "post_user = $user_id");
			if($_POST['bofotos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@fotos', "f_user = $user_id");
			if($_POST['boestados'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@muro', "p_user_pub = $user_id");
			if($_POST['bocomposts'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@posts_comentarios', "c_user = $user_id");
			if($_POST['bocomfotos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@fotos_comentarios', "c_user = $user_id");
			if($_POST['bocomestados'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@muro_comentarios', "c_user = $user_id");
			if($_POST['bolikes'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@muro_likes', "user_id = $user_id");
			if($_POST['boseguidores'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@follows', "f_id = $user_id && f_type = 1");
			if($_POST['bosiguiendo'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@follows', "f_user = $user_id && f_type = 1");
			if($_POST['bofavoritos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@posts_favoritos', "fav_user = $user_id"); 
			if($_POST['bovotosposts'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@posts_votos', "tuser = $user_id");
			if($_POST['bovotosfotos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@fotos_votos', "v_user = $user_id");
			if($_POST['boactividad'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@actividad', "user_id = $user_id");
			if($_POST['boavisos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@avisos', "user_id = $user_id");
			if($_POST['bobloqueos'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@bloqueos', "b_user = $user_id");
			if($_POST['bomensajes'] || $c) { 
				deleteFromId([__FILE__, __LINE__], '@mensajes', "mp_from = $user_id"); 
				deleteFromId([__FILE__, __LINE__], '@respuestas', "mr_from = $user_id");
			}
			if($_POST['bosesiones'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@sessions', "session_user_id = $user_id");
			if($_POST['bovisitas'] || $c) 
				deleteFromId([__FILE__, __LINE__], '@visitas', "user = $user_id");
		  
		  	$data = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT user_name FROM @miembros WHERE user_id = $user_id"));
		  	$admin = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT user_email FROM @miembros WHERE user_id = 1"));
		  
		  	if($c && $tsUser->uid != $user_id) {
		  		deleteFromId([__FILE__, __LINE__], '@miembros', "user_id = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@perfil', "user_id = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@portal', "user_id = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@denuncias', "d_user = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@bloqueos', "b_auser = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@mensajes', "mp_to = $user_id");
		  		deleteFromId([__FILE__, __LINE__], '@visitas', "`for` = $user_id && type = 1");
		  	}
		  	$avBody = "Hola, le informamos que el administrador {$tsUser->nick} ({$tsUser->uid}) ha eliminado ".($c ? 'la cuenta' : 'varios contenidos')." de {$data[0]}.";
		  	insertDataInBase([__FILE__, __LINE__], '@avisos', [
		  		'user_id' => 1,
		  		'av_subject' => 'Contenido eliminado',
		  		'av_body' => $avBody,
		  		'av_date' => time(),
		  		'av_type' => 1
		  	]);
		  	require_once TS_MODELS . "c.emails.php";
			$tsEmail = new tsEmail;

			$tsEmail->emailTo = $admin[0];
			$tsEmail->emailTemplate = 'delete';
			$tsEmail->emailSubject = 'Contenido eliminado';
			$tsEmail->emailBody = "Contenido de cierta cuenta han sido eliminados.<br>$avBody";
			$tsEmail->sendEmail() or die('0: Hubo un error al enviar el correo.');
		 
		  	return 'OK';
		} else return 'Credenciales incorrectas';
	}
	public function getUserRango(int $user_id = 0) {
		# CONSULTA
		$data['user'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_rango, r.rango_id, r.r_name, r.r_color FROM @miembros AS u LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id WHERE u.user_id = $user_id LIMIT 1"));
		# RANGOS DISPONIBLES
		$data['rangos'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT rango_id, r_name, r_color FROM @rangos'));
		#
		return $data;
	}
	public function getAllRangos() {
		# RANGOS DISPONIBLES
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT rango_id, r_name, r_color FROM @rangos'));
		#
		return $data;
	}
	public function setUserRango(int $user_id = 0) {
		global $tsUser;
		# SOLO EL PRIMER ADMIN PUEDE PONER A OTROS ADMINS
		$new_rango = (int)$_POST['new_rango'];
		if ($user_id == $tsUser->uid) return 'No puedes cambiarte el rango a ti mismo';
		elseif ($tsUser->uid != 1 && $new_rango == 1) return 'Solo el primer Administrador puede crear más administradores principales';
		else {
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_rango = $new_rango WHERE user_id = $user_id ")) return 'El rango fue cambiado correctamente';
		}
	}
	public function setUserFirma(int $user_id = 0) {
		global $tsCore;
		$firma = $tsCore->setSecure($_POST['firma']);
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_firma = '$firma' WHERE user_id = $user_id")) return 'La firma se cambio correctamente';
	}
	public function setUserInActivo() {
		global $tsUser;
		//
		$usuario = (int)$_POST['uid'];
		$data = db_exec('fetch_assoc',db_exec([__FILE__, __LINE__], 'query', "SELECT user_activo FROM @miembros WHERE user_id = $usuario"));
		$user_activo = ((int)$data['user_activo'] === 1);
		$act = $user_activo ? 0 : 1;
		$des = $user_activo ? "des" : "";
		$t = $user_activo ? 2 : 1;
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_activo = $act WHERE user_id = $usuario")) ? "$t: Cuenta {$des}activada" : "0: Ocurri&oacute, un error";
	}
	public function setUserAvatar(int $user_id = 0) {
     	global $tsCore;
     	$avatar_link = $tsCore->setSecure($_POST['avatar_link']);
     	return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @perfil SET user_avatar_gif = 1, user_avatar = '$avatar_link' WHERE user_id = $user_id"));
   }
   public function setUserAct(int $user_id = 0) {
     	$avatar_gif = (int)$_POST['avatar_active'];
     	return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET c_avatar = $avatar_gif WHERE tscript_id = 1"));
    }
	# ===================================================
	# SESIONES
	# * getSessions() :: Obtenemos todas las sesiones
	# * delSession() :: Eliminamos la sesión
	# ===================================================
	public function getSessions() {
		global $tsCore;
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, s.* FROM @sessions AS s LEFT JOIN @miembros AS u ON s.session_user_id = u.user_id ORDER BY s.session_time DESC LIMIT ' . $limit));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @sessions'));
		$data['pages'] = $tsCore->pageIndex("/admin/sesiones?", $total, $max);
		//
		return $data;
	}
	public function delSession() {
		global $tsCore;
		$session_id = $tsCore->setSecure($_POST['sesion_id']);
		if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT session_id FROM @sessions WHERE session_id = '$session_id' LIMIT 1"))) {
			if(deleteFromId([__FILE__, __LINE__], '@sessions', "session_id = '$session_id'")) return '1: Eliminado';
		} else return '0: No existe esa sesi&oacute;n';
	}
	# ===================================================
	# CAMBIOS DE NICK
	# * getChangeNicks() :: Obtenemos todos los nicks
	# * ChangeNick_o_no() :: Aprobamos/Denegamos nick
	# ===================================================
	# ===================================================
	# CAMBIOS DE NICK
	# * getChangeNicks() :: Obtenemos todos los nicks
	# * ChangeNick_o_no() :: Aprobamos/Denegamos nick
	# ===================================================
	public function getChangeNicks(string $realizado = '') {
		global $tsCore;
		# Cambio realizado
		$hecho = 'estado ' . ($realizado === 'realizados' ? ">" : "=") . ' 0';
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, n.* FROM @nicks AS n LEFT JOIN @miembros AS u ON n.user_id = u.user_id WHERE $hecho ORDER BY n.time DESC LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM @nicks WHERE estado = 0"));
		$data['pages'] = $tsCore->pageIndex("/admin/nicks?", $total, $max);
		//
		return $data;
	}
	public function ChangeNick_o_no() {
		global $tsCore, $tsMonitor;
		//
		$nick_id = (int)$_POST['nid'];
		$aprobado = ($_POST['accion'] == 'aprobar');
		  //
		$datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT id, user_id, user_email, name_1, name_2, hash, 'time', ip, estado FROM @nicks WHERE id = $nick_id LIMIT 1"));
		$nickA['name_changes'] = 'user_name_changes - 1';
		if ($aprobado) {
			$nickA['name'] = $datos['name_2'];
			$nickA['password'] = $datos['hash'];
		}
		$set = $tsCore->getIUP($nickA, 'user_');
		$estado = $aprobado ? 1 : 2;
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET $set WHERE user_id = {$datos['user_id']}");
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @nicks SET estado = $estado WHERE id = $nick_id");
		// AVISO
		$aviso = "Hola <strong>{$datos['name_1']}</strong>,\n\n";
		$aviso .= $aprobado ? "Le informo que desde este momento su nombre de acceso ser&aacute; <strong>{$datos['name_2']}</strong> . Hasta pronto." : " Lamento informarle que su petici&oacute;n de cambio de nick a <strong>{$datos['name_2']}</strong>, ha sido denegada.";
		$tsMonitor->setAviso($datos['user_id'], 'Cambio realizado', $aviso, ($aprobado ? 4 : 3));
		// ENVIAMOS CORREO
		$subject = "{$datos['name_1']}, su petición de cambio ha sido " . ($_POST['accion'] == 'aprobar' ? 'aprobada' : 'denegada');
		$body = "Hola {$datos['name_1']}:<br />Le enviamos este email para informarle que su petici&oacute;n de cambio de nick ha sido";
		$body .= $aprobado ? " aceptada. <br> Desde este momento, podr&aacute; acceder en {$tsCore->settings['titulo']} con el nombre de usuario {$datos['name_2']}." : " denegada";
		// <--
		require_once TS_MODELS . "c.emails.php";
		$tsEmail = new tsEmail;

		$tsEmail->emailTo = $datos['user_email'];
		$tsEmail->emailTemplate = 'default';
		$tsEmail->emailSubject = $subject;
		$tsEmail->emailBody = $body;
		$tsEmail->sendEmail() or die('0: Hubo un error al enviar el correo.');
		 die("1: <div class=\"box_cuerpo\" style=\"padding: 12px 20px; border-top:1px solid #CCC\">Hemos enviado un correo a <strong>{$datos['user_email']}</strong> con la decisi&oacute;n tomada. Tambi&eacute;n le hemos enviado un aviso al usuario.</div>");
		  // -->
	}
	# ===================================================
	# POSTS
	# * GetAdminPosts() :: Obtenemos todos los posts
	# ===================================================
	public function GetAdminPosts()
	 {
		  global $tsCore;
		  //
		  $max = 18; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);

		  if ($_GET['o'] == 'e')
		  {
				$order = 'p.post_status';
		  } elseif ($_GET['o'] == 'ip')
		  {
				$order = 'p.post_ip';
		  } else
		  {
				$order = 'p.post_id';
		  }

		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, c.c_nombre, c.c_seo, c.c_img, p.* FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_id > \'0\' ORDER BY ' .
				$order . ' ' . ($_GET['m'] == 'a' ? 'ASC' : 'DESC') . ' LIMIT ' . $limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @posts WHERE post_id > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex("/admin/posts?o={$_GET['o']}&m={$_GET['m']}", $total, $max);
		  //
		  return $data;
	 }


	 /****************** ADMINISTRACIÓN DE FOTOS ******************/
	 function GetAdminFotos()
	 {
		  global $tsCore;
		  //
		  $max = 15; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, f.* FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.foto_id > \'0\' ORDER BY f.foto_id DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @fotos WHERE foto_id > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex("/admin/fotos?", $total, $max);
		  //
		  return $data;
	 }

	 function DelFoto()
	 {
		  //
		  $foto = intval($_POST['foto_id']);
		  if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT foto_id FROM @fotos WHERE foto_id = \'' .
				(int)$foto . '\'')))
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @fotos WHERE foto_id = \'' . (int)$foto . '\''))
				{
					 return '1: Foto eliminada';
				} else
					 return '0: La foto no se pudo eliminar';
		  } else
				return '0: La foto no existe';

	 }

	 function setOpenClosedFoto()
	 {
		  global $tsUser;

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT f_closed FROM @fotos WHERE foto_id = \'' . (int)$_POST['fid'] .
				'\'');
		  $data = db_exec('fetch_assoc', $query);

		  // COMPROBAMOS
		  if ($data['f_closed'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @fotos SET f_closed = \'0\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '2: Comentarios abiertos';
				} else
					 return '0: Ocurri&oacute, un error';
		  } elseif ($data['f_closed'] == 0)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @fotos SET f_closed = \'1\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '1: Comentarios cerrados.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }


	 function setShowHideFoto()
	 {
		  global $tsUser;

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT f_status FROM @fotos WHERE foto_id = \'' . (int)$_POST['fid'] .
				'\'');
		  $data = db_exec('fetch_assoc', $query);


		  // COMPROBAMOS
		  if ($data['f_status'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @fotos SET f_status = \'0\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '2: Foto rehabilitada';
				} else
					 return '0: Ocurri&oacute, un error';
		  } elseif ($data['f_status'] == 0)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @fotos SET f_status = \'1\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '1: Foto deshabilitada.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }


	 /****************** ADMINISTRACIÓN DE NOTICIAS ******************/

	 function setNoticiaInActive()
	 {
		  global $tsUser;

		  $noticia = $_POST['nid'];

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT not_active FROM @noticias WHERE not_id = \'' . (int)
				$noticia . '\'');
		  $data = db_exec('fetch_assoc', $query);


		  // COMPROBAMOS
		  if ($data['not_active'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @noticias SET not_active = \'0\' WHERE not_id = \'' . (int)
					 $noticia . '\''))
				{
					 return '2: Noticia desactivada';
				} else
					 return '0: Ocurri&oacute, un error';
		  } else
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @noticias SET not_active = \'1\' WHERE not_id = \'' . (int)
					 $noticia . '\''))
				{
					 return '1: Noticia activada.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }
	# ===================================================
	# LISTA NEGRA
	# * getBlackList() :: Obtenemos todos los bloqueos
	# * getBlock() :: Obtenemos bloqueo de la lista
	# * saveBlock() :: Guardamos el bloqueo en la lista
	# * newBlock() :: Creamos nuevo bloqueo en la lista
	# * deleteBlock() :: Eliminamos el bloqueo de la lista
	# ===================================================
	public function getBlackList() {
		global $tsCore;
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);
		//
		$data['data'] = result_array( db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, b.* FROM @blacklist AS b LEFT JOIN @miembros AS u ON b.author = u.user_id ORDER BY b.date DESC LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @blacklist'));
		$data['pages'] = $tsCore->pageIndex("/admin/blacklist?", $total, $max);
		//
		return $data;
	}
	public function getBlock() {
		$id = (int)$_GET['id'];
		return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT type, value, reason FROM @blacklist WHERE id = $id LIMIT 1"));
	}
	public function saveBlock() {
		global $tsCore, $tsUser;
		// Verificamos campos
		if (empty($_POST['value']) || empty($_POST['type'])) return 'Debe rellenar todos los campos';
		// No lo pongo antes, ya que tiene que verificar el contenido del mismo
		$type = (int)$_POST['type'];
		$value = $tsCore->setSecure($_POST['value']);
		$id = (int)$_GET['id'];
		// Verificamos la IP
		if ($type == 1 && $value == $_SERVER['REMOTE_ADDR']) return 'No puedes bloquear tu propia IP';
		// Ya existe el bloqueo?
		if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @blacklist WHERE type = $type && value = '$value'"))) return 'Ya existe un bloqueo as&iacute;';
		// Actualizamos tabla
		$set = $tsCore->getIUP([
			'type' => $type, 
			'value' => $value, 
			'author' => $tsUser->uid
		]);
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @blacklist SET $set WHERE id = $id")) return true;		 
	}
	public function newBlock() {
		global $tsCore, $tsUser;
		// Comprobamos todos los campos
		if (in_array('', $_POST)) return 'Rellene todos los campos';
		// Datos 
		$type = (int)$_POST['type'];
		$value = $tsCore->setSecure($_POST['value']);
		$reason = $tsCore->setSecure($_POST['reason']);
		// Verificamos la IP
		if ($_POST['type'] == 1 && $_POST['value'] == $_SERVER['REMOTE_ADDR']) return 'No puedes bloquear tu propia IP';
		// Ya existe el bloqueo?...
		if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @blacklist WHERE type = $type && value = '$value'"))) return 'Ya existe un bloqueo as&iacute;';
		// Insertamos los datos
		if (insertDataInBase([__FILE__, __LINE__], '@blacklist', ['type' => $type, 'value' => $value, 'reason' => $reason, 'author' => $tsUser->uid, 'date' => time()])) return true;
	}
	public function deleteBlock() {
		$id = (int)$_POST['bid'];
		return (deleteFromId([__FILE__, __LINE__], '@blacklist', "id = $id")) ? '1: Bloqueo retirado' : '0: Hubo un error al borrar';
	}
	# ===================================================
	# CENSURA
	# * getBadWords() :: Obtenemos todas las censuras
	# * getBadWord() :: Obtenemos censuras de la lista
	# * saveBlock() :: Guardamos el censuras en la lista
	# * newBlock() :: Creamos nuevo censuras en la lista
	# * deleteBlock() :: Eliminamos el censuras de la lista
	# ===================================================
	public function getBadWords() {
		global $tsCore;
		//
		$max = 20; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, bw.* FROM @badwords AS bw LEFT JOIN @miembros AS u ON bw.author = u.user_id ORDER BY bw.wid DESC LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @badwords'));
		$data['pages'] = $tsCore->pageIndex("/admin/badwords?", $total, $max);
		//
		return $data;
	}
	public function getBadWord() {
		$wid = (int)$_GET['id'];
		return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM @badwords WHERE wid = $wid LIMIT 1"));
	}

	 function saveBadWord()
	 {
		  global $tsCore, $tsUser;

		  $method = empty($_POST['method']) ? 0 : 1;
		  $type = empty($_POST['type']) ? 0 : 1;
		  if (empty($_POST['before']) || empty($_POST['after']))
		  {
				return 'Rellene todos los campos';
		  } else
		  {
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT wid FROM @badwords WHERE LOWER(word) = \'' .
					 $tsCore->setSecure(strtolower($_POST['before'])) . '\' && LOWER(swop) = \'' . $tsCore->
					 setSecure(strtolower($_POST['after'])) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE @badwords SET method = \'' . $method . '\', type = \'' .
						  (int)$type . '\', word = \'' . $tsCore->setSecure($_POST['before']) . '\', swop = \'' .
						  $tsCore->setSecure($_POST['after']) . '\', author = \'' . $tsUser->uid . '\' WHERE wid = \'' .
						  (int)$_GET['id'] . '\''))
						  return true;
					 else
						  return 'Error al guardar';
				} else
					 return 'Ya existe un filtro as&iacute;';
		  }
	 }

	 function newBadWord()
	 {
		  global $tsCore, $tsUser;

		  $method = empty($_POST['method']) ? 0 : 1;
		  $type = empty($_POST['type']) ? 0 : 1;
		  if (empty($_POST['before']) || empty($_POST['after']) || empty($_POST['reason']))
		  {
				return 'Rellene todos los campos';
		  } else
		  {
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT wid FROM @badwords WHERE LOWER(word) = \'' .
					 $tsCore->setSecure(strtolower($_POST['before'])) . '\' && LOWER(swop) = \'' . $tsCore->
					 setSecure(strtolower($_POST['after'])) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @badwords (word, swop, method, type, author, reason, date) VALUES (\'' .
						  $tsCore->setSecure($_POST['before']) . '\', \'' . $tsCore->setSecure($_POST['after']) .
						  '\', \'' . (int)$method . '\', \'' . (int)$type . '\', \'' . $tsUser->uid . '\', \'' .
						  $tsCore->setSecure($_POST['reason']) . '\', \'' . time() . '\')'))
						  return true;
					 else
						  return 'Error al agregar';
				} else
					 return 'Ya existe un filtro as&iacute;';
		  }
	 }

	 function deleteBadWord()
	 {

		  if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @badwords WHERE wid = \'' . (int)$_POST['wid'] . '\''))
				return '1: Filtro retirado';
		  else
				return '0: Hubo un error al borrar';

	 }
	# ===================================================
	# ESTADÍSTICASA
	# * GetAdminStats() :: Obtenemos todas las estadisticas
	# ===================================================
	public function GetAdminStats() {
		$num = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT 
			(SELECT count(foto_id) FROM @fotos WHERE f_status = 2) as fotos_eliminadas, 
			(SELECT count(foto_id) FROM @fotos WHERE f_status = 1) as fotos_ocultas, 
			(SELECT count(foto_id) FROM @fotos WHERE f_status = 0) as fotos_visibles, 
			(SELECT count(post_id) FROM @posts WHERE post_status = 0) as posts_visibles, 
			(SELECT count(post_id) FROM @posts WHERE post_status = 1) as posts_ocultos, 
			(SELECT count(post_id) FROM @posts  WHERE post_status = 2) as posts_eliminados, 
			(SELECT count(post_id) FROM @posts  WHERE post_status = 3) as posts_revision, 
			(SELECT count(cid) FROM @posts_comentarios WHERE c_status = 0) as comentarios_posts_visibles, 
			(SELECT count(cid) FROM @posts_comentarios WHERE c_status = 1) as comentarios_posts_ocultos, 
			(SELECT count(user_id) FROM @miembros WHERE user_activo = 1) as usuarios_activos, 
			(SELECT count(user_id) FROM @miembros WHERE user_activo = 0 ) as usuarios_inactivos, 
			(SELECT count(user_id) FROM @miembros WHERE user_baneado = 1 ) as usuarios_baneados, 
			(SELECT count(cid) FROM @fotos_comentarios) as comentarios_fotos_total, 
			(SELECT count(follow_id) FROM @follows WHERE f_type  = 1 ) AS usuarios_follows,
			(SELECT count(follow_id) FROM @follows WHERE f_type  = 2 ) AS posts_follows,
			(SELECT count(follow_id) FROM @follows WHERE f_type  = 3 ) AS posts_compartidos,
			(SELECT count(fav_id) FROM @posts_favoritos) AS posts_favoritos,  
			(SELECT count(mr_id) FROM @respuestas) AS usuarios_respuestas,
			(SELECT count(mp_id) FROM @mensajes) AS mensajes_total, 
			(SELECT count(mp_id) FROM @mensajes WHERE mp_del_to = 1) AS mensajes_de_eliminados,
			(SELECT count(mp_id) FROM @mensajes WHERE mp_del_from = 1) AS mensajes_para_eliminados,
			(SELECT count(bid) FROM @posts_borradores) AS posts_borradores,
			(SELECT count(bid) FROM @bloqueos) AS usuarios_bloqueados, 
			(SELECT count(bid) FROM @bloqueos) AS usuarios_bloqueados,
			(SELECT count(medal_id) FROM @medallas WHERE m_type = 1) AS medallas_usuarios,
			(SELECT count(medal_id) FROM @medallas WHERE m_type = 2) AS medallas_posts,
			(SELECT count(medal_id) FROM @medallas WHERE m_type = 3) AS medallas_fotos,
			(SELECT count(id) FROM @medallas_assign) AS medallas_asignadas, 
			(SELECT count(aid) FROM @afiliados WHERE a_active = 1) AS afiliados_activos, 
			(SELECT count(aid) FROM @afiliados WHERE a_active = 0) AS afiliados_inactivos,
			(SELECT count(pub_id) FROM @muro) AS muro_estados, 
			(SELECT count(cid) FROM @muro_comentarios) AS muro_comentarios
		'));
		$num['usuarios_total'] = $num['usuarios_activos'] + $num['usuarios_inactivos'] + $num['usuarios_baneados'];
		$num['seguidos_total'] = $num['posts_follows'] + $num['usuarios_follows'];
		$num['muro_total'] = $num['muro_estados'] + $num['muro_comentarios'];
		$num['afiliados_total'] = $num['afiliados_activos'] + $num['afiliados_inactivos'];
		$num['posts_total'] = $num['posts_visibles'] + $num['posts_ocultos'] + $num['posts_eliminados'];
		$num['comentarios_posts_total'] = $num['comentarios_posts_visibles'] + $num['comentarios_posts_ocultos'];
		$num['medallas_total'] = $num['medallas_usuarios'] + $num['medallas_posts'] + $num['medallas_fotos'];
		$num['fotos_total'] = $num['fotos_visibles'] + $num['fotos_ocultas'] + $num['fotos_eliminadas'];
		return $num;
	}

	public function setUsuarioVerificado() {
		global $tsCore;
      $user = (int)$_GET['id'];
      $verificar = db_exec('fetch_array', db_exec([__FILE__, __LINE__], 'query', "SELECT user_verificado FROM @miembros WHERE user_id = $user"));
      $cambiar = ((int)$verificar['user_verificado'] === 1) ? 0 : 1;
      if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_verificado = $cambiar WHERE user_id = $user")) {
      	return $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user.'&save=true');
      } else return false;
   } 

   public function getHtaccess() {
   	return file_get_contents(TS_ROOT . '.htaccess');
   }

   public function createCopy() {
   	return (copy(TS_ROOT . '.htaccess', TS_ROOT . '.htaccess-bak'));
   }

   public function setErrorDesc() {
   	return [
   		400 => [
   			'type' => 'Bad Request',
   			'description' => 'El servidor no puede o no procesará la solicitud debido a un error del cliente.'
   		], 
   		401 => [
   			'type' => 'Unauthorized',
   			'description' => 'La solicitud requiere autenticación del usuario. El usuario no está autorizado para acceder al recurso.'
   		], 
   		403 => [
   			'type' => 'Forbidden',
   			'description' => 'El servidor ha entendido la solicitud, pero se niega a autorizarla.'
   		], 
   		404 => [
   			'type' => 'Not Found',
   			'description' => 'El servidor no puede encontrar el recurso solicitado.'
   		], 
   		500 => [
   			'type' => 'Internal Server Error',
   			'description' => 'Error genérico del servidor cuando se encuentra una condición inesperada.'
   		], 
   		501 => [
   			'type' => 'Not Implemented',
   			'description' => 'El servidor no puede cumplir con la solicitud debido a que no tiene la funcionalidad necesaria.'
   		]
   	];
   }

   public function getError() {
   	// Lee el contenido del archivo .htaccess
   	$content = $this->getHtaccess();
   	// Divide el contenido en líneas
   	$lines = explode("\n", $content);
   	// Inicializa el array de salida
   	$salida = [];
   	// Itera sobre cada línea
   	foreach ($lines as $line) {
   	   // Limpia los espacios en blanco al principio y al final de la línea
   	   $line = trim($line);
   	   // Verifica si la línea comienza con ErrorDocument
   	   if (preg_match('/^#?ErrorDocument/', $line)) {
   			$error = explode(' ', $line);
   	      // Determina si la línea está comentada
   	      $isActive = $line[0] !== '#';
   	      // Agrega la información al array de salida
   	      $salida[] = [
   	         'active' => $isActive,
   	         'lines'   => $error,
   	         ...$this->setErrorDesc()[$error[1]]
   	      ];
   	   }
   	}
   	// Muestra las líneas (opcional)
   	return $salida;
   }

   public function saveError() {
   	// Obtiene el contenido actual del archivo .htaccess
   	$content = $this->getHtaccess();
   	// Divide el contenido en líneas
   	$lines = explode("\n", $content);
   	
   	// Define los errores y sus líneas correspondientes
   	$errores = [400, 401, 403, 404, 500, 501];
   	$newLines = [];
    
    	// Genera las nuevas líneas para cada error basado en $_POST
    	foreach ($errores as $error) {
        	$hash = (in_array($error, $_POST['error'])) ? '' : '#';
        	$newLines[] = "{$hash}ErrorDocument $error /$error.html";
    	}
    
    	// Itera sobre las líneas actuales del archivo
   	foreach ($lines as $line) {
   	  	$line = trim($line);
   	  	// Verifica si la línea contiene ErrorDocument
   	  	if (preg_match('/^#?ErrorDocument (\d+)/', $line, $matches)) {
   	  	   $errorCode = $matches[1];
   	  	   if (in_array($errorCode, $errores)) {
   	  	      continue;
   	  	   }
   	  	}
   	  	$newLines[] = $line;
   	}
    	// Asegura que cada línea esté en una nueva línea
    	$newContent = implode("\n", $newLines);
    	// Asegura que el contenido esté en UTF-8 antes de guardar
    	$newContent = mb_convert_encoding($newContent, 'UTF-8', 'UTF-8');
   	// Guarda el contenido nuevo en el archivo .htaccess
    	if(file_put_contents(TS_ROOT . '.htaccess', $newContent)) {
    		return true;
    	}
    	return false;
   }

   public function getRewriteRules() {
	   // Lee el contenido del archivo .htaccess
	   $content = $this->getHtaccess();
	   // Divide el contenido en líneas
	   $lines = explode("\n", $content);
	   // Inicializa el array de salida
	   $salida = [
	      'base' => [
	         'active' => false,
	         'site' => ''
	      ],
	      'rules' => []
	   ];
	   // Bandera para detectar si estamos en el bloque de Rewrite
	   $inRewriteBlock = false;
	   
	   // Itera sobre cada línea
	   foreach ($lines as $line) {
	      // Limpia los espacios en blanco al principio y al final de la línea
	      $line = trim($line);
	      // Verifica si la línea es RewriteBase
	      if (preg_match('/^#?RewriteBase/', $line)) {
	         $inRewriteBlock = true;
	         $isActive = ($line[0] !== '#') ? 1 : 0;
	         $site = trim(str_replace(['RewriteBase', '/'], '', $line));
	         $salida['base'] = [
	            'active' => $isActive,
	            'site' => $site
	         ];
	         continue;
	      }
	      
	      // Verifica si la línea es RewriteCond o RewriteRule dentro del bloque de Rewrite
	      if ($inRewriteBlock && preg_match('/^#?RewriteBase|RewriteCond|RewriteRule/', $line)) {
	         $salida['rules'][] = $line;
	      } elseif (empty($line) || preg_match('/^#/', $line)) {
	         // Si la línea está vacía o es un comentario, resetea la bandera del bloque de Rewrite
	         $inRewriteBlock = false;
	      }
	   }
	   return $salida;
	}

	public function saveRewriteRules() {
		global $tsCore;
	   // Obtiene el contenido actual del archivo .htaccess
	   $content = $this->getHtaccess();
	   // Divide el contenido en líneas
	   $lines = explode("\n", $content);
	   
	   // Reglas predeterminadas
	   $defaultRules = [
	      '#RewriteBase /',
	      '#RewriteCond %{SERVER_PORT} 80',
	      '#RewriteCond %{HTTP_HOST} ^http://TU_SITIO_WEB.com[NC,OR]',
	      '#RewriteRule ^(.*)$ https://TU_SITIO_WEB.com/$1 [L,R=301,NC]'
	   ];
	   // Obtiene los datos de $_POST
	   $baseActive = (isset($_POST['active']) AND (int)$_POST['active'] === 1) ? true : false;
	   $site = isset($_POST['site']) ? $tsCore->setSecure($_POST['site']) : 'TU_SITIO_WEB.com';
	   
		// Prepara las nuevas líneas para la sección de Rewrite
		$newRewriteLines = [];
		$hash = $baseActive ? '' : '#'; // No agregar `#` si está activo
		$newRewriteLines[] = "{$hash}RewriteBase /";
		$newRewriteLines[] = "{$hash}RewriteCond %{SERVER_PORT} 80";
		$newRewriteLines[] = "{$hash}RewriteCond %{HTTP_HOST} ^http://{$site}[NC,OR]";
		$newRewriteLines[] = "{$hash}RewriteRule ^(.*)$ https://{$site}/$1 [L,R=301,NC]";
		  
		// Índice de las líneas que se deben reemplazar
		$startLine = 12; // Cambia esto al índice de la primera línea que deseas reemplazar
		$endLine = 15; // Cambia esto al índice de la última línea que deseas reemplazar

		// Reemplaza las líneas específicas del bloque de reescritura
		$i = 0;
		foreach ($lines as &$line) {
		   $i++;
		   if ($i >= $startLine && $i <= $endLine) {
		      $line = ($i - $startLine < count($newRewriteLines)) ? $newRewriteLines[$i - $startLine] : '';
		   }
		}

   	// Si hay más nuevas líneas que líneas para reemplazar, agregar las nuevas líneas restantes
   	if (count($newRewriteLines) > ($endLine - $startLine + 1)) {
   	   $extraLines = array_slice($newRewriteLines, $endLine - $startLine + 1);
   	   $lines = array_merge(array_slice($lines, 0, $endLine), $extraLines, array_slice($lines, $endLine));
   	}

   	// Asegura que cada línea esté en una nueva línea
   	$newContent = implode("\n", $lines);
   	// Asegura que el contenido esté en UTF-8 antes de guardar
   	$newContent = mb_convert_encoding($newContent, 'UTF-8', 'UTF-8');
	   // Guarda el contenido nuevo en el archivo .htaccess
	   file_put_contents(TS_ROOT . '.htaccess', $newContent);
	}


}
