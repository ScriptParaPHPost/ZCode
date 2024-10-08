<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de la actividad
 *
 * @name    c.actividad.php
 * @author  Miguel92 & PHPost.es
 */

/**
 * ACTIVIDAD
 * // POSTS
 * 1 => Cre� un nuevo post
 * 2 => Agreg� a favoritos el post
 * 3 => Dej� 10 puntos en el post
 * 4 => Recomend&oacute; el post
 * 5 => Coment� el post
 * 6 => Vot� positivo/negativo un comentario en el post
 * 7 => Est&aacute; siguiendo el post
 * // FOLLOWS
 * 8 => Est� siguiendo a
 * // FOTOS
 * 9 => Subi� una nueva foto
 * // MURO
 * 10 => 
 *      0 => Public� en su muro
 *      1 => Coment� su publicaci�n
 *      2 => Public� en el muro de
 *      3 => Coment� la publicaci�n de
 * 11 => Le gusta
 *      0 => su publicaci�n
 *      1 => su comentario
 *      2 => la publicaci�n de
 *      3 => el comentario de
*/
class tsActividad {

	private $actividad = [];

	public function __construct(){
		# NO ES NESESARIO HACER ALGO EN EL CONSTRUCTOR
	}
	/**
	 * @name makeActividad
	 * @access private
	 * @params none
	 * @return none
	 */
	private function makeActividad(){
		# ACTIVIDAD CON FORMATO | ID => array(TEXT, LINK, CSS_CLASS)
		$this->actividad = [
			// POSTS
			1 => ['text' => 'Cre&oacute; un nuevo post', 'css' => 'post'],
			2 => ['text' => 'Agreg&oacute; a favoritos el post', 'css' => 'star'],
			3 => ['text' => ['Vot&oacute;', 'el post'], 'css' => 'voto_'],
			4 => ['text' => 'Recomend&oacute; el post', 'css' => 'share'],
			5 => ['text' => ['Coment&oacute;', 'el post'], 'css' => 'comment_post'],
			6 => ['text' => ['Vot&oacute;', 'un comentario en el post'], 'css' => 'voto_'],
			7 => ['text' => 'Est&aacute; siguiendo el post', 'css' => 'follow_post'],
			// FOLLOWS
			8 => ['text' => 'Est&aacute; siguiendo a', 'css' => 'follow'],
			// FOTOS
			9 => ['text' => 'Subi&oacute; una nueva foto', 'css' => 'photo'],
			// MURO
			10 => [
				0 => ['text' => 'Public&oacute; en su', 'link' => 'muro', 'css' => 'status'],
				1 => ['text' => 'Coment&oacute; su', 'link' => 'publicaci&oacute;n', 'css' => 'w_comment'],
				2 => ['text' => 'Public&oacute; en el muro de', 'css' => 'wall_post'],
				3 => ['text' => 'Coment&oacute; la publicaci&oacute;n de', 'css' => 'w_comment']
			],
			11 => ['text' => 'Le gusta', 'css' => 'w_like',
				0 => ['text' => 'su', 'link' => 'publicaci&oacute;n'],
				1 => ['text' => 'su comentario'],
				2 => ['text' => 'la publicaci&oacute;n de'],
				3 => ['text' => 'el comentario'],
			],
			12 => ['text' => ['Reaccio&oacute;', 'un comentario en el post'], 'css' => 'reaction']
		];
	}
	/**
	 * @name setActividad
	 * @access public
	 * @params none
	 * @return void
	 */
	public function setActividad($ac_type = NULL, $obj_uno = NULL, $obj_dos = 0){
		global $tsUser, $tsCore;
		# VARIABLES LOCALES{
		$ac_date = time();
		# BUSCAMOS ACTIVIDADES				
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT `ac_id` FROM @actividad WHERE user_id = \''.$tsUser->uid.'\' ORDER BY ac_date DESC'));
		//
		$ntotal = safe_count($data);
		$delid = $data[$ntotal-1]['ac_id']; // ID DE ULTIMA NOTIFICACION
		// ELIMINAR ACTIVIDADES?
		if($ntotal >= (int)$tsCore->settings['c_max_acts']) {			
			db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @actividad WHERE `ac_id` = '.$delid);
		}
		# SE HACE UN CONTEO PROGRESIVO SI HACE ESTA ACCON MAS DE 1 VEZ AL DIA
		if($ac_type == 5) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `ac_id`, `ac_date` FROM @actividad WHERE user_id = {$tsUser->uid} AND obj_uno = $obj_uno AND ac_type = $ac_type LIMIT 1"));
			$hace = $this->makeFecha($data['ac_date']);
			if($hace == 'today') {                
				if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @actividad SET obj_dos = obj_dos + 1 WHERE ac_id = {$data['ac_id']} LIMIT 1")) return true;			
			}
		}
		# INSERCION DE DATOS        
		return (db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @actividad (`user_id`, `obj_uno`, `obj_dos`, `ac_type`, `ac_date`) VALUES ({$tsUser->uid}, $obj_uno, $obj_dos, $ac_type, $ac_date)"));
	}
	/**
	 * @name getActividad
	 * @access public
	 * @params int(3)
	 * @return array
	 */
	public function getActividad(int $user_id = 0, $ac_type = 0, $start = 0, $v_type = NULL){
		# CREAR ACTIVIDAD
		$this->makeActividad();
		# VARIABLES LOCALES
		$ac_type = ($ac_type != 0) ? ' AND ac_type = \''.$ac_type.'\'' : '';
		# CONSULTA
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT `ac_id`, `user_id`, `obj_uno`, `obj_dos`, `ac_type`, `ac_date` FROM @actividad WHERE user_id = $user_id $ac_type ORDER BY ac_date DESC LIMIT $start, 25"));
		# ARMAR ACTIVIDAD
		$actividad = $this->armActividad($data);
		# RETORNAR ACTIVIDAD
		return $actividad;
	}
	/**
	 * @name getActividadFollows
	 * @access public
	 * @param none
	 * @return array
	 */
	public function getActividadFollows(int $start = 0){
		global $tsCore, $tsUser;
		# CREAR ACTIVIDAD
		$this->makeActividad();
		// SOLO MOSTRAREMOS LAS ULTIMAS 100 ACTIVIDADES
		if($start > 90) return array('total' => '-1');
		// SEGUIDORES
		$follows = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT `f_id` FROM @follows WHERE f_user = {$tsUser->uid} AND f_type = 1"));
		// ORDENAMOS 
		foreach($follows as $key => $val) $amigos[] = "'{$val['f_id']}'";
		// ME AGREGO A LA LISTA DE AMIGOS
		$amigos[] = $tsUser->uid;
		// CONVERTIMOS EL ARRAY EN STRING
		$amigos = implode(', ',$amigos);
		// OBTENEMOS LAS ULTIMAS PUBLICACIONES
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT a.*, u.user_id, u.user_name AS usuario FROM @actividad AS a LEFT JOIN @miembros AS u ON a.user_id = u.user_id WHERE a.user_id IN($amigos) ORDER BY ac_date DESC LIMIT $start, 25"));

		# ARMAR ACTIVIDAD
		if(empty($data)) return 'No hay actividad o no sigues a ning&uacute;n usuario.';
		$actividad = $this->armActividad($data);
		# RETORNAR ACTIVIDAD
		return $actividad;
	}
	/**
	 * @name delActividad
	 * @access public
	 * @param none
	 * @return string
	 */
	public function delActividad(){
		global $tsUser;
		# VARIABLES LOCALES
		$ac_id = (int)$_POST['acid'];
		# CONSULTAS		
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @actividad WHERE ac_id = $ac_id LIMIT 1"));
		# COMPROBAMOS
		if($data['user_id'] == $tsUser->uid) {			
			if(db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @actividad WHERE ac_id = $ac_id")) return '1: Actividad borrada';
		}
		//
		return '0: No puedes borrar esta actividad.';
	}
	/**
	 * @name armActividad
	 * @access private
	 * @params array
	 * @return array
	 */
	private function armActividad($data = NULL){
		# VARIABLES LOCALES
		$actividad = [
			'total' => safe_count($data),
			'data' => [
				'today' => ['title' => 'Hoy', 'data' => []],
				'yesterday' => ['title' => 'Ayer', 'data' => []],
				'week' => ['title' => 'D&iacute;as Anteriores', 'data' => []],
				'month' => ['title' => 'Semanas Anteriores', 'data' => []],
				'old' => ['title' => 'Actividad m&aacute;s antigua', 'data' => []]
			]
		];
		# PARA CADA VALOR CREAR UNA CONSULTA
		foreach($data as $key => $val){
			// CREAR CONSULTA
			$sql = $this->makeConsulta($val);
			// CONSULTAMOS
			$dato = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', $sql));
			if(!empty($dato)) {
				// AGREGAMOS AL ARRAY ORIGINAL
				$dato = array_merge($dato, $val);
				// ARMAMOS LOS TEXTOS
				$oracion = $this->makeOracion($dato);
				// DONDE PONERLO?
				$ac_date = $this->makeFecha($val['ac_date']);
				// PONER
				$actividad['data'][$ac_date]['data'][] = $oracion;
			}
		}
		#RETORNAMOS LOS VALORES
		return $actividad;
	}
	/**
	 * @name makeConsulta
	 * @access private
	 * @params array
	 * @return string/array
	 */
	private function makeConsulta($data = NULL){
		# CON UN SWITCH ESCOGEMOS LA CONSULTA APROPIADA
		switch($data['ac_type']){
			// DEL TIPO 1 al 7 USAMOS LA MISMA CONSULTA
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				return 'SELECT p.post_id, p.post_title, c.c_seo FROM @posts AS p LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid WHERE p.post_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
			break;
			// SIGUIENDO A...
			case 8:
				return 'SELECT user_id AS avatar, user_name FROM @miembros WHERE user_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
			break;
			// SUBIO UNA FOTO
			case 9:
				return 'SELECT f.foto_id, f.f_title, u.user_name FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.foto_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
			break;
			// PUBLICACION EN EL MURO & LE GUSTA
			case 10:
			case 11:
				if($data['obj_dos'] == 0 || $data['obj_dos'] == 2) {
				 	return 'SELECT p.pub_id, u.user_name FROM @muro AS p LEFT JOIN @miembros AS u ON p.p_user = u.user_id WHERE p.pub_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
				} else {
				 	return 'SELECT c.pub_id, c.c_body, u.user_name FROM @muro_comentarios AS c LEFT JOIN @muro AS p ON c.pub_id = p.pub_id LEFT JOIN @miembros AS u ON p.p_user = u.user_id WHERE cid = \''.(int)$data['obj_uno'].'\' LIMIT 1';
				}
			break;
			case 12:
				return 'SELECT p.post_id, p.post_title, c.c_seo, r.r_comment_id, r.r_user_id, r.r_reaction FROM @posts AS p LEFT JOIN @posts_categorias AS c ON p.post_category = c.cid LEFT JOIN @comentarios_reaccion AS r ON r.r_comment_id = c.cid WHERE p.post_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
			break;
		}
	}

	private function linkMonitorOfPost(array $data = [], string $param = '') {
		global $tsCore;
		return $tsCore->createLink('post', [
			'c_seo' => $data['c_seo'],
			'post_id' => $data['post_id'],
			'post_title' => $data['post_title']
		], $param);
	}

	private function linkMonitorOfFoto(array $data = [], string $param = '') {
		global $tsCore;
		return $tsCore->createLink('foto', [
			'user_name' => $data['user_name'],
			'foto_id' => $data['foto_id'],
			'f_title' => $data['f_title']
		], $param);
	}
	/**
	 * @name makeOracion
	 * @access private
	 * @params array
	 * @return array
	 **/
	private function makeOracion($data = NULL){
		global $tsCore;
		# VARIABLES LOCALES
		$ac_type = $data['ac_type'];
		$site_url =  $tsCore->settings['url'];
		$oracion['id'] = $data['ac_id'];
		$oracion['style'] = $this->actividad[$ac_type]['css'];
		$oracion['date'] = $data['ac_date'];
		$oracion['user'] = $data['usuario'];
		$oracion['uid'] = $tsCore->getAvatar($data['user_id'], 'use');
		# CON UN SWITCH ESCOGEMOS QUE ORACION CONSTRUIR
		switch($ac_type){
			# DEL TIPO 1-2, 4 y 7 USAMOS LA MISMA
			case 1:
			case 2:
			case 4:
			case 7:
				$oracion['text'] = $this->actividad[$ac_type]['text'];
				$oracion['link'] = $this->linkMonitorOfPost([
					'c_seo' => $data['c_seo'],
					'post_id' => $data['post_id'],
					'post_title' => $data['post_title']
				]);
				$oracion['ltext'] = $data['post_title'];
			break;
			# DEL TIPO 3, 5 y 6 USAMOS EL MISMO
			case 3:
			case 5:
			case 6:
				//
				if($ac_type == 3) $extra_text = ($data['obj_dos'] == 2) ? 'negativo' : 'positivo';
				elseif($ac_type == 5) $extra_text = ($data['obj_dos'] == 0) ? '' : ($data['obj_dos']+1).' veces';
				else $extra_text = ($data['obj_dos'] == 0) ? 'negativo' : 'positivo';
				//
				$oracion['text'] = $this->actividad[$ac_type]['text'][0]." <strong>{$extra_text}</strong> ".$this->actividad[$ac_type]['text'][1];
				$oracion['link'] = $this->linkMonitorOfPost([
					'c_seo' => $data['c_seo'],
					'post_id' => $data['post_id'],
					'post_title' => $data['post_title']
				]);
				$oracion['ltext'] = $data['post_title'];
				// ESTILO
				$oracion['style'] = ($ac_type === 3 || $ac_type === 6) ? 'voto_'.$extra_text : $oracion['style']; 
			break;
			# ESTA SIGUIENDO A..
			case 8:
				// AVATARES
				$img_uno = '<img class="avatar avatar-1" src="'.$tsCore->getAvatar($data['user_id'], 'use').'"/>';
				$img_dos = '<img class="avatar avatar-1" src="'.$tsCore->getAvatar($data['avatar'], 'use').'"/>';
				// ORACION
				$oracion['text'] = $img_uno.' '.$this->actividad[$ac_type]['text'].' '.$img_dos;
				$oracion['link'] = $site_url.'/perfil/'.$data['user_name'];
				$oracion['ltext'] = $data['user_name'];
				$oracion['style'] = '';
			break;
			# SUBIO NUEVA FOTO
			case 9:
				$oracion['text'] = $this->actividad[$ac_type]['text'];
				$oracion['link'] = $this->linkMonitorOfPost([
					'user_name' => $data['user_name'],
					'foto_id' => $data['foto_id'],
					'f_title' => $data['f_title']
				]);
				$oracion['ltext'] = $data['f_title'];
			break;
			# MURO POSTS
			case 10:
				// SEC TYPE
				$sec_type = $data['obj_dos'];
				$link_text = $this->actividad[$ac_type][$sec_type]['link'];
				//
				$oracion['text'] = $this->actividad[$ac_type][$sec_type]['text'];
				$oracion['link'] = $site_url.'/perfil/'.$data['user_name'].'/'.$data['pub_id'];
				$oracion['ltext'] = empty($link_text) ? $data['user_name'] : $link_text;
				$oracion['style'] = $this->actividad[$ac_type][$sec_type]['css'];
			break;
			# LIKES
			case 11:
				// SEC TYPE
				$sec_type = $data['obj_dos'];
				$link_text = $this->actividad[$ac_type][$sec_type]['link'];
				//
				$oracion['text'] = $this->actividad[$ac_type]['text'].' '.$this->actividad[$ac_type][$sec_type]['text'];
				$oracion['link'] = $site_url.'/perfil/'.$data['user_name'].'?pid='.$data['pub_id'];
				// 
				if($data['obj_dos'] == 0 || $data['obj_dos'] == 2)
					  $oracion['ltext'] = empty($link_text) ? $data['user_name'] : $link_text;
				else {
					$end_text = (strlen($data['c_body']) > 35) ? '...' : '';
					$oracion['ltext'] = substr($data['c_body'],0,30).$end_text;
				}
			break;
			case 12:
				var_dump($data);
			break;
		}
		//
		return $oracion;
	}
	/**
	 * @name makeFecha
	 * @access private
	 * @params int
	 * @return string
	 */
	private function makeFecha($time = NULL){
		# VARIABLES LOCALES
		$tiempo = time() - $time; 
		$dias = round($tiempo / 86400);
		//
		if($dias < 1) return 'today';
		elseif($dias < 2) return 'yesterday';
		elseif($dias <= 7) return 'week';
		elseif($dias <= 30) return 'month';
		else return 'old';
		#
	}
}