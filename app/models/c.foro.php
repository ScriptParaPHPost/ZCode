<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los foro
 *
 * @name    c.foro.php
 * @author  Miguel92
 */

class tsForo {

	private function getPostsOfCategorie(array &$data = []) {
		global $tsCore;
		foreach($data['super_subcategorias'] as $key => $posts) {
			$lastPost = db_exec('fetch_assoc', db_exec(array(__FILE__, __LINE__), 'query', "SELECT p.post_id, p.post_user, p.post_category, p.post_title, p.post_private, p.post_sponsored, p.post_sticky, p.post_block_comments, p.post_date, u.user_id, u.user_name, u.user_rango, r.r_name, r.r_color, c.c_nombre FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE p.post_category = {$posts['cid']} ORDER BY p.post_id DESC LIMIT 1"));
			$lastPost['post_url'] = $tsCore->createLink('post', $lastPost['post_id']);
			$data['super_subcategorias'][$key]['ultimo'] = $lastPost;

			$data['super_subcategorias'][$key]['super_stats'] = db_exec('fetch_assoc', db_exec(array(__FILE__, __LINE__), 'query', "SELECT COUNT(p.post_id) as posts, SUM(p.post_comments) as comentarios, SUM(post_hits) as hits FROM @posts AS p LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE p.post_category = {$posts['cid']}"));
		}
	}

	public function getForoPosts() {
		global $tsCore;
		# Obtenemos todos los foros
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT fid, super_nombre, super_descripcion, super_color, super_img FROM @posts_supercategorias"));
		# Mostraremos 3 categorías
		foreach($data as $k => $super) {
			$data[$k]['super_img'] = $tsCore->imageCat($super['super_img'] ?? '1f30d.svg');
			$data[$k]['super_subcategorias'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT cid, c_nombre, c_seo FROM @posts_categorias WHERE c_foro = {$super['fid']}"));
			$this->getPostsOfCategorie($data[$k]);
		}
		return $data;
	}

}