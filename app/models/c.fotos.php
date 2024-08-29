<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de las fotos
 *
 * @name    c.fotos.php
 * @author  ZCode | PHPost
 */
class tsFotos {

	private $limitar = 500;

	private function isAdmod(string $add = '') {
		global $tsCore, $tsUser;
		$append = empty($add) ? '' : $add;
		return ($tsUser->is_admod && (int)$tsCore->settings['c_see_mod'] == 1) ? '' : "AND u.user_activo = 1 && u.user_baneado = 0 $append";
	}

	private function newEditFoto(string $type = 'new') {
		global $tsCore;
		$data = [
			'title' => $tsCore->setSecure($tsCore->parseBadWords($_POST['titulo']), true),
			'foto' => [
				'url' => $tsCore->setSecure($tsCore->parseBadWords($_POST['url'])), 
				'file' => $_FILES['file']
			],
			'description' => $tsCore->setSecure($tsCore->parseBadWords(substr($_POST['description'], 0, 500)), true),
			'closed' => empty($_POST['closed']) ? 0 : 1,
			'visitas' => empty($_POST['visitas']) ? 0 : 1,
			'date' => time()
		];
		if($type === 'edit') {
			$data['razon'] = $tsCore->setSecure($_POST['razon'] ?? 'undefined', true);
			$data['update'] = time();
		}
		return $data;
	}

	/*
		newFoto()
	*/
	public function newFoto(){
		global $tsCore, $tsUser, $tsMonitor, $tsActividad;
		//
		if($tsUser->is_member && $tsUser->info['user_baneado'] == 0 && $tsUser->info['user_activo'] == 1 && ($tsUser->is_admod || $tsUser->permisos['gopf'])) {
			$fData = $this->newEditFoto();
		
			$antiflood = (int)($tsUser->permisos['goaf'] * 5);
			$af_date = (time() - $antiflood);
			$af_date_or = ($af_date * 12);

		  	$antiflood = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) AS f FROM @fotos WHERE (f_date > $af_date AND f_user = {$tsUser->uid}) OR (f_url = '{$fData['foto']['url']}' OR (f_title = '{$fData['titulo']}' AND f_date > $af_date_or AND f_user = {$tsUser->uid}) ) LIMIT 1"));
			if($antiflood[0]) die('Espere '.$antiflood.' segundos para continuar.'); 
			// COMPROBAR CAMPOS
			if(empty($fData['titulo'])) $error['titulo'] = 'true';
			// SE PERMITE SUBIDA DE ARCHIVOS?
			if((int)$tsCore->settings['c_allow_upload'] === 1){
				if(empty($fData['foto']['url']) AND empty($fData['foto']['file']['name'])) return 'No has seleccionado ningun archivo.';
			} else {
				if(empty($fData['foto']['url'])) return 'No has ingresado ninguna URL.';
			}
			
			// ANTI FLOOD original (?)
			$tsCore->antiFlood(true, 'foto', 'Para el carro, chacho...');
			// UPLOAD
			require_once TS_MODELS . 'c.upload.php';
			$tsUpload = new tsUpload();
			$tsUpload->image_scale = true;
			// HACER
			$type = 1;
			if((int)$tsCore->settings['c_allow_upload'] !== 1 AND empty($fData['foto']['file']['name'])) {
				$type = 2;
				$tsUpload->file_url = $fData['foto']['url'];
			}
			$result = $tsUpload->newUpload($type);
		  //
		  if($result[0][0] == 0) return $result[0][1];
		  else {
				$fData['url'] = $result[0][1];
				if(empty($fData['url'])) return 'Lo sentimos ocurri&oacute; un error al subir la imagen.';
				// INSERTAMOS
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @fotos SET f_last = 0 WHERE f_user = {$tsUser->uid} AND f_last = 1");
				// LA ULTIMA DEJA DE SERLO
				$fData['user'] = $tsUser->uid;
				$fData['last'] = 1;
				$fData['ip'] = $tsCore->executeIP($postData['ip']);
				if(insertDataInBase([__FILE__, __LINE__], '@fotos', $fData, 'f_')) {

				}
			if(db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @fotos (f_title, f_date, f_description, f_url, f_user, f_closed, f_visitas, f_last, f_ip) VALUES (\''.$fData['titulo'].'\', \''.time().'\', \''.$fData['desc'].'\',  \''.$img_url.'\', \''.$tsUser->uid.'\', \''.$fData['closed'].'\', \''.$fData['visitas'].'\', \'1\', \''.$fData['ip'].'\')')) {
					 $fid = db_exec('insert_id');
					 // Estadísticas
					 db_exec([__FILE__, __LINE__], 'query', 'UPDATE @stats SET `stats_fotos` = stats_fotos + \'1\' WHERE `stats_no` = \'1\'');
					 //db_exec([__FILE__, __LINE__], 'query', 'UPDATE @miembros SET `user_fotos` = user_fotos + \'1\' WHERE `user_id` = \''.$tsUser->uid.'\''); // Eliminado en 1.1.000.9
				// AGREGAR AL MONITOR DE LOS USUARIOS QUE ME SIGUEN
				$tsMonitor->setFollowNotificacion(10, 1, $tsUser->uid, $fid);
					 // ACTIVIDAD
					 $tsActividad->setActividad(9, $fid);
					 //
					 return $fid;
				}
				else exit( show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db') );
		 // } else return 'fewlolazsp';       
		  }
		  
		}else return 'No tienes permiso para continuar.';
		
	}
	 /*
		  getFotoEdit()
	 */
	 function getFotoEdit(){
		  global $tsCore, $tsUser;
		  //
		  $fid = $tsCore->setSecure($_GET['id']);
		  // DATOS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM @fotos WHERE foto_id = \''.(int)$fid.'\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  
		  //
		  if(!empty($data['f_user'])){
				// ES EL DUEÑO DE LA FOTO?
				if($data['f_user'] == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos['moedfo']){
					 return $data;
				} else return 'La foto que intentas editar no es tuya.';
		  } else return 'La foto que intentas editar no existe.';
	 }
	 /*
		  editFoto()
	 */
	 function editFoto(){
		  global $tsCore, $tsUser, $tsMonitor;
		  //
		  $fid = (int)$_GET['id'];
		  // DATOS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT f.foto_id, f.f_title, f.f_user, u.user_name FROM @fotos AS f LEFT JOIN @miembros AS u ON f.f_user = u.user_id WHERE f.foto_id = \''.(int)$fid.'\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  
		  //
		  if(!empty($data['f_user'])){
				// ES EL DUEÑO DE LA FOTO?
				if($data['f_user'] == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos['moedfo']){
				$fData = array(
						  'titulo' => $tsCore->setSecure($tsCore->parseBadWords($_POST['titulo']), true),
						  'desc' => $tsCore->setSecure($tsCore->parseBadWords(substr($_POST['desc'], 0, 1500)), true),
						  'privada' => empty($_POST['privada']) ? 0 : 1,
						  'closed' => empty($_POST['closed']) ? 0 : 1,
					'visitas' => empty($_POST['visitas']) ? 0 : 1,
					'razon' => empty($_POST['razon']) ? 'undefined' : $tsCore->setSecure($_POST['razon'], true),
				);
					 // UPDATES
				db_exec([__FILE__, __LINE__], 'query', 'UPDATE @fotos SET f_title = \''.$fData['titulo'].'\', f_description = \''.$fData['desc'].'\',  f_closed = \''.$fData['closed'].'\', f_visitas = \''.$fData['visitas'].'\' WHERE foto_id = \''.(int)$fid.'\'');
				
				if($data['f_user'] != $tsUser->uid){
					 $aviso = 'Hola <b>'.$tsUser->getUserName($data['f_user'])."</b>\n\n Te informo que tu foto <a href=".$tsCore->settings['url'].'/fotos/'.$data['user_name'].'/'.$data['foto_id'].'/'.$tsCore->setSEO($data['f_title']).'.html'."><b>".$data['f_title']."</b></a> ha sido editada por <a href=\"#\" class=\"hovercard\" uid=\"".$tsUser->uid."\">".$tsUser->nick."</a>\n\n Causa: <b>".$fData['razon']."</b>\n\n \n\n Te recomendamos leer el <a href=\"".$tsCore->settings['url']."/pages/protocolo/\">protocolo</a> para evitar futuras sanciones.\n\n Muchas gracias por entender!";
						  $tsMonitor->setAviso($data['f_user'], 'Foto editada', $aviso, 2);
					 $_SERVER['REMOTE_ADDR'] = $_SERVER['X_FORWARDED_FOR'] ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
					 if(!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) { die('Su ip no se pudo validar.'); }
					 db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @historial (`pofid`, `action`, `type`, `mod`, `reason`, `date`, `mod_ip`) VALUES (\''.(int)$data['foto_id'].'\', \'1\', \'2\', \''.$tsUser->uid.'\', \''.$fData['razon'].'\', \''.time().'\', \''.$tsCore->setSecure($_SERVER['REMOTE_ADDR']).'\')');
				}
				// REDIRIGIMOS
					 $url = $tsCore->settings['url'].'/fotos/'.$data['user_name'].'/'.$fid.'/'.$tsCore->setSEO($fData['titulo']).'.html';
					 //
					 $tsCore->redirectTo($url);
				} else return 'La foto que intentas editar no es tuya.';
		  } else return 'La foto que intentas editar no existe.';
	 }
	 /*
		  delFoto()
	 */
	 function delFoto(){
		  global $tsCore, $tsUser;
		  //
		  $fid = $tsCore->setSecure($_POST['fid']);
		  // DATOS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `f_user` FROM @fotos WHERE foto_id = \''.(int)$fid.'\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  
		  //
		  if(!empty($data['f_user'])){
				// ES EL DUEÑO DE LA FOTO?
				if($data['f_user'] == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos['moef']){
				 if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @fotos WHERE foto_id = \''.(int)$fid.'\'')){
						  // BORRAMOS LOS COMENTARIOS
					db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @fotos_comentarios WHERE c_foto_id = \''.(int)$fid.'\'');
						  // RESTAMOS ESTADÍSTICAS
						  db_exec([__FILE__, __LINE__], 'query', 'UPDATE @stats SET `stats_fotos` = stats_fotos - \'1\' WHERE `stats_no` = \'1\'');
						  return '1: OK';
					 } else return '0: Ocurri&oacute; un error al intentar borrar';
				} else return '0: Esta no es tu foto.';
		  } else return '0: La foto no existe.';
	 }

	 /*
		  getLastFotos()
	 */
	public function getLastFotos(){
		global $tsCore, $tsUser;
		//
		$max = 15; // MAXIMO A MOSTRAR
		$limit = $tsCore->setPageLimit($max, true);		
		// PAGINAS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(f.foto_id) FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user '.($tsUser->is_admod && $tsCore->settings['c_see_mod'] == 1 ? '' : 'WHERE f.f_status = \'0\' AND u.user_activo = \'1\' && u.user_baneado = \'0\''));
		list ($total) = db_exec('fetch_row', $query);
		
		$data['pages'] = $tsCore->pageIndex("/fotos/?", $total, $max);
		  //
		$query = 'SELECT f.foto_id, f.f_title, f.f_date, f.f_description, f.f_url, f.f_status, u.user_id, u.user_name, u.user_activo, u.user_baneado FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user '.($tsUser->is_admod && $tsCore->settings['c_see_mod'] == 1 ? '' : 'WHERE f.f_status = \'0\' AND u.user_activo = \'1\' && u.user_baneado = \'0\'').' ORDER BY f.foto_id DESC LIMIT '.$limit;
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', $query));
		  
		foreach($data['data'] as $fid => $foto) {
			$data['data'][$fid]['avatar'] = $tsCore->getAvatar($foto['user_id'], 'use');
			$data['data'][$fid]['foto_url'] = $tsCore->createLink('foto', $foto['foto_id']);
		}
		
		  //
		  return $data;
	 }
	 /*
		  getLastComments()
	 */
	public function getLastComments() {
		global $tsUser, $tsCore;
		//
		$isAdmod = ($tsUser->is_admod && $tsCore->settings['c_see_mod'] == 1) ? '' : "WHERE f.f_status = 0 && u.user_activo = 1 && u.user_baneado = 0";
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.cid, c.c_user, f.foto_id, f.f_title, f.f_status, u.user_id, u.user_name, u.user_activo FROM @fotos_comentarios AS c LEFT JOIN @fotos AS f ON c.c_foto_id = f.foto_id LEFT JOIN @miembros AS u ON f.f_user = u.user_id $isAdmod ORDER BY c.c_date DESC LIMIT 10"));
		foreach($data as $fid => $foto) {
			$data[$fid]['avatar'] = $tsCore->getAvatar($foto['user_id'], 'use');
			$data[$fid]['foto_url'] = $tsCore->createLink('foto', $foto['f_title'], '#comment-' . $foto['cid']);
			
		}
		return $data;
	}
	 /*
		  getFotos($user_id)
	 */
	public function getFotos($user_id) {
		global $tsCore, $tsUser;
		//
		$query = 'SELECT f.foto_id, f.f_title, f.f_date, f.f_description, f.f_url, f.f_status, u.user_id, u.user_name, u.user_activo FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user WHERE f.f_user = \''.(int)$user_id.'\' '.($tsUser->is_admod && $tsCore->settings['c_see_mod'] == 1 ? '' : ' && f.f_status = \'0\' && u.user_activo = \'1\' && u.user_baneado = \'0\'').' ORDER BY f.foto_id DESC';
		// PAGINAR
		$total = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', $query));
		$pages = $tsCore->getPagination($total, 12);
		$data['pages'] = $pages;
		//
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', $query.' LIMIT '.$pages['limit']));
		foreach($data['data'] as $fid => $foto) {
			$data['data'][$fid]['avatar'] = $tsCore->getAvatar($foto['user_id'], 'use');
			$data['data'][$fid]['foto_url'] = $tsCore->createLink('foto', $foto['foto_id']);
		}
		//
		return $data;
	}

	 /*
		  getFoto()
	 */
	public function getFoto(){
		global $tsCore, $tsUser;
		//
		$fid = (int)$_GET['fid'];
		$isAdmodPerm = ($tsUser->is_admod || $tsUser->permisos['moacp']) ? '' : "AND f.f_status = 0 AND u.user_activo = 1";
		// MORE FOTOS
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT f.*, u.user_name, u.user_activo, p.user_pais, p.user_sexo, u.user_rango, r.r_name, r.r_color, r.r_image FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user LEFT JOIN @perfil AS p ON p.user_id = u.user_id LEFT JOIN @rangos AS r ON u.user_rango = r.rango_id WHERE f.foto_id = $fid $isAdmodPerm LIMIT 1");
		$data['foto'] = db_exec('fetch_assoc', $query);
		$f_user = (int)$data['foto']['f_user'];
		// Avatar del usuario
		$data['foto']['avatar'] = $tsCore->getAvatar($f_user, 'use');
		// User foto comments
		$data['foto']['user_foto_comments'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) FROM @fotos_comentarios WHERE c_user = $f_user"))[0];
		// User fotos
		$data['foto']['user_fotos'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) AS f FROM @fotos WHERE f_user = $f_user && f_status = 0"))[0];
		$data['foto']['exist'] = db_exec('num_rows', $query);
		$data['foto']['f_description'] = $tsCore->parseBBCode($tsCore->parseSmiles($data['foto']['f_description']));
		// País
		$data['foto']['user_pais'] = $tsCore->countryUser($data['foto']['user_pais']);
		// FOLLOW
		$data['foto']['follow'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT `follow_id` FROM @follows WHERE f_user = {$tsUser->uid} AND f_id = $f_user AND f_type = 1 LIMIT 1"));
		// SEGUIDORES
		$data['amigos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.f_id, p.foto_id, p.f_title, p.f_url, u.user_name FROM @follows AS f LEFT JOIN @fotos AS p ON f.f_id = p.f_user LEFT JOIN @miembros AS u ON p.f_user = u.user_id WHERE f.f_user = $f_user AND f.f_type = 1 AND p.f_last = 1 LIMIT 5"));
		foreach($data['amigos'] as $afid => $foto) {
			$data['amigos'][$afid]['foto_url'] = $tsCore->createLink('foto', $foto['foto_id']);
		}
		// ULTIMAS FOTOS
		$isAdmod = $this->isAdmod("AND f.f_status = 0");
		$data['ultimas_fotos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.foto_id, f.f_title, f.f_date, f.f_status, f.f_url, u.user_name, u.user_activo FROM @fotos AS f LEFT JOIN @miembros AS u ON u.user_id = f.f_user WHERE f.f_user = $f_user AND f.foto_id != $fid $isAdmod ORDER BY f.foto_id DESC LIMIT 5"));
		foreach($data['ultimas_fotos'] as $ufid => $foto) {
			$data['ultimas_fotos'][$ufid]['foto_url'] = $tsCore->createLink('foto', $foto['foto_id']);
		}
		// COMENTARIOS
		$isAdmod = $this->isAdmod();
		$comments = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.*, u.user_id, u.user_name, u.user_activo FROM @fotos_comentarios AS c LEFT JOIN @miembros AS u ON c.c_user = u.user_id WHERE c.c_foto_id = $fid $isAdmod"));
		foreach($comments as $key => $val) {
			$val['c_avatar'] = $tsCore->getAvatar($val['user_id'], 'use');
			$val['c_body'] = $tsCore->parseBBCode($tsCore->parseBadWords($tsCore->parseSmiles($val['c_body']), true));
			$data['comentarios'][] = $val;
		}
		$data['foto']['f_comments'] = safe_count($comments);
		  
		// MEDALLAS
		$data['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, a.* FROM @medallas AS m LEFT JOIN @medallas_assign AS a ON a.medal_id = m.medal_id WHERE a.medal_for = $fid AND m.m_type = 3 ORDER BY a.medal_date DESC LIMIT 10"));
		$data['medalla_total'] = safe_count($data['medallas']);
		  
		$data['foto']['votos'] = $this->getVotes($fid);
		include_once TS_MODELS . "c.visitas.php";
		$tsVisitas = new tsVisitas;
		$tsVisitas->recordarVisita($fid, 3, $tsUser->uid);
		//VISITANTES RECIENTES
		if($data['foto']['f_visitas']) {
			$data['visitas'] = $tsVisitas->ultimasVisitas($fid);
		}
		//
		$this->DarMedalla($fid);
		//
		return $data;
	}

	private function getVotes(int $id = 0) {
		$query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT  v_foto_id, COUNT(CASE WHEN v_pos > 0 THEN 1 END) AS positivos, COUNT(CASE WHEN v_neg > 0 THEN 1 END) AS negativos FROM @fotos_votos WHERE v_foto_id = $id GROUP BY v_foto_id"));
		$query['positivos'] = (int)$query['positivos'];
		$query['negativos'] = (int)$query['negativos'];
		
		return $query;
	}
	
	/*
		DarMedalla()
	*/
	function DarMedalla($fid){
		//
		$data = db_exec('fetch_assoc', $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT foto_id, f_user, f_hits FROM @fotos WHERE foto_id = \''.(int)$fid.'\' LIMIT 1'));
		//
		  $q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS a FROM @fotos_comentarios WHERE c_foto_id = \''.(int)$fid.'\''));
		  $q2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(wm.medal_id) AS m FROM @medallas AS wm LEFT JOIN @medallas_assign AS wma ON wm.medal_id = wma.medal_id WHERE wm.m_type = \'3\' AND wma.medal_for = \''.(int)$fid.'\''));
		// MEDALLAS
		$datamedal = result_array($query = db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM @medallas WHERE m_type = \'3\' ORDER BY m_cant DESC'));
		
		//		
		foreach($datamedal as $medalla){
			// DarMedalla
			if($medalla['m_cond_foto'] == 1 && !empty($data['@fotos_votos_pos']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $data['@fotos_votos_pos']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_foto'] == 2 && !empty($data['@fotos_votos_neg']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $data['@fotos_votos_neg']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_foto'] == 3 && !empty($q1[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q1[0]){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_foto'] == 4 && !empty($data['f_hits']) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $data['f_hits']){
				$newmedalla = $medalla['medal_id'];
			}elseif($medalla['m_cond_foto'] == 5 && !empty($q2[0]) && $medalla['m_cant'] > 0 && $medalla['m_cant'] <= $q2[0]){
				$newmedalla = $medalla['medal_id'];
			}
		//SI HAY NUEVA MEDALLA, HACEMOS LAS CONSULTAS
		if(!empty($newmedalla)){
		  $q3 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(id) AS w FROM @medallas_assign WHERE medal_id = \''.(int)$newmedalla.'\' AND medal_for = \''.(int)$fid.'\''));
		if(!$q3[0]){
		db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @medallas_assign (`medal_id`, `medal_for`, `medal_date`, `medal_ip`) VALUES (\''.(int)$newmedalla.'\', \''.(int)$fid.'\', \''.time().'\', \''.$_SERVER['REMOTE_ADDR'].'\')');
		db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @monitor (user_id, obj_uno, obj_dos, not_type, not_date) VALUES (\''.(int)$data['f_user'].'\', \''.(int)$newmedalla.'\', \''.(int)$fid.'\', \'17\', \''.time().'\')');
		db_exec([__FILE__, __LINE__], 'query', 'UPDATE @medallas SET m_total = m_total + 1 WHERE medal_id = \''.(int)$newmedalla.'\'');}
		}
	  }	
	}

	/*
	 * votarFoto()
	*/
	public function votarFoto() {
		global $tsCore, $tsUser;
		$time = time();
		# Solo usuarios
		if(!$tsUser->is_member) return '0: Lo sentimos, para poder votar debes estar registrado.';
		# Obtenemos variables
		$foto_id = (int)$_POST['fotoid'] ?? 0;
		$voto = $tsCore->setSecure($_POST['voto']);
		//
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT f_user FROM @fotos WHERE foto_id = $foto_id LIMIT 1"));
		if($data['f_user'] === $tsUser->uid) return '0: No puedes votar tu propia foto.';
		// YA LO VOTE?
		$votado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT vid FROM @fotos_votos WHERE v_foto_id = $foto_id AND v_user = {$tsUser->uid} LIMIT 1"));
		if(!empty($votado)) return '0: Ya has votado esta foto.';
		// INSERTAR EN TABLA
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @fotos_votos (`v_foto_id`, `v_user`, `v_$voto`, `v_date`) VALUES ($foto_id, {$tsUser->uid}, 1, $time)")) return '1: La foto fue votada correctamente.';
		return '0: Hubo un error al votar!';
	}

	 /************ COMENTARIOS *******************/

	private function limitComment(bool $parsear = false) {
		global $tsCore;
		$substrCommnet = substr(urldecode($_POST['comentario']), 0, $this->limitar); 
		return $parsear ? $tsCore->parseBadWords($tsCore->setSecure($substrCommnet, true)) : $substrCommnet;
	}

	private function verifyComment(string $comentario = ''):?string {
		/* COMPROBACIONES */
		$tsText = preg_replace('# +#', "", $comentario);
		$tsText = str_replace("\n", "", $tsText);
		if(empty($tsText)) return '0: El campo <strong>Comentario</strong> es requerido para esta operaci&oacute;n';
		return $tsText;
	}
	 /*
		  newComentario()
	 */
	public function newComentario() {
		global $tsCore, $tsUser, $tsMonitor;

		// NO MAS DE 1500 CARACTERES PUES NADIE COMENTA TANTO xD
		$comentario = $this->limitComment();
		$foto_id = (int)$_POST['fotoid'];
		// DE QUIEN ES LA FOTO
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT f_user, f_closed FROM @fotos WHERE foto_id = $foto_id LIMIT 1"));
		/* COMPROBACIONES */
		$tsText = $this->verifyComment($comentario);
		$most_resp = $_POST['mostrar_resp'];
		$fecha = time();
		//
		if(($tsUser->is_member AND $tsUser->info['user_baneado'] === 0 AND $tsUser->info['user_activo'] === 1) OR ($tsUser->is_admod || $tsUser->permisos['gopcf'])) {
			// VAMOS...
			if(!$data['f_user']) return '0: La foto no existe.';
			if($data['f_closed'] === 1 AND $data['f_user'] != $tsUser->uid) return '0: La foto se encuentra cerrada y no se permiten comentarios.';
			// ANTI FLOOD
			$tsCore->antiFlood();
			$IP = $tsCore->executeIP();
			//
			if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @fotos_comentarios (c_foto_id, c_user, c_date, c_body, c_ip) VALUES ($foto_id, {$tsUser->uid}, $fecha, '$comentario', '$IP')")) {
				$cid = db_exec('insert_id');
				// ESTADÍSTICAS
				db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET `stats_foto_comments` = stats_foto_comments + 1 WHERE `stats_no` = 1");
				// NOTIFICAR AL USUARIO
				$tsMonitor->setNotificacion(11, $data['f_user'], $tsUser->uid, $foto_id);
				// array(comid, com, fecha, autor_del_post)
				return [
					'comment_id' => $cid, 
					'comment' => $tsCore->parseBadWords($tsCore->parseSmiles($comentario), true), 
					'comment_date' => $fecha, 
					'comment_autor' => $_POST['auser'],
					'comment_user' => $tsCore->getAvatar($tsUser->uid, 'use')
				];
			} else return '0: Ocurri&oacute; un error int&eacute;ntalo m&aacute;s tarde.';
		} else return '0: Necesitas permisos para continuar.';
	}
	 /*
		  delComentario()
	 */
	 function delComentario(){
		  global $tsCore, $tsUser;
		  //
		  $cid = $tsCore->setSecure($_POST['cid']);
		  // DATOS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT c.cid, c.c_user, f.foto_id, f.f_user FROM @fotos_comentarios AS c LEFT JOIN @fotos AS f ON c.c_foto_id = f.foto_id WHERE c.cid = \''.(int)$cid.'\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  
		  //
		  if(!empty($data['cid'])){
				// ES EL DUEÑO DE LA FOTO?
				if($data['f_user'] == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos['moecf']){
			if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM @fotos_comentarios WHERE cid = \''.(int)$cid.'\'')){
				  db_exec([__FILE__, __LINE__], 'query', 'UPDATE @stats SET `stats_foto_comments` = stats_foto_comments - \'1\' WHERE `stats_no` = \'1\'');
						  return '1: Borrado';
					 }
				} else return '0: Hmmm... &iquest;Haciendo pruebas?';
		  } else return '0: El comentario no existe.'; 
	 }
}