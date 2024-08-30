<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los posts
 *
 * @name    c.posts.php
 * @author  Miguel92 & PHPost.es
 */

class tsPosts {

	public $isAdmodSeeMod;
	public $isMember;

	public function __construct() {
		global $tsCore, $tsUser;
		//
		$this->isAdmodSeeMod = ($tsUser->is_admod AND ((int)$tsCore->settings['c_see_mod'] === 1));
		$this->isMember = $tsUser->is_member;
	}


	private function redirectLinkPost(array $isArray = []) {
		global $tsCore;
		$tsDir = $tsCore->createLink('post', $isArray);
		header("Location: $tsDir");
	}

	/** 
	 * isAdmod($prefix, $addSql)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmod(string $prefix = 'u.', string $addSql = '') {
		return $this->isAdmodSeeMod ? '' : "AND {$prefix}user_activo = 1 AND {$prefix}user_baneado = 0 $addSql";
	}
	
	/** 
	 * isAdmodPost('u.', 'p.', 'AND cm.status = 0'$fix, $add)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmodPost(string $prefix = 'u.', string $prefixSecondary = 'p.', string $append = '') {
	   return $this->isAdmodSeeMod ? "{$prefixSecondary}post_id > 0" : " {$prefix}user_activo = 1 && {$prefix}user_baneado = 0 && {$prefixSecondary}post_status = 0 $append";
	}

	/**
	 * Acortador de post automático 
	 * @author KMario19
	 * Formateado por
	 * @author Miguel92
	 * @link https://www.phpost.net/foro/topic/24984-mod-acortador-de-post-autom%C3%A1tico/
	*/
	public function short_url_post() {
		# Obtenemos el nombre del post!
		$post = (int)$_GET['p'];
		# Adicionamos si es administrador o no! 
		$admod = $this->isAdmod();
		# Buscamos el post en la base
		$dataArray = db_exec('fetch_assoc', $search = db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_title, p.post_category, p.post_user, u.user_name, c.* FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE p.post_id = $post AND p.post_status = 0 {$admod}"));
		foreach($dataArray as $pid => $post) $dataArray['post_title'] = stripslashes($post['post_title']);
		# Si no existe redirecciomos a la página posts
		if(!db_exec('num_rows', $search)){
			$this->redirectLinkPost();
			die;
		}
		$this->redirectLinkPost($dataArray);
	}

	/*
      OBTENER LOS TITULOS DE LOS POSTS ANTERIOR/SIGUIENTE
   */
	public function getTitles(string $from = '') {
	   global $tsCore;
	   $postid = (int)$_GET["post_id"];
	   $majorOrMinor = ($from === 'prev') ? "<" : ">";
	   $order = ($from === 'prev') ? "DESC" : "ASC";
	   // Consulta para obtener el post más cercano en la dirección deseada
	   $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], "query", "SELECT post_id, post_title, c_seo FROM @posts LEFT JOIN @posts_categorias ON post_category = cid WHERE post_status = 0 AND post_id $majorOrMinor $postid ORDER BY post_id $order LIMIT 1"));
	   if (!empty($data)) {
	      $data['post_title'] = stripslashes($data['post_title']);
	      $data["post_url"] = $tsCore->createLink('post', [
	         'c_seo' => $data['c_seo'],
	         'post_id' => $data['post_id'],
	         'post_title' => $data['post_title']
	      ]);
	   }
	   return !empty($data) ? $data : false;
	}


	/**
	 * setNP()
	 * @access public
	 * return redirecciona a post
	*/
	public function setNP() {
		// Tipo de acción
		$action = $_GET['action'];
		// Es administrador, moderador o especial
		$isAdmod = $this->isAdmod();
		$order = ($action == 'fortuitae') ? 'RAND() DESC' : 'p.post_id ' . ($action === 'prev' ? 'DESC' : 'ASC');
		if($action != 'fortuitae') {
			$pid = isset($_GET['id']) ? (int) $_GET['id'] : 1;
			$isAdmod .= ' AND p.post_id ' . ($action === 'prev' ? "< " : "> ") . $pid;
		}
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_category, p.post_title, u.user_name, c.c_nombre, c.c_seo FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 $isAdmod ORDER BY $order LIMIT 1") or exit(show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db'));
		if(!db_exec('num_rows', $query)) $this->redirectLinkPost();
		$queryData = db_exec('fetch_assoc', $query);
		$this->redirectLinkPost($queryData);
	}
	
	/**
	 * @access public
	 * @return array
	*/
	public function getCatData(string $category = '') {
		global $tsCore;
		// Obtenemos categoría
		$category = $tsCore->setSecure($category);
		$mostramos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_nombre, c_seo, c_img, c_color, c_descripcion FROM @posts_categorias WHERE c_seo = '{$category}' LIMIT 1"));
		$mostramos['c_img'] = $tsCore->imageCat($mostramos['c_img']);
		return $mostramos;
	}

	private function getLastForeach($setForeach) {
		global $tsImages, $tsCore, $tsUser;
		foreach ($setForeach as $pid => $post) {
			# URL completa de la portada del post!
			$setForeach[$pid]['post_portada'] = $tsImages->setImageCover($post['post_id'], $post['post_portada'], $post['post_body']);
			# URL completa de la imagen de categoría
			$setForeach[$pid]['c_img'] = $tsCore->imageCat($post['c_img']);
			$setForeach[$pid]['post_title'] = stripslashes($post['post_title']);
			# Ya vio el post?
			include_once TS_CLASS . "c.visitas.php";
			$tsVisitas = new tsVisitas;
			$setForeach[$pid]['visto'] = $tsVisitas->wasVisited($post['post_id'], 3, "1");
			# Creamos la URL del post
			$setForeach[$pid]['post_url'] = $tsCore->createLink('post', [
				'c_seo' => $post['c_seo'],
				'post_id' => $post['post_id'],
				'post_title' => $post['post_title']
			]);
	      $setForeach[$pid]['post_new'] = $tsCore->tagsNew($post['post_date']);
		}
		return $setForeach;
	}

	private function getLastSQL() {
		$isAdmod = $this->isAdmod();
		$isAdmodPost = $this->isAdmodPost();
		return "SELECT p.post_id, p.post_user, p.post_category, p.post_title, p.post_hits, p.post_body, p.post_portada, p.post_date, p.post_comments, p.post_puntos, p.post_private, p.post_sponsored, p.post_status, p.post_sticky, u.user_id, u.user_name, u.user_activo, u.user_baneado, c.c_nombre, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id $isAdmod LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE $isAdmodPost";
	}

	/**
	 * @access public
	 * @param string
	 * @param bool
	 * @return arrat
	*/
	public function getLastPostsStickys() {
		// TIPO DE POSTS A MOSTRAR
		$sentencia = $this->getLastSQL() . " AND p.post_sticky = 1 ORDER BY p.post_id DESC LIMIT 5";
		$result = result_array(db_exec([__FILE__, __LINE__], 'query', $sentencia));
		return $this->getLastForeach($result);
	}

	public function getLastPosts(string $category = NULL) {
		global $tsCore;
		// TIPO DE POSTS A MOSTRAR
		$c_where = '';
		$p_where = '';
		if(!empty($category)) {
			$category = $tsCore->setSecure($category);
			// EXISTE LA CATEGORIA?
		 	$cid = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_categorias WHERE c_seo = '$category' LIMIT 1"))['cid'];
		 	if($cid > 0) {
		 		$c_where = 'AND p.post_category = ' . $cid;
		 		$p_where = ' && post_category = ' . $cid;
		 	}
		}
		// TOTAL DE POSTS
		$isAdmodPost = $this->isAdmodPost();
		$isAdmod = $this->isAdmod();
		$posts['total'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(p.post_id) AS total FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE $isAdmodPost $p_where AND p.post_sticky = 0"))[0];
		//
		$lastPosts['pages'] = $tsCore->system_pagination($posts['total'], $tsCore->settings['c_max_posts']);
		$limit = $tsCore->setPageLimit($tsCore->settings['c_max_posts'], false, $posts['total']);
		
		$query = db_exec([__FILE__, __LINE__], 'query', $this->getLastSQL() . " $c_where AND p.post_sticky = 0 GROUP BY p.post_id ORDER BY p.post_id DESC LIMIT $limit");

		$lastPosts['data'] = $this->getLastForeach(result_array($query));
		return $lastPosts;
	}
	/*
		getPost()
	*/
	public function getPost(){
		global $tsCore, $tsUser, $tsImages;
		//
		$time = time();
		$post_id = (int)$_GET['post_id'];
		if(empty($post_id)) return array('deleted','Oops! Este post no existe o fue eliminado.');
		// DAR MEDALLA
		$this->DarMedalla($post_id);
		// DATOS DEL POST
		$postData = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.* ,m.*, u.user_id FROM @posts AS c LEFT JOIN @miembros AS u ON c.post_user = u.user_id LEFT JOIN @perfil AS m ON c.post_user = m.user_id  WHERE `post_id` = $post_id {$this->isAdmod} LIMIT 1"));
		//
		if(empty($postData['post_id'])) {
			$tsDraft = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT b_title FROM @posts_borradores WHERE b_post_id = $post_id LIMIT 1"));
			$text = (!empty($tsDraft['b_title'])) ? 'Este post no existe o fue eliminado.' : 'El post fue eliminado!';
			return ['deleted','Oops! ' . $text];
		} elseif($postData['post_status'] == 1 && (!$tsUser->is_admod && $tsUser->permisos['moacp'] == false)) return ['denunciado','Oops! El Post se encuentra en revisi&oacute;n por acumulaci&oacute;n de denuncias.'];
		elseif($postData['post_status'] == 2 && (!$tsUser->is_admod && $tsUser->permisos['morp'] == false)) return ['deleted','Oops! El post fue eliminado!'];
		elseif($postData['post_status'] == 3 && (!$tsUser->is_admod && $tsUser->permisos['mocp'] == false)) return ['denunciado','Oops! El Post se encuentra en revisi&oacute;n, a la espera de su publicaci&oacute;n.'];
		elseif(!empty($postData['post_private']) && empty($tsUser->is_member)) return ['privado', $postData['post_title']];
  
		//ESTADÍSTICAS
		#if((int)$postData['post_cache'] <= $time - ((int)$tsCore->settings['c_stats_cache'] * 60)) {
			// NÚMERO DE COMENTARIOS
			$postData['post_comments'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(u.user_name) AS c FROM @miembros AS u LEFT JOIN @posts_comentarios AS c ON u.user_id = c.c_user WHERE c.c_post_id = $post_id && c.c_status = 0 && u.user_activo = 1 && u.user_baneado = 0"))[0];
			// NÚMERO DE SEGUIDORES
			$postData['post_seguidores'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(u.user_name) AS s FROM @miembros AS u LEFT JOIN @follows AS f ON u.user_id = f.f_user WHERE f.f_type = 2 && f.f_id = $post_id && u.user_activo = 1 && u.user_baneado = 0"))[0];
			// NÚMERO DE SEGUIDORES
			$postData['post_shared'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS m FROM @follows WHERE f_type = 3 && f_id = $post_id"))[0];
			// NÚMERO DE FAVORITOS
			$postData['post_favoritos'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(fav_id) AS f FROM @posts_favoritos WHERE fav_post_id = $post_id"))[0];
			// ACTUALIZAMOS
			$post = $tsCore->getIUP([
				'comments' => $postData['post_comments'],
				'seguidores' => $postData['post_seguidores'],
				'shared' => $postData['post_shared'],
				'favoritos' => $postData['post_favoritos'],
				'cache' => $time
			], 'post_');

		  //ACTUALIZAMOS LAS ESTADÍSTICAS
		  db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET $post WHERE post_id = $post_id");
		#}
		// BLOQUEADO
		$postData['block'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT bid FROM @bloqueos WHERE b_user = {$postData['post_user']} AND b_auser = {$tsUser->uid} LIMIT 1"));
		// FOLLOWS
		if($postData['post_seguidores'] > 0) {
			$postData['follow'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS f FROM @follows WHERE f_id = {$postData['post_id']} AND f_user = {$tsUser->uid} AND f_type = 2"))[0];	
		}
		//PUNTOS
		if($postData['post_user'] == $tsUser->uid || $tsUser->is_admod) {
			$postData['puntos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.*, u.user_id, u.user_name FROM @posts_votos AS p LEFT JOIN @miembros AS u ON p.tuser = u.user_id WHERE p.tid = {$postData['post_id']} && p.type = 1 ORDER BY p.voto_id DESC"));
		}
		// CATEGORIAS
		$postData['categoria'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_nombre, c.c_seo FROM @posts_categorias AS c WHERE c.cid = {$postData['post_category']}"));
		// Para el seo
		$postData['post_body_descripcion'] = $tsCore->truncate($tsCore->nobbcode($postData['post_body']), 230);
		// Tiempo de lectura
		$postData['post_read'] = $tsCore->readingTime($postData['post_body']);
		// BBCode
		$postData['post_body'] = $tsCore->parseBadWords($postData['post_smileys'] == 0 ? $tsCore->parseBBCode($postData['post_body']) : $tsCore->parseBBCode($postData['post_body'], 'firma'), true);
		// Escapeando
		$postData['post_title'] = stripslashes($postData['post_title']);
		$postData['post_body'] = stripslashes($postData['post_body']);
		// Firma del usuario
		$postData['user_firma'] = $tsCore->parseBadWords($tsCore->parseBBCodeFirma($postData['user_firma']),true);
		// TAGS
		$postData['post_tags'] = explode(",", $postData['post_tags']);
		// URL POST
		$postData['post_url'] = $tsCore->createLink('post', [
			'c_seo' => $postData['categoria']['c_seo'],
			'post_id' => $postData['post_id'],
			'post_title' => $postData['post_title']
		]);
		// Portada
		$postData['post_portada'] = $tsImages->setImageCover($postData['post_id'], $postData['post_portada'], $postData['post_body']);
		$postData['post_ip'] = $postData['post_ip'] ?? $tsCore->getIP();
		$postData['post_fuentes'] = !empty($postData['post_fuentes']) ? json_decode($postData['post_fuentes'], true) : '';
		// YA LO VOTE?
      $vote = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(voto_id) FROM @posts_votos WHERE tid = {$postData['post_id']} AND tuser = {$tsUser->uid} LIMIT 1"))[0];
      $postData['post_vote'] = !empty($vote) ? true : false;
		// NUEVA VISITA
		include_once TS_CLASS . "c.visitas.php";
		$tsVisitas = new tsVisitas;
		$tsVisitas->recordarVisita($post_id, 3, $tsUser->uid);
		//VISITANTES RECIENTES
		$postData['post_hits'] = $tsVisitas->actualizarVisitas($post_id, $tsUser->uid, 3);
		$postData['post_stats'] = $this->countSharedIn($post_id, $tsUser->uid);
		//
		return $postData;
	}

	private function countSharedIn(int $pid = 0, int $uid = 0) {
		global $tsCore;
		$in = $tsCore->setSecure($_GET['in']);
		$exists = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT stats_user FROM @posts_stats WHERE stats_post_id = $pid AND stats_in = '$in' LIMIT 1"));
		$visitas = [
			'facebook' => 0, 
			'twitter' => 0, 
			'telegram' => 0, 
			'whatsapp' => 0
		];
		foreach($visitas as $vid => $visita) {
			$visitas[$vid] = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(sid) AS total FROM @posts_stats WHERE stats_in = '$vid' AND stats_post_id = $pid"))['total'];
		}
		
		return $visitas;
	}
	
	/*
		getSideData($array)
	*/
	public function getAutor(int $user_id = 0){
		global $tsUser, $tsCore;
		// DATOS DEL AUTOR
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_rango, u.user_puntos, u.user_lastactive, u.user_registro, u.user_last_ip, u.user_activo, u.user_baneado, p.user_pais, p.user_sexo, p.user_firma FROM @miembros AS u LEFT JOIN @perfil AS p ON u.user_id = p.user_id WHERE u.user_id = $user_id LIMIT 1"));
		//
		$data['user_seguidores'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM @follows WHERE f_id = $user_id && f_type = 1"));
		$data['user_comentarios'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_comentarios WHERE c_user = $user_id && c_status = 0"));
		$data['user_posts'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id FROM @posts WHERE post_user = $user_id && post_status = 0"));
		// RANGOS DE ESTE USUARIO
		$data['rango'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT r_name, r_color, r_image FROM @rangos WHERE rango_id = {$data['user_rango']} LIMIT 1"));
		$data['rango_image'] = $tsCore->settings['assets'] . '/images/rangos/' . $data['rango']['r_image'];
		// STATUS
		$data['status'] = $tsCore->statusUser($user_id);
		// PAIS
		$data['pais'] = $tsCore->countryUser($data['user_pais']);
		// FOLLOWS
		if($data['user_seguidores'] > 0){
			$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT follow_id FROM @follows WHERE f_id = \''.(int)$user_id.'\' AND f_user = \''.$tsUser->uid.'\' AND f_type = \'1\'');
			$data['follow'] = db_exec('num_rows', $query);
		}
		$data['user_avatar'] = $tsCore->getAvatar($user_id, 'use');
		// RETURN
		return $data;
	}
	
	/*
		lalala
	*/
	function getPunteador(){
		global $tsUser, $tsCore;
		
		if($tsCore->settings['c_allow_points'] > 0) {
		$data['rango'] = $tsCore->settings['c_allow_points'];
		}elseif($tsCore->settings['c_allow_points'] == '-1') {
		$data['rango'] = $tsUser->info['user_puntosxdar']; 
		}else{
		$data['rango'] = $tsUser->permisos['gopfp'];
		  }
		return $data;
	}
	
	/*
		deletePost()
	*/
	function deletePost(){
		global $tsCore, $tsUser;
		//
		$post_id = $tsCore->setSecure($_POST['postid']);
		// ES SU POST EL Q INTENTA BORRAR?
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT post_id, post_title, post_user, post_body, post_category FROM @posts WHERE post_id = \''.(int)$post_id.'\' AND post_user = \''.$tsUser->uid.'\'');
		$data = db_exec('fetch_assoc', $query);
		
		  db_exec([__FILE__, __LINE__], 'query', 'UPDATE @stats SET `stats_posts` = stats_posts - \'1\' WHERE `stats_no` = \'1\'');
		  db_exec([__FILE__, __LINE__], 'query', 'UPDATE @miembros SET `user_posts` = user_posts - \'1\' WHERE `user_id` = \''.$data['post_user'].'\'');
		// ES MIO O SOY MODERADOR/ADMINISTRADOR...
		if(!empty($data['post_id']) || !empty($tsUser->is_admod)){
				// SI ES MIS POST LO BORRAMOS Y MANDAMOS A BORRADORES
			if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @posts WHERE post_id = \''.(int)$post_id.'\'')) {
				if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @posts_comentarios WHERE c_post_id = \''.(int)$post_id.'\'')) {
						 if(db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @posts_borradores (b_user, b_date, b_title, b_body, b_tags, b_category, b_status, b_causa) VALUES (\''.$tsUser->uid.'\', \''.time().'\', \''.$tsCore->setSecure($data['post_title']).'\', \''.$tsCore->setSecure($data['post_body']).'\', \'\', \''.$data['post_category'].'\', \'2\', \'\')'))
						  return "1: El post fue eliminado satisfactoriamente.";  
					  }
			}else {
				 if(db_exec([__FILE__, __LINE__], 'query', 'UPDATE @posts SET post_status = \'2\' WHERE post_id = \''.(int)$post_id.'\'')) return "1: El post se ha eliminado correctamente.";
			}
				
		} else return '0: Lo que intentas no est&aacute; permitido.';
	}
	
	function deleteAdminPost(){
		global $tsUser;
			  if($tsUser->is_admod == 1){
				 if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT post_id FROM @posts WHERE post_id = \''.(int)$_POST['postid'].'\' AND post_status = \'2\''))){
				 if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @posts WHERE post_id = \''.(int)$_POST['postid'].'\'')) {
				  if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @posts_comentarios WHERE c_post_id = \''.(int)$_POST['postid'].'\' ')){
						db_exec([__FILE__, __LINE__], 'query', 'UPDATE @stats SET `stats_posts` = stats_posts - \'1\' WHERE `stats_no` = \'1\'');
				 return "1: El post se ha eliminado correctamente.";
					 }else return '0: Ha ocurrido un error eliminando comentarios del post.';
				}else return '0: Ha ocurrido un error eliminando el post.';
				 }else return '0: El post ya se encuentra eliminado';
			}else return '0: Para el carro chacho';
	}

	private function getRelatedPostAutor($data) {
		global $tsCore, $tsImages;
		foreach($data as $pid => $post) {
			$data[$pid]['post_title'] = stripslashes($post['post_title']);
			$data[$pid]['post_url'] = $tsCore->createLink('post', [
				'c_seo' => $post['c_seo'],
				'post_id' => $post['post_id'],
				'post_title' => $post['post_title']
			]);
			$data[$pid]['c_img'] = $tsCore->settings['categories'] . '/' . $post['c_img'];
			// Portada
			$data[$pid]['post_portada'] = $tsImages->setImageCover($post['post_id'], $post['post_portada'], $post['post_body']);
			$data[$pid]['post_new'] = $tsCore->tagsNew($post['post_date']);
		}
		return $data;
	}
	/*
		getRelated()
	*/
	public function getRelated($tags = null){
		global $tsCore, $tsUser, $tsImages;
		// ES UN ARRAT AHORA A UNA CADENA
		$tags = is_array($tags) ? implode(",", $tags) : str_replace('-', ', ', $tags);
		//
		$pid = (int)$_GET['post_id'] ?? 0;
		//
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT DISTINCT p.post_id, p.post_title, p.post_category, p.post_private, p.post_portada, p.post_body, p.post_date, c.c_nombre, c.c_seo, c.c_img, u.user_id, u.user_name FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @miembros AS u ON u.user_id = p.post_user WHERE MATCH (post_tags) AGAINST ('$tags' IN BOOLEAN MODE) AND p.post_status = 0 AND post_sticky = 0 AND p.post_id != $pid ORDER BY rand() LIMIT 0, 5"));
		$data = $this->getRelatedPostAutor($data);
		return $data;
	}

	public function getPostAutor(int $uid = 0) {
		$pid = (int)$_GET['post_id'] ?? 0;
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT DISTINCT p.post_id, p.post_title, p.post_category, p.post_private, p.post_portada, p.post_body, p.post_date, c.c_nombre, c.c_seo, c.c_img, u.user_id, u.user_name FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @miembros AS u ON u.user_id = p.post_user WHERE p.post_status = 0 AND post_sticky = 0 AND p.post_user = $uid AND p.post_id != $pid ORDER BY rand() LIMIT 0, 10"));
		$data = $this->getRelatedPostAutor($data);
		return $data;
	}
	
	/*
		votarPost()
	*/
	public function votarPost() {
		global $tsCore, $tsUser, $tsMonitor, $tsActividad;
		#GLOBALES
		if($tsUser->is_admod || $tsUser->permisos['godp']) {
			//Comprobamos si otro usuario ha votado un post con esta ip
		  	$myIP = $tsCore->executeIP();
		  	$time = time();
			if($tsUser->is_admod != 1) {
				if(
					db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_last_ip =  '$myIP' AND user_id != {$tsUser->uid}")) || 
					db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT session_id FROM @sessions WHERE session_ip = '$myIP' AND session_user_id != {$tsUser->uid}"))
				) return '0: Has usado otra cuenta anteriormente, deber&aacute;s contactar con la administraci&oacute;n.';
			}
			$post_id = (int)$_POST['postid'];
			$puntos  = (int)$_POST['puntos'] === 2 ? 2 : 1;
			// SUMAR PUNTOS
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_user FROM @posts WHERE post_id = $post_id LIMIT 1"));
			$userPost = (int)$data['post_user'];
			// NO ES MI POST, PUEDO VOTAR
			if($userPost === $tsUser->uid) return '0: No puedes votar tu propio post.';
			// YA LO VOTE?
			$votado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT tid FROM @posts_votos WHERE tid = $post_id AND tuser = {$tsUser->uid} AND type = 1 LIMIT 1"));
			if (!empty($votado)) return '0: No es posible votar a un mismo post m&aacute;s de una vez.';
			// COMPROBAMOS LOS PUNTOS QUE PODEMOS DAR
			if($tsCore->settings['c_allow_points'] > 0) {
				$max_points = $tsCore->settings['c_allow_points'];
			} elseif($tsCore->settings['c_allow_points'] == '-1') {
				$max_points = $tsUser->info['user_puntosxdar']; 
			} elseif($tsCore->settings['c_allow_points'] == '-2') {
				$max_points = 999;
		 	} else {
				$max_points = $tsUser->permisos['gopfp'];
			}
			// TENGO SUFICIENTES PUNTOS
			if($tsUser->info['user_puntosxdar'] >= $puntos) {
				if($puntos === 0) return '0: Voto no v&aacute;lido. No puedes no dar puntos.';
				if($puntos >= $max_points) {
					return "0: Voto no v&aacute;lido. No puedes dar $puntos puntos, s&oacute;lo se permiten $max_points";
				}
				// SUMAR PUNTOS AL POST
				$mp = ($puntos == 2) ? "-" : "+";
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_puntos = post_puntos $mp 1 WHERE post_id = $post_id");
				// SUMAR PUNTOS AL DUEÑO DEL POST
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_puntos = user_puntos $mp 1 WHERE user_id = $userPost");
				// RESTAR PUNTOS AL VOTANTE
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_puntosxdar = user_puntosxdar - 1 WHERE user_id = {$tsUser->uid}");
				// INSERTAR EN TABLA
				db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_votos (tid, tuser, cant, type, date) VALUES ($post_id, {$tsUser->uid}, $puntos, 1, $time)");
				// AGREGAR AL MONITOR
				$tsMonitor->setNotificacion(3, $userPost, $tsUser->uid, $post_id, $puntos);
				// ACTIVIDAD
				$tsActividad->setActividad(3, $post_id, $puntos);
				// SUBIR DE RANGO
				$this->subirRango($data['post_user'], $post_id);
				return '1: Puntos agregados!';					                  
			} else return "'0: Voto no v&aacute;lido. No puedes dar $puntos puntos, s&oacute;lo te quedan {$tsUser->info['user_puntosxdar']}.'";
		} else return '0: No tienes permiso para hacer esto.';			
	}	
	/*
		subirRango()
	*/
	function subirRango($user_id, $post_id = false){
		global $tsCore, $tsUser;
		// CONSULTA
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_puntos, u.user_rango, r.r_type FROM @miembros AS u LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id WHERE u.user_id = \''.$user_id.'\' LIMIT 1');
		$data = db_exec('fetch_assoc', $query);
		
		// SI TIEN RANGO ESPECIAL NO ACTUALIZAMOS....
		  if(empty($data['r_type']) && $data['user_rango'] != 3) return true;
		  // SI SOLO SE PUEDE SUBIR POR UN POST
		  if(!empty($post_id) && $tsCore->settings['c_newr_type'] == 0) {
			 $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT post_puntos FROM @posts WHERE post_id = \''.(int)$post_id.'\' LIMIT 1');
				$puntos = db_exec('fetch_assoc', $query);
				
				// MODIFICAMOS
				$data['user_puntos'] = $puntos['post_puntos'];
		  }
		  //
		$puntos_actual = $data['user_puntos'];
		  $posts = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(post_id) AS p FROM @posts WHERE post_user = \''.(int)$user_id.'\' && post_status = \'0\''));
		$fotos = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(foto_id) AS f FROM @fotos WHERE f_user = \''.(int)$user_id.'\' && f_status = \'0\''));
		  $comentarios = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS c FROM @posts_comentarios WHERE c_user = \''.(int)$user_id.'\' && c_status = \'0\''));
		  
		// RANGOS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT rango_id, r_cant, r_type FROM @rangos WHERE r_type > \'0\' ORDER BY r_cant');
		
		//
		while($rango = db_exec('fetch_assoc', $query)) 
		  {
			// SUBIR USUARIO
			if(!empty($rango['r_cant']) && $rango['r_type'] == 1 && $rango['r_cant'] <= $puntos_actual){
				$newRango = $rango['rango_id'];
			}elseif(!empty($rango['r_cant']) && $rango['r_type'] == 2 && $rango['r_cant'] <= $posts[0]){
				$newRango = $rango['rango_id'];
			}elseif(!empty($rango['r_cant']) && $rango['r_type'] == 3 && $rango['r_cant'] <= $fotos[0]){
				$newRango = $rango['rango_id'];
			}elseif(!empty($rango['r_cant']) && $rango['r_type'] == 4 && $rango['r_cant'] <= $comentarios[0]){
				$newRango = $rango['rango_id'];
			}
		}
		//HAY NUEVO RANGO?
		if(!empty($newRango) && $newRango != $data['user_rango']){
			//
			if(db_exec([__FILE__, __LINE__], 'query', 'UPDATE @miembros SET user_rango = \''.$newRango.'\' WHERE user_id = \''.$user_id.'\' LIMIT 1')) return true;
		}
	}
	
	/*
		DarMedalla()
	*/
	function DarMedalla($post_id){
		//
		$data = db_exec('fetch_assoc', $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT post_id, post_user, post_puntos, post_hits FROM @posts WHERE post_id = \''.(int)$post_id.'\' LIMIT 1'));
		  
		#···#
		  $q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(follow_id) AS se FROM @follows WHERE f_id = \''.(int)$post_id.'\' && f_type = \'2\''));
		  $q2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS c FROM @posts_comentarios WHERE c_post_id = \''.(int)$post_id.'\' && c_status = \'0\''));
		  $q3 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(fav_id) AS f FROM @posts_favoritos WHERE fav_post_id = \''.(int)$post_id.'\''));
		  $q4 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(did) AS d FROM @denuncias WHERE obj_id = \''.(int)$post_id.'\' && d_type = \'1\''));
		  $q5 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(wm.medal_id) AS m FROM @medallas AS wm LEFT JOIN @medallas_assign AS wma ON wm.medal_id = wma.medal_id WHERE wm.m_type = \'2\' AND wma.medal_for = \''.(int)$post_id.'\''));
		  $q6 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(follow_id) AS sh FROM @follows WHERE f_id = \''.(int)$post_id.'\' && f_type = \'3\''));
		// MEDALLAS
		$datamedal = result_array($query = db_exec([__FILE__, __LINE__], 'query', 'SELECT medal_id, m_cant, m_cond_post FROM @medallas WHERE m_type = \'2\' ORDER BY m_cant DESC'));
		
		//		
		foreach($datamedal as $medalla){
			// DarMedalla
			if($medalla['m_cond_post'] == 1 && !empty($data['post_puntos']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $data['post_puntos']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 2 && !empty($q1[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q1[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 3 && !empty($q2[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q2[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 4 && !empty($q3[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q3[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 5 && !empty($q4[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q4[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 6 && !empty($data['post_hits']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $data['post_hits']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 7 && !empty($q5[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q5[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_post'] == 8 && !empty($q6[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q6[0]){
				$newmedalla = $medalla['medal_id'];
			}
		//SI HAY NUEVA MEDALLA, HACEMOS LAS CONSULTAS
		if(!empty($newmedalla)){
		if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT id FROM @medallas_assign WHERE medal_id = \''.(int)$newmedalla.'\' AND medal_for = \''.(int)$post_id.'\''))){
		db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @medallas_assign (`medal_id`, `medal_for`, `medal_date`, `medal_ip`) VALUES (\''.(int)$newmedalla.'\', \''.(int)$post_id.'\', \''.time().'\', \''.$_SERVER['REMOTE_ADDR'].'\')');
		db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @monitor (user_id, obj_uno, obj_dos, not_type, not_date) VALUES (\''.(int)$data['post_user'].'\', \''.(int)$newmedalla.'\', \''.(int)$post_id.'\', \'16\', \''.time().'\')'); 
		db_exec([__FILE__, __LINE__], 'query', 'UPDATE @medallas SET m_total = m_total + 1 WHERE medal_id = \''.(int)$newmedalla.'\'');}
		}
	  }	
	}

}