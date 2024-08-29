<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de las medallas
 *
 * @name    c.medals.php
 * @author  ZCode | PHPost
 */
class tsMedal {

   /**
    * @name adGetMedals()
    * @access public
    * @uses Cargamos las medallas para la administracion
    * @param
    * @return array
   */
	public function adGetMedals(){
		global $tsCore;
		// MEDALLAS A MOSTRAR POR PÁGINA
		$total = 20; 
		$limit = $tsCore->setPageLimit($total, true);
		// MEDALLAS
      $datos['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, m.* FROM @medallas AS m LEFT JOIN @miembros AS u ON m.m_autor = u.user_id ORDER BY medal_id DESC LIMIT '.$limit));
		// PAGINAS
		list ($max) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @medallas WHERE medal_id > 0'));
		$datos['pages'] = $tsCore->pageIndex("/admin/medals?", $max, $total);
		return $datos;
	}
	/**
    * @name adGetAssign()
    * @access public
    * @uses Cargamos las medallas asignadas
    * @param
    * @return array
   */
	public function adGetAssign(){
		global $tsCore;
		// MEDALLAS A MOSTRAR POR PÁGINA
		$max = 30; 
		$limit = $tsCore->setPageLimit($max, true);
      // ASIGNACIONES
      $datos['asignaciones'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, a.*, p.post_id, p.post_title, c.c_nombre, c.c_seo, f.foto_id, f.f_title, w.* FROM @medallas_assign AS a LEFT JOIN @miembros AS u ON u.user_id = a.medal_for LEFT JOIN @posts AS p ON p.post_id = a.medal_for LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @fotos AS f ON f.foto_id = a.medal_for LEFT JOIN @medallas AS w ON w.medal_id = a.medal_id ORDER BY a.medal_date DESC LIMIT '.$limit));
		// PAGINAS
		list ($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM @medallas_assign WHERE id > 0'));
		$datos['pages'] = $tsCore->pageIndex("/admin/medals?act=showassign", $total, $max);
		return $datos;
	}
	/**
    * @name adGetMedal()
    * @access public
    * @uses Cargamos una medalla para su edición
    * @param
    * @return array
   */
	public function adGetMedal(){
		$mid = (int)$_GET['mid'];
      $medal = db_exec('fetch_assoc',db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM @medallas WHERE medal_id = $mid LIMIT 1"));
      return $medal;
	}
	/**
    * @name sameArrayMedal()
    * @access private
    * @uses Evitamos la repetición del array
    * @param
    * @return array
   */
	private function sameArrayMedal() {
		global $tsCore;
	   // DATOS
		$medalla = [
			'title' => $tsCore->setSecure($tsCore->parseBadWords($_POST['med_title']), true),
			'description' => $tsCore->setSecure($tsCore->parseBadWords($_POST['med_desc']), true),
			'image' => $tsCore->setSecure($_POST['med_img']),
			'type' => (int)$_POST['med_type'],
			'cant' => (int)$_POST['med_cant'],
			'cond_user' => (int)$_POST['med_cond_user'],
			'cond_user_rango' => (int)$_POST['med_cond_user_rango'],
			'cond_post' => (int)$_POST['med_cond_post'],
			'cond_foto' => (int)$_POST['med_cond_foto']
		];
		// COMPROBAMOS CAMPOS
		if(empty($medalla['title']) || empty($medalla['description'])) 
			return 'Debe introducir t&iacute;tulo y descripci&oacute;n';
		return $medalla;
	}
	/**
    * @name sameCheck()
    * @access private
    * @uses Evitamos la repetición del chequeo
    * @param
    * @return bool
   */
   private function sameCheck(array $medalla = [], int $mid = 0) {
		//COMPROBAMOS QUE NO EXISTA
		if($medalla['type'] >= 1 OR $medalla['type'] <= 3) {
			if($medalla['type'] === 1) {
				$add = "AND `m_cond_user` = {$medalla['cond_user']} AND `m_cond_user_rango` = {$medalla['cond_user_rango']}";
			} elseif($medalla['type'] === 2) {
				$add = "AND m_cond_post = {$medalla['cond_post']}";
			} elseif($medalla['type'] === 3) {
				$add = "AND m_cond_foto = {$medalla['cond_foto']}";
			}
			$mid = ($mid !== 0) ? " AND medal_id != $mid" : "";
			return db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT medal_id FROM @medallas WHERE `m_type` = {$medalla['type']} AND `m_cant` = {$medalla['cant']} $add$mid")) ? false : true;
		}
   }
	/**
    * @name editMedal()
    * @access public
    * @uses Editamos la medalla
    * @param
    * @return array
   */
	public function editMedal() {
      global $tsCore;
      // ID
      $mid = (int)$_GET['mid'];
      $medalla = $this->sameArrayMedal();
		if(is_numeric($medalla['type']) && is_numeric($medalla['cond_user']) && is_numeric($medalla['cond_user_rango']) && is_numeric($medalla['cond_post']) && is_numeric($medalla['cond_foto'])){
			// ACTUALIZAR
        	if($this->sameCheck($medalla, $mid) === true) {
        		$set = $tsCore->getIUP($medalla, 'm_');
				if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @medallas SET $set WHERE medal_id = $mid")) return true;
			} else return 'Ya existe una medalla con esas caracter&iacute;sticas';
		} else return 'Introduzca valores num&eacute;ricos';
	}
   /**
    * @name adNewMedal()
    * @access public
    * @uses Creamos nueva medalla
    * @param
    * @return void
   */
   public function adNewMedal(){
      global $tsUser, $tsCore;
		// DATOS
      $medalla = $this->sameArrayMedal();
      $medalla['autor'] = $tsUser->uid;
      $medalla['date'] = time();

		if(is_numeric($medalla['type']) && is_numeric($medalla['cond_user']) && is_numeric($medalla['cond_user_rango']) && is_numeric($medalla['cond_post']) && is_numeric($medalla['cond_foto'])) {
			// INSERTAR
        	if($this->sameCheck($medalla) !== true) return 'Ya existe una medalla con esas caracter&iacute;sticas';
			if(insertDataInBase([__FILE__, __LINE__], '@medallas', $medalla, 'm_')) return true;
       	else return 'No se pudo insertar la medalla';
			
		} else return 'Introduzca valores num&eacute;ricos';
	}

	private function AsignarMedallaTable(int $id = 0, int $medalla = 0, array $medAss = []) {
		if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @medallas_assign WHERE medal_id = $medalla && medal_for = $id LIMIT 1"))) return '0: El usuario ya tiene esa medalla';
		// Asignamos la asignacion de la medalla
		$medAss['for'] = $id;
		if(!insertDataInBase([__FILE__, __LINE__], '@medallas_assign', $medAss, 'medal_')) {
			return '0: Ocurri&oacute; un error al asignar la medalla';
		}
	}

	private function AsignarMedallaNotificar(int $uid = 0, int $medalla = 0, int $type = 0, $obj_dos = null) {
		$medMon['user_id'] = $uid; 
		$medMon['obj_uno'] = $medalla; 
		if(!empty($obj_dos)) $medMon['obj_dos'] = $obj_dos; 
		$medMon['not_type'] = $type; 
		$medMon['not_date'] = time();
		if(!insertDataInBase([__FILE__, __LINE__], '@monitor', $medMon)) {
			return '0: Ocurri&oacute; un error al notificar al usuario';
		}
		// Debe continuar...
		return true;
	}

	/**
    * @name AsignarMedalla()
    * @access public
    * @uses Damos una medalla a un usuario
    * @param
    * @return void
   */
   public function AsignarMedalla() {
      global $tsUser, $tsCore;
		// DATOS
      $medalla = (int)$_POST['mid'];
		$usuario = $tsCore->setSecure($_POST['m_usuario']);
		$post = (int)$_POST['pid'] ?? 0;
		$foto = (int)$_POST['fid'] ?? 0;
		$user_id = $tsUser->getUserID($usuario);
		// QUE ESTOS DATOS NO ESTEN VACIOS
		if($medalla > 0 AND !empty($usuario) OR $post > 0 OR $foto > 0) {
			if($usuario OR $post OR $foto) {
				$typ = $usuario ? 1 : ($post ? 2 : 3);
				$yeltipo = ' AND m_type = ' . $typ;
			}
			if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT medal_id FROM @medallas WHERE medal_id = $medalla$yeltipo LIMIT 1"))) return '0: La medalla no puede ser asignada porque no existe o no corresponde a este tipo de asignaci&oacute;n.';
			// VERIFICAMOS LA IP
			$myIP = $tsCore->validarIP();
			$medAss = ['id' => $medalla, 'date' => time(), 'ip' => $myIP];
			if(!filter_var($myIP, FILTER_VALIDATE_IP)) return '0: Su IP no se pudo validar';
			// Usuario
			if($usuario) {
				// El usuario existe?
				if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros WHERE user_name = '$usuario' LIMIT 1"))) 
					return '0: El usuario no existe';
				// Ya tiene la medalla?
				$this->AsignarMedallaTable($user_id, $medalla, $medAss);
				// Notificamos
				$continuar = $this->AsignarMedallaNotificar($user_id, $medalla, 15);
			// Post
			} elseif($post) {
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_user FROM @posts WHERE post_id = $post LIMIT 1");
				// El post existe?
				if(!db_exec('num_rows', $query)) return '0: El post no existe';
				$datosdelpost = db_exec('fetch_assoc', $query);
	        	// El post ya tiene medalla?
				$this->AsignarMedallaTable($post, $medalla, $medAss);
				// Notificamos
				$continuar = $this->AsignarMedallaNotificar((int)$datosdelpost['post_user'], $medalla, 16, $post);
			// Foto
			} elseif($foto) {
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT foto_id, f_user FROM @fotos WHERE foto_id = $foto LIMIT 1");
				// Existe la foto
		    	if(!db_exec('num_rows', $query)) return '0: La foto no existe';
				$datosdelafoto = db_exec('fetch_assoc', $query);
				// La foto ya tiene la medalla?
				$this->AsignarMedallaTable($foto, $medalla, $medAss);
				// Notificamos
				$continuar = $this->AsignarMedallaNotificar((int)$datosdelafoto['f_user'], $medalla, 17, $foto);
			// Ninguno de los 3
			} else return '0: No queda claro lo que quiere';
		} else return '0: Falta alg&uacute;n dato importante';
		// Ahora si continuamos....
		if($continuar) { 
			return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @medallas SET m_total = m_total + 1 WHERE medal_id = $medalla")) ? '1: Medalla asignada' : '0: La medalla no se asign&oacute;.'; 
		} else return '0: Hubo problemas, chacho';
 	}
	/**
    * @name delMedalla()
    * @access public
    * @uses Eliminamos una medalla
    * @param
    * @return chorros
	*/
	public function DelMedalla() {
		$medalla = (int)$_POST['medal_id'];
		if(!deleteFromId([__FILE__, __LINE__], '@medallas', "medal_id = $medalla")) return '0: Hubo un problema al eliminar la medalla';
		if(deleteFromId([__FILE__, __LINE__], '@medallas_assign', "medal_id = $medalla")) {
			return '1: La medalla se ha eliminado, usuario/post/foto ha dejado de tenerla.';
		} else return '0: Hubo un problema al matar al p&aacute;jaro, parece ser que se elimin&oacute; a la madre, pero quedan los hijos y te van a hacer mucho da&ntilde;o...';
	}		
	/**
    * @name delAssign()
    * @access public
    * @uses Eliminamos la medalla asignada a un usuario/post/foto
    * @param
    * @return text
   */
	public function DelAssign() {
		$asignacion = (int)$_POST['aid'];
		$medalla = (int)$_POST['medal_id'];
	   if(!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @medallas_assign WHERE id = $asignacion AND medal_id = $medalla LIMIT 1"))) 
	   	$msg = '0: No se ha encontrado esa asignaci&oacute;n';
	   // Eliminamos la asignación
	   if(deleteFromId([__FILE__, __LINE__], '@medallas_assign', "id = $asignacion")) {
	   	// Descontar la asignacion de medalla
		   if(!db_exec([__FILE__, __LINE__], 'query', "UPDATE @medallas SET m_total = m_total - 1 WHERE medal_id = $medalla")) $msg = '0: Se elimin&oacute; la asignaci&oacute;n, pero no se descont&oacute; de las estad&iiacute;sticas.';
	   } else $msg = '0: No se elimin&oacute; la asignaci&oacute;n, pero ahora sabemos que existe.';
		//
		$msg = '1: Asignaci&oacute;n eliminada';
		return $msg;
	}
}