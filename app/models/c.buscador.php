<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los resultados
 *
 * @name    c.buscador.php
 * @author  Miguel92
 */

class tsBuscador {

	private function setPagination(string $where = '') {
		global $tsCore;
		// PAGINAS
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(p.post_id) AS total FROM @posts AS p $where");
		$total = db_exec('fetch_assoc', $query);
		  
		return $tsCore->getPagination($total['total'], 12);
	}

	public function isLinkPost(array $isArray = [], bool $post = true) {
		global $tsCore;
		$category = $isArray['c_seo'];
		$post_id = $isArray['post_id'];
		$title = $tsCore->setSEO($isArray['post_title'], true);
		return "{$tsCore->settings['url']}/posts/$category/$post_id/$title.html";
	}

	/*
		  getQuery()
	 */
	public function getQuery() {
		global $tsCore, $tsUser, $tsImages;
		//
		$query = $tsCore->setSecure($_GET['query'] ?? '');
		$category = (int)$_GET['category'] ?? 0;
		$author = $tsCore->setSecure($_GET['autor'] ?? '');
		$engine = $tsCore->setSecure($_GET['engine'] ?? 'web');
		$w_autor = '';
		// ESTABLECER FILTROS
		$where_cat = ($category > 0) ? "AND p.post_category = $category" : '';
		//
		$search_on = 'p.post_' . ($engine === 'tags' ? $engine : 'title');
		// BUSQUEDA
		$w_search = "AND MATCH($search_on) AGAINST('$query' IN BOOLEAN MODE)";
		#$w_search = (!empty($query)) ? '' : "AND $search_on LIKE '%$query%'";
		// SELECCIONAR USUARIO
		if(!empty($author)){
			// OBTENEMOS ID
			$aid = (int)$tsUser->getUserID($author);
			// BUSCAR LOS POST DEL USUARIO SIN CRITERIO DE BUSQUEDA
			if(empty($query) && $aid > 0) $w_search = "AND p.post_user = $aid";
			// BUSCAMOS CON CRITERIO PERO SOLO LOS DE UN USUARIO
			elseif($aid >= 1) $w_autor = "AND p.post_user = $aid";
			//
		}
		$where = "WHERE p.post_status = 0 $where_cat $w_autor $w_search ORDER BY p.post_date";
		$data['pages'] = $this->setPagination($where);
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_category, p.post_title, p.post_body, p.post_date, p.post_comments, p.post_favoritos, p.post_puntos, p.post_portada, p.post_tags, u.user_id, u.user_name, c.c_seo, c.c_nombre, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON u.user_id = p.post_user LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category $where DESC LIMIT {$data['pages']['limit']}"));

		# Limitamos la cantidad de tags a mostrar y calculamos los adicionales
		$max_display = 2;

		foreach($data['data'] as $pid => $post) {
			$data['data'][$pid]['post_url'] = $tsCore->createLink('post', $post['post_id']);
			$data['data'][$pid]['use_avatar'] = $tsCore->getAvatar($post['user_id'], 'use');
			$data['data'][$pid]['portada'] = $tsImages->setImageCover($post['post_id']);
			# Solo mostraremos 3
			$post_tags = explode(',', $post['post_tags']);
			$total_tags = safe_count($post_tags);
			$remaining_tags = $total_tags - $max_display;
			$data['data'][$pid]['post_tags'] = array_slice($post_tags, 0, $max_display);
    		$data['data'][$pid]['remaining_tags'] = ($remaining_tags > 0) ? "+{$remaining_tags}" : "";
		}

		// ACTUALES
		$total = explode(',', $data['pages']['limit']);
		$data['total'] = ($total[0]) + safe_count($data['data']);
		//
		return $data;
	}

}