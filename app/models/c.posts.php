<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

require TS_MODELS . 'c.cache.php';

class tsPosts {

	public $isAdmodSeeMod;

	public $isMember;

	private $cache;

	private $core;

	private $user;

	private $zcode;

	private $images;

	public function __construct() {
		global $tsImages;
		$this->core = new tsCore;
		$this->user = new tsUser;
		$this->zcode = new tsZCode;
		$this->images = $tsImages;
		//
		$this->isAdmodSeeMod = ($this->user->is_admod AND ((int)$this->core->settings['c_see_mod'] === 1));
		$this->isMember = $this->user->is_member;
	}

	private function redirectLinkPost(int $pid = 0) {
		$tsDir = $this->zcode->createLink('post', $pid);
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
		return $this->isAdmodSeeMod ? '' : " {$prefix}user_activo = 1 AND {$prefix}user_baneado = 0 $addSql";
	}
	
	/** 
	 * isAdmodPost('u.', 'p.', 'AND cm.status = 0'$fix, $add)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmodPost(string $prefix = 'u.', string $prefixSecondary = 'p.', string $append = '') {
	   return $this->isAdmodSeeMod ? "{$prefixSecondary}post_id > 0" : $this->isAdmod($prefix, "AND {$prefixSecondary}post_status = 0 $append");
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
		$dataArray = db_exec('fetch_assoc', $search = db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_title, p.post_category, p.post_user, u.user_name, c.* FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE p.post_id = $post AND p.post_status = 0 AND $admod"));
		foreach($dataArray as $pid => $post) $dataArray['post_title'] = stripslashes($post['post_title']);
		# Si no existe redirecciomos a la página posts
		if(!db_exec('num_rows', $search)){
			$this->redirectLinkPost();
			die;
		}
		$this->redirectLinkPost($dataArray['post_id']);
	}

	/*
      OBTENER LOS TITULOS DE LOS POSTS ANTERIOR/SIGUIENTE
   */
	public function getTitles(string $from = '') {
	   $postid = (int)$_GET["post_id"];
	   $majorOrMinor = ($from === 'prev') ? "<" : ">";
	   $order = ($from === 'prev') ? "DESC" : "ASC";
	   // Consulta para obtener el post más cercano en la dirección deseada
	   $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], "query", "SELECT post_id, post_title, c_seo FROM @posts LEFT JOIN @posts_categorias ON post_category = cid WHERE post_status = 0 AND post_id $majorOrMinor $postid ORDER BY post_id $order LIMIT 1"));
	   if (!empty($data)) {
	      $data['post_title'] = stripslashes($data['post_title']);
	      $data["post_url"] = $this->zcode->createLink('post', $data['post_id']);
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
		$order = ($action === 'fortuitae') ? 'RAND() DESC' : 'p.post_id ' . ($action === 'prev' ? 'DESC' : 'ASC');
		if($action !== 'fortuitae') {
			$pid = isset($_GET['id']) ? (int) $_GET['id'] : 1;
			$isAdmod .= ' AND p.post_id ' . ($action === 'prev' ? "< " : "> ") . $pid;
		}
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_category, p.post_title, u.user_name, c.c_nombre, c.c_seo FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 AND $isAdmod ORDER BY $order LIMIT 1") or exit(show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db'));
		if(!db_exec('num_rows', $query)) $this->redirectLinkPost();
		$queryData = db_exec('fetch_assoc', $query);
		$this->redirectLinkPost($queryData['post_id']);
	}
	
	/**
	 * @access public
	 * @return array
	*/
	public function getCatData(string $category = '') {
		// Obtenemos categoría
		$category = $this->core->setSecure($category);
		$mostramos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_nombre, c_seo, c_img, c_color, c_descripcion FROM @posts_categorias WHERE c_seo = '{$category}' LIMIT 1"));
		#$mostramos['c_img'] = $this->core->imageCat($mostramos['c_img']);
		return $mostramos;
	}

	private function general(&$postData, int $pid = 0, string $title = '') {
		$postData['post_url'] = $this->zcode->createLink('post', $pid);
		$postData['post_title'] = stripslashes($title);
		$postData['post_portada'] = $this->images->setImageCover($pid);
	}

	private function getLastForeach($postData) {
		foreach ($postData as $pid => $post) {
			# URL completa de la portada del post!
			$this->general($postData[$pid], $post['post_id'], $post['post_title']);
			# URL completa de la imagen de categoría
			$postData[$pid]['c_img'] = $this->core->imageCat($post['c_img']);
			# Ya vio el post?
			include_once TS_MODELS . "c.visitas.php";
			$tsVisitas = new tsVisitas;
			$postData[$pid]['visto'] = $tsVisitas->wasVisited($post['post_id'], 3, "1");
			# 
			$postData[$pid]['user_avatar'] = $this->zcode->getAvatar($post['post_user'], 'use');
	      $postData[$pid]['post_new'] = $this->zcode->tagsNew($post['post_date']);
		}
		return $postData;
	}

	private function getLastSQL() {
		$isAdmod = $this->isAdmod();
		$isAdmodPost = $this->isAdmodPost();
		return "SELECT p.post_id, p.post_user, p.post_category, p.post_title, p.post_hits, p.post_date, p.post_comments, p.post_puntos, p.post_private, p.post_sponsored, p.post_status, p.post_sticky, u.user_id, u.user_name, u.user_activo, u.user_baneado, c.c_nombre, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id AND $isAdmod LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE $isAdmodPost";
	}

	/**
	 * @access public
	 * @param string
	 * @param bool
	 * @return array
	*/
	public function getLastPostsStickys() {
		// TIPO DE POSTS A MOSTRAR
		$sentencia = $this->getLastSQL() . " AND p.post_sticky = 1 ORDER BY p.post_date DESC LIMIT 5";
		$result = result_array(db_exec([__FILE__, __LINE__], 'query', $sentencia));
		$data = $this->getLastForeach($result);
		return $data;
	}

	public function getLastPosts(?string $category = NULL) {
		include TS_ZCODE . 'Paginator.php';
		$Paginator = new Paginator;
		$cache = new tsCache;

	   // Configuración inicial
	   $c_where = '';
	   $p_where = '';
	   $cacheKey = "getLastPosts";
	   
	   if (!empty($category) OR isset($_GET["category"])) {
	      $category = $this->core->setSecure($category ?? $_GET["category"]);
	      $cacheKey .= "_$category";
	      // Verificar existencia de la categoría
	      $result = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_categorias WHERE c_seo = '$category' LIMIT 1"));
	      $cid = isset($result['cid']) ? (int)$result['cid'] : 0;
	      if ($cid > 0) {
	         $c_where = 'AND p.post_category = ' . $cid;
	         $p_where = ' AND post_category = ' . $cid;
	      }
	   } else {
	      $cacheKey .= "_normal";
	   }

	   // Función para detectar cambios en los datos
	  	$changeDetector = function() {
	      $latestPost = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(post_id) FROM @posts"))[0];
	      return $latestPost ? (int)$latestPost : 0;
	   };
	 
	   $MaxTotal = (int)$this->core->settings['c_max_posts'];
	   $limit = $this->core->setPageLimit($MaxTotal, false, $changeDetector);
	   $cacheKey .= "_$limit";

	   // Generar caché y procesar datos
	   return $cache->generate($cacheKey, function() use ($Paginator, $c_where, $p_where, $limit, $MaxTotal) {
	      $isAdmodPost = $this->isAdmodPost();
	      // Calcular el total de posts
	      $posts['total'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(p.post_id) AS total FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE $isAdmodPost $p_where AND p.post_sticky = 0"))[0];
	   	// Configurar paginación y límite
	  		$lastPosts['pages'] = $Paginator->system_pagination($posts['total'], $MaxTotal);
	      // Consultar los posts
	      $query = db_exec([__FILE__, __LINE__], 'query', $this->getLastSQL() . " $c_where AND p.post_sticky = 0 GROUP BY p.post_id ORDER BY p.post_id DESC LIMIT $limit");
	      $lastPosts['data'] = $this->getLastForeach(result_array($query));

	      return $lastPosts;
	   }, $changeDetector);
	}

	private function getPostStats(&$postData, int $post_id = 0) {
		$time = time();
		//ESTADÍSTICAS
		#if((int)$postData['post_cache'] <= $time - ((int)$this->core->settings['c_stats_cache'] * 60)) {
			// NÚMERO DE COMENTARIOS
			$postData['post_comments'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(u.user_name) AS c FROM @miembros AS u LEFT JOIN @posts_comentarios AS c ON u.user_id = c.c_user WHERE c.c_post_id = $post_id AND c.c_status = 0 AND u.user_activo = 1 AND u.user_baneado = 0"))[0];
			// NÚMERO DE SEGUIDORES
			$postData['post_seguidores'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(u.user_name) AS s FROM @miembros AS u LEFT JOIN @follows AS f ON u.user_id = f.f_user WHERE f.f_type = 2 AND f.f_id = $post_id AND u.user_activo = 1 AND u.user_baneado = 0"))[0];
			// NÚMERO DE SEGUIDORES
			$postData['post_shared'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS m FROM @follows WHERE f_type = 3 AND f_id = $post_id"))[0];
			// NÚMERO DE FAVORITOS
			$postData['post_favoritos'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(fav_id) AS f FROM @posts_favoritos WHERE fav_post_id = $post_id"))[0];
			// ACTUALIZAMOS
			$post = $this->core->getIUP([
				'comments' => $postData['post_comments'],
				'seguidores' => $postData['post_seguidores'],
				'shared' => $postData['post_shared'],
				'favoritos' => $postData['post_favoritos'],
				'cache' => $time
			], 'post_');

		  //ACTUALIZAMOS LAS ESTADÍSTICAS
		  db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET $post WHERE post_id = $post_id");
		#}
	}

	/*
		getPost()
	*/
	public function getPost() {
		$post_id = (int)$_GET['post_id'];
		if(empty($post_id)) return array('deleted','Oops! Este post no existe o fue eliminado.');
		
		$this->DarMedalla($post_id);
		$postData = $this->fetchPostData($post_id);
		
		if(empty($postData['post_id'])) {
			return $this->handleDeletedPost($post_id);
		} elseif($this->isPostInReview($postData)) {
			return ['denunciado','Oops! El Post se encuentra en revisi&oacute;n.'];
		} elseif($this->isPostPrivate($postData)) {
			return ['privado', $postData['post_title']];
		}

		$this->getPostStats($postData, $post_id);
		$postData['block'] = $this->isUserBlocked($postData['post_user']);
		$postData['follow'] = $this->getPostFollowers($postData);
		$postData['puntos'] = $this->getPostPoints($postData);
		$postData['categoria'] = $this->getPostCategory($postData['post_category']);
		$postData['post_body_descripcion'] = $this->zcode->truncate($this->zcode->nobbcode($postData['post_body']), 230);
		$postData['post_read'] = $this->zcode->readingTime($postData['post_body']);
		$postData['post_body'] = $this->parsePostBody($postData);
		$postData['user_firma'] = $this->parseUserSignature($postData['user_firma']);
		$postData['post_tags'] = explode(",", $postData['post_tags']);
		$this->general($postData, $postData['post_id'], $postData['post_title']);
		$postData['post_ip'] = $postData['post_ip'] ?? $this->core->getIP();
		$postData['post_fuentes'] = !empty($postData['post_fuentes']) ? json_decode($postData['post_fuentes'], true) : '';
		$postData['post_vote'] = $this->hasUserVoted($postData['post_id']);
		$this->recordPostVisit($post_id);
		$postData['post_hits'] = $this->updatePostVisits($post_id);
		$postData['post_stats'] = $this->countSharedIn($post_id, $this->user->uid);	
		return $postData;
	}

	private function fetchPostData($post_id) {
		return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.* ,m.*, u.user_id FROM @posts AS c LEFT JOIN @miembros AS u ON c.post_user = u.user_id LEFT JOIN @perfil AS m ON c.post_user = m.user_id  WHERE `post_id` = $post_id AND {$this->isAdmod()} LIMIT 1"));
	}

	private function handleDeletedPost($post_id) {
		$tsDraft = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT b_title FROM @posts_borradores WHERE b_post_id = $post_id LIMIT 1"));
		$text = (!empty($tsDraft['b_title'])) ? 'Este post no existe o fue eliminado.' : 'El post fue eliminado!';
		return ['deleted','Oops! ' . $text];
	}

	private function isPostInReview($postData) {
		return ($postData['post_status'] === 1 AND (!$this->user->is_admod AND $this->user->permisos['moacp'] === false)) ||
				($postData['post_status'] === 2 AND (!$this->user->is_admod AND $this->user->permisos['morp'] === false)) ||
				($postData['post_status'] === 3 AND (!$this->user->is_admod AND $this->user->permisos['mocp'] === false));
	}

	private function isPostPrivate($postData) {
		return !empty($postData['post_private']) AND empty($this->user->is_member);
	}

	private function isUserBlocked($post_user) {
		return db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT bid FROM @bloqueos WHERE b_user = $post_user AND b_auser = {$this->user->uid} LIMIT 1"));
	}

	private function getPostFollowers($postData) {
		if($postData['post_seguidores'] > 0) {
			return db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS f FROM @follows WHERE f_id = {$postData['post_id']} AND f_user = {$this->user->uid} AND f_type = 2"))[0];	
		}
		return 0;
	}

	private function getPostPoints($postData) {
		if($postData['post_user'] === $this->user->uid || $this->user->is_admod) {
			return result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.*, u.user_id, u.user_name FROM @posts_votos AS p LEFT JOIN @miembros AS u ON p.tuser = u.user_id WHERE p.tid = {$postData['post_id']} AND p.type = 1 ORDER BY p.voto_id DESC"));
		}
		return [];
	}

	private function getPostCategory($post_category) {
		return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_nombre, c.c_seo FROM @posts_categorias AS c WHERE c.cid = $post_category"));
	}

	private function parsePostBody($postData) {
		$smiles = $postData['post_smileys'] === 0 ? 'normal' : 'firma';
		return $this->core->parseBadWords($this->core->parseBBCode($postData['post_body'], $smiles), true);
	}

	private function parseUserSignature($user_firma) {
		return $this->core->parseBadWords($this->core->parseBBCode($user_firma, 'firma'), true);
	}

	private function hasUserVoted($post_id) {
		$vote = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(voto_id) FROM @posts_votos WHERE tid = $post_id AND tuser = {$this->user->uid} LIMIT 1"))[0];
		return !empty($vote);
	}

	private function recordPostVisit($post_id) {
		include_once TS_MODELS . "c.visitas.php";
		$tsVisitas = new tsVisitas;
		$tsVisitas->recordarVisita($post_id, 3, $this->user->uid);
	}

	private function updatePostVisits($post_id) {
		include_once TS_MODELS . "c.visitas.php";
		$tsVisitas = new tsVisitas;
		return $tsVisitas->actualizarVisitas($post_id, $this->user->uid, 3);
	}

	private function countSharedIn(int $pid = 0, int $uid = 0) {
		$in = isset($_GET['in']) ? $this->core->setSecure($_GET['in']) : '';
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
	public function getAutor(int $user_id = 0) {
		// DATOS DEL AUTOR
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_rango, u.user_puntos, u.user_lastactive, u.user_registro, u.user_last_ip, u.user_activo, u.user_baneado, p.user_pais, p.user_sexo, p.user_firma FROM @miembros AS u LEFT JOIN @perfil AS p ON u.user_id = p.user_id WHERE u.user_id = $user_id LIMIT 1"));
		//
		$data['user_seguidores'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM @follows WHERE f_id = $user_id AND f_type = 1"));
		$data['user_comentarios'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_comentarios WHERE c_user = $user_id AND c_status = 0"));
		$data['user_posts'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id FROM @posts WHERE post_user = $user_id AND post_status = 0"));
		// RANGOS DE ESTE USUARIO
		$data['rango'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT r_name, r_color, r_image FROM @rangos WHERE rango_id = {$data['user_rango']} LIMIT 1"));
		$data['rango_image'] = $this->core->setRoutes('assets', 'images') . '/rangos/' . $data['rango']['r_image'];
		// STATUS
		$data['status'] = $this->zcode->statusUser($user_id);
		// PAIS
		$data['pais'] = $this->zcode->countryUser($data['user_pais']);
		// FOLLOWS
		if($data['user_seguidores'] > 0){
			$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT follow_id FROM @follows WHERE f_id = \''.(int)$user_id.'\' AND f_user = \''.$this->user->uid.'\' AND f_type = \'1\'');
			$data['follow'] = db_exec('num_rows', $query);
		}
		$data['user_avatar'] = $this->zcode->getAvatar($user_id, 'use');
		// RETURN
		return $data;
	}
	
	/*
		lalala
	*/
	public function getPunteador(bool $puntuador = false) {
   	$allow = $this->core->settings['c_allow_points'];
    	$data['rango'] = ($allow > 0) ? $allow : ($allow == '-1' ? $this->user->info['user_puntosxdar'] : ($allow == '-2' ? 999 : $this->user->permisos['gopfp']));
    	return $puntuador ? $data : $data['rango'];
	}

	/*
		deletePost()
	*/
	public function deletePost() {
		$post_id = (int)$_POST['postid'];
		// ES SU POST EL Q INTENTA BORRAR?
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_title, post_user, post_body, post_category FROM @posts WHERE post_id = $post_id AND post_user = {$this->user->uid}"));
		//
		statsUpdate([__FILE__, __LINE__], ['table' => '@stats', 'columna' => 'stats_posts', 'donde' => "stats_no = 1"]);
		statsUpdate([__FILE__, __LINE__], ['table' => '@miembros', 'columna' => 'user_posts', 'donde' => "user_id = {$data['post_user']}"]);
		// ES MIO O SOY MODERADOR/ADMINISTRADOR...
		if(empty($data['post_id']) || empty($this->user->is_admod)) return '0: Lo que intentas no est&aacute; permitido.';
		// SI ES MIS POST LO BORRAMOS Y MANDAMOS A BORRADORES
		if(removeDataById([__FILE__, __LINE__], '@posts', "post_id = $post_id")) {
			if(removeDataById([__FILE__, __LINE__], '@posts_comentarios', "c_post_id = $post_id")) {
				$info = [
					'user' => $this->user->uid, 
					'date' => time(), 
					'title' => $this->core->setSecure($data['post_title']), 
					'body' => $this->core->setSecure($data['post_body']), 
					'tags' => '', 
					'category' => $data['post_category'],
					'status' => 2,
					'causa' => ''
				];
				if(addDataToTable([__FILE__, __LINE__], '@posts_borradores', $info, 'b_')) return "1: El post fue eliminado satisfactoriamente.";  
			}
		} else {
			 if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_status = 2 WHERE post_id = $post_id")) return "1: El post se ha eliminado correctamente.";
		}
			
	}
	
	public function deleteAdminPost(){
		$pid = (int)$_POST['postid'];
		if($this->user->is_admod !== 1) return '0: Para el carro chacho';
		if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id FROM @posts WHERE post_id = $pid AND post_status = 2"))) return '0: El post ya se encuentra eliminado';
		if(removeDataById([__FILE__, __LINE__], '@posts', "post_id = $pid")) {
			if(removeDataById([__FILE__, __LINE__], '@posts_comentarios', "c_post_id = $pid")) {
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET stats_posts = stats_posts - 1 WHERE stats_no = 1");
				return "1: El post se ha eliminado correctamente.";
			} else return '0: Ha ocurrido un error eliminando comentarios del post.';
		} else return '0: Ha ocurrido un error eliminando el post.';
	}

	private function getRelatedPostAutor($postData) {
		foreach($postData as $pid => $post) {
			$this->general($postData[$pid], $post['post_id'], $post['post_title']);
			$postData[$pid]['c_img'] = $this->core->setRoutes('assets', 'categories') . '/' . $post['c_img'];
			// Portada
			$postData[$pid]['post_new'] = $this->zcode->tagsNew($post['post_date']);
		}
		return $postData;
	}
	/*
		getRelated()
	*/
	public function getRelated($tags = null) {
		// ES UN ARRAY AHORA A UNA CADENA
		$search = !empty($tags) ? implode(",", $tags) : str_replace('-', ' ', $this->core->setSecure($_GET['title']));
		$match = !empty($tags) ? 'post_tags' : 'post_title';
		//
		$pid = (int)$_GET['post_id'] ?? 0;
		//
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT DISTINCT p.post_id, p.post_title, p.post_category, p.post_private, p.post_portada, p.post_body, p.post_date, c.c_nombre, c.c_seo, c.c_img, u.user_id, u.user_name FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @miembros AS u ON u.user_id = p.post_user WHERE MATCH ($match) AGAINST ('$search' IN BOOLEAN MODE) AND p.post_status = 0 AND post_sticky = 0 AND p.post_id != $pid ORDER BY rand() LIMIT 0, 5"));
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
		global $tsMonitor, $tsActividad;
		#GLOBALES
		if(!$this->user->is_admod || !$this->user->permisos['godp']) return '0: No tienes permiso para hacer esto.';
		//Comprobamos si otro usuario ha votado un post con esta ip
		$myIP = $this->core->executeIP();
		$time = time();
		if($this->user->is_admod != 1) {
			if(
				db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_last_ip = '$myIP' AND user_id != {$this->user->uid}")) || 
				db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT session_id FROM @sessions WHERE session_ip = '$myIP' AND session_user_id != {$this->user->uid}"))
			) return '0: Has usado otra cuenta anteriormente, deber&aacute;s contactar con la administraci&oacute;n.';
		}
		$post_id = (int)$_POST['postid'];
		$puntos  = (int)$_POST['puntos'] === 2 ? 2 : 1;
		// SUMAR PUNTOS
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_user FROM @posts WHERE post_id = $post_id LIMIT 1"));
		$userPost = (int)$data['post_user'];
		// NO ES MI POST, PUEDO VOTAR
		if($userPost === $this->user->uid) return '0: No puedes votar tu propio post.';
		// YA LO VOTE?
		$votado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT tid FROM @posts_votos WHERE tid = $post_id AND tuser = {$this->user->uid} AND type = 1 LIMIT 1"));
		if (!empty($votado)) return '0: No es posible votar a un mismo post m&aacute;s de una vez.';
		// COMPROBAMOS LOS PUNTOS QUE PODEMOS DAR
		$max_points = $this->getPunteador(true);
		// TENGO SUFICIENTES PUNTOS
		if($this->user->info['user_puntosxdar'] <= $puntos) return "'0: Voto no v&aacute;lido. No puedes dar $puntos puntos, s&oacute;lo te quedan {$this->user->info['user_puntosxdar']}.'";
		if($puntos === 0) return '0: Voto no v&aacute;lido. No puedes no dar puntos.';
		if($puntos >= $max_points) return "0: Voto no v&aacute;lido. No puedes dar $puntos puntos, s&oacute;lo se permiten $max_points";
		// SUMAR PUNTOS AL POST
		$mp = ($puntos == 2) ? "-" : "+";
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_puntos = post_puntos $mp 1 WHERE post_id = $post_id");
		// SUMAR PUNTOS AL DUEÑO DEL POST
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_puntos = user_puntos $mp 1 WHERE user_id = $userPost");
		// RESTAR PUNTOS AL VOTANTE
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_puntosxdar = user_puntosxdar - 1 WHERE user_id = {$this->user->uid}");
		// INSERTAR EN TABLA
		db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_votos (tid, tuser, cant, type, date) VALUES ($post_id, {$this->user->uid}, $puntos, 1, $time)");
		// AGREGAR AL MONITOR
		$tsMonitor->setNotificacion(3, $userPost, $this->user->uid, $post_id, $puntos);
		// ACTIVIDAD
		$tsActividad->setActividad(3, $post_id, $puntos);
		// SUBIR DE RANGO
		$this->subirRango($data['post_user'], $post_id);
		return '1: Puntos agregados!';
	}

	/*
		subirRango()
	*/
	public function subirRango($user_id, $post_id = false) {
	   $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_puntos, u.user_rango, r.r_type FROM 	@miembros AS u LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id WHERE u.user_id = $user_id LIMIT 1"));
	   if (empty($data['r_type']) AND $data['user_rango'] !== 3) return true;
	   if (!empty($post_id) AND (int)$this->core->settings['c_newr_type'] === 0) {
	      $puntos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_puntos FROM @posts WHERE post_id = 	$post_id LIMIT 1"));
	      $data['user_puntos'] = $puntos['post_puntos'];
	   }
	   $stats = [
	      'puntos' => $data['user_puntos'],
	      'posts' => db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(post_id) FROM @posts WHERE 	post_user = $user_id AND post_status = 0"))[0],
	      'fotos' => db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) FROM @fotos WHERE f_user = $user_id AND f_status = 0"))[0],
	      'comentarios' => db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) FROM @posts_comentarios 	WHERE c_user = $user_id AND c_status = 0"))[0]
	   ];
	   $rangos = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT rango_id, r_cant, r_type FROM @rangos WHERE r_type > 0 ORDER BY r_cant"));
	    
	   foreach ($rangos as $rango) {
	   	$dataRango = [
	      	1 => 'puntos', 
	      	2 => 'posts', 
	      	3 => 'fotos',
	      	4 => 'comentarios'
	      ];
	      if (!empty($rango['r_cant']) && (int)$rango['r_cant'] <= (int)$stats[array_search($rango['r_type'], $dataRango)]) {
	         $newRango = $rango['rango_id'];
	      }
	   }
	   if (!empty($newRango) && (int)$newRango !== (int)$data['user_rango']) {
	      return db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET user_rango = $newRango WHERE user_id = $user_id 	LIMIT 1");
	   }
	}
	
	/*
		DarMedalla()
	*/
	public function DarMedalla($post_id) {
   	$MYIP = $this->core->executeIP();
   	$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_user, post_puntos, post_hits FROM @posts WHERE post_id = $post_id LIMIT 1"));
   	
   	$queries = [
   	   "SELECT COUNT(follow_id) FROM @follows WHERE f_id = $post_id AND f_type = 2",
   	   "SELECT COUNT(cid) FROM @posts_comentarios WHERE c_post_id = $post_id AND c_status = 0",
   	   "SELECT COUNT(fav_id) FROM @posts_favoritos WHERE fav_post_id = $post_id",
   	   "SELECT COUNT(did) FROM @denuncias WHERE obj_id = $post_id AND d_type = 1",
   	   "SELECT COUNT(wm.medal_id) FROM @medallas AS wm LEFT JOIN @medallas_assign AS wma ON wm.medal_id = wma.medal_id WHERE wm.m_type = 2 AND wma.medal_for = $post_id",
   	   "SELECT COUNT(follow_id) FROM @follows WHERE f_id = $post_id AND f_type = 3"
   	];
   	$results = array_map(fn($q) => db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', $q))[0], $queries);
   	$datamedal = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT medal_id, m_cant, m_cond_post FROM @medallas WHERE m_type = 2 ORDER BY m_cant DESC"));
    
   	foreach ($datamedal as $medalla) {
   	  	$conditions = [
   	  	  	1 => $data['post_puntos'],
   	  	  	2 => $results[0],
   	  	  	3 => $results[1],
   	  	  	4 => $results[2],
   	  	  	5 => $results[3],
   	  	  	6 => $data['post_hits'],
   	  	  	7 => $results[4],
   	  	  	8 => $results[5]
   	  	];
   	    
   	   if (!empty($conditions[$medalla['m_cond_post']]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $conditions[$medalla['m_cond_post']]) {
   	      $newmedalla = $medalla['medal_id'];
   	      if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @medallas_assign WHERE medal_id = $newmedalla AND medal_for = $post_id"))) {
   	         db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @medallas_assign (medal_id, medal_for, medal_date, medal_ip) VALUES ($newmedalla, $post_id, time(), '$MYIP')");
   	         db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @monitor (user_id, obj_uno, obj_dos, not_type, not_date) VALUES ({$data['post_user']}, $newmedalla, $post_id, 16, time())");
   	         db_exec([__FILE__, __LINE__], 'query', "UPDATE @medallas SET m_total = m_total + 1 WHERE medal_id = $newmedalla");
   	      }
   	   }
   	}
	}
}