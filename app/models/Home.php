<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\models; 

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

use app\interfaces\HomeInterface;
use app\models\{Core,User,Visitas};
use app\utils\{Paginator,Zcode};
use app\services\{ContentAccessService,CacheService};

class Home implements HomeInterface {

	protected $cache;

	protected $core;

	protected $user;

	protected $zcode;

	protected $access;

	public function __construct() {
		$this->zcode = new Zcode;
		$this->core = new Core;
      $this->user = new User;
		$this->access = new ContentAccessService();
	}

	/**
	 * @access public
	 * @return array
	*/
	public function getCatData(string $category = '') {
		// Obtenemos categoría
		$category = $this->core->setSecure($category);
		$mostramos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_nombre, c_seo, c_img, c_color, c_descripcion FROM @posts_categorias WHERE c_seo = '{$category}' LIMIT 1"));
		$mostramos['c_img'] = $this->core->imageCat($mostramos['c_img']);
		return $mostramos;
	}

	private function getLastForeach($postData) {
		foreach ($postData as $pid => $post) {
			# URL completa de la portada del post!
			$this->access->general($postData[$pid], $post['post_id'], $post['post_title']);
			# URL completa de la imagen de categoría
			$postData[$pid]['c_img'] = $this->core->imageCat($post['c_img']);
			# Ya vio el post?
			$tsVisitas = new Visitas;
			$postData[$pid]['visto'] = $tsVisitas->wasVisited($post['post_id'], 3, "1");
			# 
			$postData[$pid]['user_avatar'] = $this->zcode->getAvatar($post['post_user'], 'use');
	      $postData[$pid]['post_new'] = $this->zcode->tagsNew($post['post_date']);
		}
		return $postData;
	}

	private function getLastSQL() {
		$isAdmod = $this->access->isAdmod();
		$isAdmodPost = $this->access->isAdmodPost();
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
		$cacheService = new CacheService();

	   // Configuración inicial
	   $c_where = '';
	   $p_where = '';
	   $baseKey = "getLastPosts";
	   
	   if (!empty($category) OR isset($_GET["category"])) {
	      $category = $this->core->setSecure($category ?? $_GET["category"]);
	      $baseKey .= "_$category";
	      // Verificar existencia de la categoría
	      $result = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_categorias WHERE c_seo = '$category' LIMIT 1"));
	      $cid = isset($result['cid']) ? (int)$result['cid'] : 0;
	      if ($cid > 0) {
	         $c_where = 'AND p.post_category = ' . $cid;
	         $p_where = ' AND post_category = ' . $cid;
	      }
	   } else {
	      $baseKey .= "_normal";
	   }

	   // Función para detectar cambios en los datos
	  	$changeDetector = fn() => (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(post_id) FROM @posts"))[0];
	 	
	 	// Obtener límites
	   $MaxTotal = (int)$this->core->settings['c_max_posts'];
	   $limit = $this->core->setPageLimit($MaxTotal, false, $changeDetector);
	   $params = [$limit];

	   // Generar caché y procesar datos
	   return $cacheService->getCached($baseKey, $params, function () use ($c_where, $p_where, $limit, $MaxTotal) {
        	$Paginator = new Paginator;
	      $isAdmodPost = $this->access->isAdmodPost();
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
}