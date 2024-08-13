<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los tops
 *
 * @name    c.tops.php
 * @author  Miguel92 & PHPost.es
 */
class tsTops {
	
	private $filter = ['hoy' => 1, 'ayer' => 2, 'semana' => 3, 'mes' => 4, 'historico' => 5];

	private function getHomeTops($array, $function = '') {
		if(is_array($array)) {
			foreach ($array as $tiempo => $settime) {
				$data[$tiempo] = call_user_func(array($this, $function), $this->setTime($settime));
			}
		} else {
			$data = call_user_func(array($this, $function), $this->setTime($array));
		}
		return $data;
	}
	
	/*
		getHomeTopPosts()
		: TOP DE POST semana, histórico
	*/
	public function getHomeTopPosts() {
		array_shift($this->filter);
		return $this->getHomeTops($this->filter, 'getHomeTopPostsQuery');
	}
	/*
		getHomeTopUsers()
		: TOP DE USUARIOS semana, histórico
	*/
	public function getHomeTopUsers(){
		array_shift($this->filter);
		return $this->getHomeTops($this->filter, 'getHomeTopUsersQuery');
	}

	private function appendAvatar(&$array) {
		global $tsCore;
		foreach($array as $uid => $user) {
			$array[$uid]['avatar'] = $tsCore->getAvatar($user['user_id'], 'use');
		}
		return $array;
	}
	 /*
		  getTopUsers()
	 */
	 function getTopUsers($fecha, $cat){
		  //
		  $data = $this->setTime($fecha);
		  $category = empty($cat) ? '' : 'AND post_category = '.$cat;
		// PUNTOS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT SUM(p.post_puntos) AS total, u.user_id, u.user_name FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE p.post_status = 0  AND p.post_date BETWEEN '.$data['start'].' AND '.$data['end'].' '.$category.' GROUP BY p.post_user ORDER BY total DESC LIMIT 10');
		  $array['puntos'] = result_array($query);
		  $this->appendAvatar($array['puntos']);
		  
		  // SEGUIDORES
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(f.follow_id) AS total, u.user_id, u.user_name FROM @follows AS f LEFT JOIN @miembros AS u ON f.f_id = u.user_id WHERE f.f_type = 1 AND f.f_date BETWEEN '.$data['start'].' AND '.$data['end'].' GROUP BY f.f_id ORDER BY total DESC LIMIT 10');
		  $array['seguidores'] = result_array($query);
		  $this->appendAvatar($array['seguidores']);
		  
		// MEDALLAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(m.medal_for) AS total, u.user_id, u.user_name, wm.medal_id FROM @medallas_assign AS m LEFT JOIN @miembros AS u ON m.medal_for = u.user_id LEFT JOIN @medallas AS wm ON wm.medal_id = m.medal_id WHERE wm.m_type = \'1\' AND m.medal_date BETWEEN '.$data['start'].' AND '.$data['end'].' GROUP BY m.medal_for ORDER BY total DESC LIMIT 10');
		  $array['medallas'] = result_array($query);
		  $this->appendAvatar($array['medallas']);
		  
		  //
		  return $array;
	 }
	/*
		getTopPosts()
	*/
	function getTopPosts($fecha, $cat){
		// PUNTOS
		$data['puntos'] = $this->getTopPostsVars($fecha, $cat, 'puntos');
		// SEGUIDORES
		$data['seguidores'] = $this->getTopPostsVars($fecha, $cat, 'seguidores');
		// COMENTARIOS
		$data['comments'] = $this->getTopPostsVars($fecha, $cat, 'comments');
		// FAVORITOS
		$data['favoritos'] = $this->getTopPostsVars($fecha, $cat, 'favoritos');
		//
		//
		return $data;
	}
	/*
		setTopPostsVars($text, $type)
	*/
	function getTopPostsVars($fecha, $cat, $type){
		//
		$data = $this->setTime($fecha);
		if(!empty($cat)) $data['scat'] = 'AND c.cid = '.$cat;
		//
		$data['type'] = 'p.post_'.$type;

		//
		return $this->getTopPostsQuery($data);
	}
	/*
		getTopPostsQuery($data)
	*/
	public function getTopPostsQuery($data){
		global $tsCore;
		$datos = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT p.post_id, p.post_category, p.post_portada, '.$data['type'].', p.post_puntos, p.post_title, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category  WHERE p.post_status = \'0\' AND p.post_date BETWEEN '.$data['start'].' AND '.$data['end'].' '.$data['scat'].' ORDER BY '.$data['type'].' DESC LIMIT 10'));
		foreach($datos as $pid => $post) {
			$datos[$pid]['post_title'] = stripslashes($post['post_title']);
		}
		//
		return $datos;
	}
	/*
		getHomeTopPostsQuery($data)
	*/
	public function getHomeTopPostsQuery($date){
		global $tsCore;
		//
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT p.post_id, p.post_category, p.post_portada, p.post_title, p.post_puntos, c.c_seo FROM @posts AS p LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category  WHERE p.post_status = 0 AND p.post_date BETWEEN \''.$date['start'].'\' AND \''.$date['end'].'\' ORDER BY p.post_puntos DESC LIMIT 15'));

		foreach ($data as $pid => $post) {
			$data[$pid]['post_title'] = stripslashes($post['post_title']);
			$data[$pid]['post_url'] = $tsCore->createLink('post', [
				'c_seo' => $post['c_seo'],
				'post_id' => $post['post_id'],
				'post_title' => $post['post_title']
			]);
		}
		
		//
		return $data;
	}

	/*
		getHomeTopUsersQuery($date)
	*/
	public function getHomeTopUsersQuery($date){
		// PUNTOS
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT SUM(p.post_puntos) AS total, u.user_id, u.user_name FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id WHERE p.post_status = 0  AND p.post_date BETWEEN \''.$date['start'].'\' AND \''.$date['end'].'\' GROUP BY p.post_user ORDER BY total DESC LIMIT 10'));
		return $data;
	}

	/*
		getStats() : NADA QUE VER CON LA CLASE PERO BUENO PARA AHORRAR ESPACIO...
		: ESTADISTICAS DE LA WEB
	*/
	public function getStats(){
		global $tsCore;
		$time = time();
		// OBTENEMOS LAS ESTADISTICAS
		$return = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT stats_max_online, stats_max_time, stats_time, stats_time_cache, stats_miembros, stats_posts, stats_fotos, stats_comments, stats_foto_comments FROM @stats WHERE stats_no = 1"));
		if((int)$return['stats_time_cache'] < time() - ((int)$tsCore->settings['c_stats_cache'] * 60)) {
			// MIEMBROS
			$return['stats_miembros'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(user_id) AS u FROM @miembros WHERE user_activo = 1 && user_baneado = 0'))[0];
			// POSTS
			$return['stats_posts'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(post_id) AS p FROM @posts WHERE post_status = 0'))[0];
			// FOTOS
		  $return['stats_fotos'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(foto_id) AS f FROM @fotos WHERE f_status = 0'))[0];
			// COMENTARIOS
		  $return['stats_comments'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS c FROM @posts_comentarios WHERE c_status = 0'))[0];
			// COMENTARIOS EN FOTOS
		  $return['stats_foto_comments'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS fc FROM @fotos_comentarios'))[0];

		  $ndat = ", stats_time_cache = {$time}, stats_miembros = {$return['stats_miembros']}, stats_posts = {$return['stats_posts']}, stats_fotos = {$return['stats_fotos']}, stats_comments = {$return['stats_comments']}, stats_foto_comments = {$return['stats_foto_comments']}";
		}
		// PARA SABER SI ESTA ONLINE
		$is_online = (time() - ((int)$tsCore->settings['c_last_active'] * 60));
		// USUARIOS ONLINE - COMPROBAMOS SI CONTAMOS A TODOS LOS USUARIOS O SOLO A REGISTRADOS
		if((int)$tsCore->settings['c_count_guests']) {
			$sentencia = "COUNT(user_id) AS u FROM @miembros WHERE `user_lastactive`";
		} else {
		  $sentencia = "COUNT(DISTINCT `session_ip`) AS s FROM @sessions WHERE `session_time`";
		}
		$return['stats_online'] = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT $sentencia > $is_online"))[0];
		  
		if($return['stats_online'] > (int)$return['stats_max_online']) {
			$timen = ", stats_max_online = {$return['stats_online']}, stats_max_time = $time";
		}
				
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET stats_time = $time $ndat $timen");
		//
		return $return;
	}
	/******************************************************************************/
	/*
		setTime($fecha)
	*/
	public function setTime($fecha){
    	// Obtiene la fecha actual en formato UNIX
    	$tiempo = strtotime('now');

    	switch($fecha){
        // HOY
        case 1: 
            $data['start'] = strtotime('midnight today');
            $data['end'] = strtotime('tomorrow -1 second');
         break;
        // AYER
        case 2: 
            $data['start'] = strtotime('midnight -1 day');
    			$data['end'] = strtotime('midnight');
	      break;
	     	// SEMANA
	     	case 3: 
            $data['start'] = strtotime('-1 week');
            $data['end'] = strtotime('tomorrow -1 second');
	     	break;
	     	// MES
	     	case 4: 
            $data['start'] = strtotime('first day of this month', $tiempo);
            $data['end'] = strtotime('tomorrow', $tiempo) - 1;
	     	break;
	     	// TODO EL TIEMPO
	     	case 5: 
	     	default: 
	     	   $data['start'] = 0;
	     	   $data['end'] = $tiempo;
	     	break;
	   }
	   return $data;
	}
}