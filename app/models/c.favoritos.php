<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los favoritos
 *
 * @name    c.favoritos.php
 * @author  Miguel92
 */

class tsFavoritos {

	/*
		savePostFavorito()
	*/
	public function savePostFavorito(){
		global $tsCore, $tsUser, $tsMonitor, $tsActividad;
		//
		$pid = (int)$_POST['postid'];
		$fecha = (int)empty($_POST['reactivar']) ? time() : $tsCore->setSecure($_POST['reactivar']);
		/* DE QUIEN ES EL POST */
		$data = db_exec('fetch_assoc',db_exec([__FILE__, __LINE__], 'query', "SELECT post_user FROM @posts WHERE post_id = $pid LIMIT 1"));
		# Es mi post?
		if($data['post_user'] === $tsUser->uid) return '0: No puedes agregar tus propios post a favoritos.';
		// YA LO TENGO?
		$my_favorito = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT fav_id FROM @posts_favoritos WHERE fav_post_id = $pid AND fav_user = {$tsUser->uid} LIMIT 1"));
		# Ya esta en favorito
		if(!empty($my_favorito)) return '0: Este post ya lo tienes en tus favoritos.';
		# Agregamos
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_favoritos (fav_user, fav_post_id, fav_date) VALUES ({$tsUser->uid}, $pid, $fecha)")) {
			// AGREGAR AL MONITOR
			$tsMonitor->setNotificacion(1, $data['post_user'], $tsUser->uid, $pid);
			// ACTIVIDAD 
			$tsActividad->setActividad(2, $pid);
			return '1: Este post fue agregado a tus favoritos.';
		} else return '0: Error al querer guardar en favoritos';
	
	}
	/*
		getPostFavoritos()
	*/
	function getPostFavoritos(){
		global $tsCore, $tsUser;
		//
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT f.fav_id, f.fav_date, p.post_id, p.post_title, p.post_date, p.post_puntos, COUNT(p_c.c_post_id) as post_comments,  c.c_nombre, c.c_seo, c.c_img FROM @posts_favoritos AS f LEFT JOIN @posts AS p ON p.post_id = f.fav_post_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @posts_comentarios AS p_c ON p.post_id = p_c.c_post_id && p_c.c_status = 0 WHERE f.fav_user = {$tsUser->uid} AND p.post_status = 0 GROUP BY c_post_id");
		$data = result_array($query);
		
		//
		foreach($data as $fav){
			$favjson = [
				"fav_id" => $fav['fav_id'],
				"post_id" => $fav['post_id'],
				"titulo" => stripslashes($fav['post_title']),
				"categoria" => [
					"seo" => $fav['c_seo'],
					"name" => $fav['c_nombre'],
					"imagen" => $fav['c_img']
				],
				"url" => $tsCore->createLink('post', $fav['post_id']),
				"fecha_creado" => $fav['post_date'],
				"fecha_guardado" =>  $fav['fav_date'],
				"puntos" => $fav['post_puntos'],
				"comentarios" => $fav['post_comments']
			];
			$favoritos[] = json_encode($favjson, JSON_FORCE_OBJECT);
		}
		//

		return is_array($favoritos) ? join(',', $favoritos) : '';
	}
	/*
		delPostFavorito()
	*/
	public function delPostFavorito() {
		global $tsCore, $tsUser;
		//
		$fid = (int)$_POST['fav_id'];
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT fav_post_id FROM @posts_favoritos WHERE fav_id = $fid AND fav_user = {$tsUser->uid} LIMIT 1");
		$data = db_exec('fetch_assoc', $query);
		// ES MI FAVORITO?
		if(empty($data['fav_post_id'])) return '0: No se pudo borrar, no es tu favorito.';
		return (db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @posts_favoritos WHERE fav_id = $fid AND fav_user = {$tsUser->uid}")) ? '1: Favorito borrado.' : '0: No se pudo borrar.';
	}

}