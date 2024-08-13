<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de las visitas
 *
 * @name    c.visitas.php
 * @author  Miguel92
 */
class tsVisitas {

	protected $isCore;

	protected $isUser;

	protected $ip;

	/**
	 * Constructor de la clase
	 * Inicializa las propiedades necesarias
	*/
	public function __construct() {
		$this->isCore = new tsCore;
		$this->isUser = new tsUser;
		$this->ip = $this->isCore->executeIP($postData['ip']);
	}

	/**
	 * Verifica si ya se ha registrado una visita
	 * 
	 * @param int $id ID del elemento visitado
	 * @param int $type Tipo de elemento visitado
	 * @param string $limit Límite de resultados para la consulta SQL
	 * @return int Número de visitas registradas
	*/
	public function wasVisited(int $id = 0, int $type = 0, string $limit = '') {
		$likeip = "`ip` LIKE '{$this->ip}'";
		$useriplike = $tsUser->is_member ? "(`user` = {$tsUser->uid} OR $likeip)" : $likeip;
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM @visitas WHERE `for` = $id && `type` = $type && $useriplike LIMIT $limit");
		return db_exec('num_rows', $query);
	}

	/**
	 * Genera la consulta SQL para actualizar los contadores de visitas
	 * 
	 * @param string $action Acción a realizar
	 * @param int $type Tipo de elemento visitado
	 * @param int $id ID del elemento visitado
	 * @param int $uid ID del usuario que realiza la visita
	 * @return string Consulta SQL
	*/
	private function action($action = '', int $type = 0, int $id = 0, int $uid = 0) {
		$action = ($action === 'user');
		$queries = [
			2 => "UPDATE @posts SET post_hits = post_hits + 1 WHERE post_id = $id" . ($action ? " AND post_user != $uid" : ''),
			3 => "UPDATE @fotos SET f_hits = f_hits + 1 WHERE foto_id = $id" . ($action ? " AND f_user != $uid" : '')
		];
		return $queries[$type] ?? '';
	}

	private function insertarVisita($uid, $id, $type) {
		$time = time();
		db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @visitas (`user`, `for`, `type`, `date`, `ip`) VALUES ($uid, $id, $type, $time, '{$this->ip}')");
	}

	/**
	 * Registra una visita
	 * 
	 * @param int $id ID del elemento visitado
	 * @param int $type Tipo de elemento visitado
	 * @param int $uid ID del usuario que realiza la visita
	 * @return int Número de visitas registradas
	*/
	public function recordarVisita(int $id = 0, int $type = 0, int $uid = 0) {
		//
		$time = time();
		$visitado = $this->wasVisited($id, $type, ($type === 3 ? '0, 100' : '1'));
		// Registro de visitas para miembros
		if($this->isUser->is_member && !$visitado) {
			if(!$visitado) $this->insertarVisita($uid, $id, $type);
			db_exec([__FILE__, __LINE__], 'query', $this->action('user', $type, $id, $uid));
		} else {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @visitas SET `date` = $time, ip = '{$this->ip}' WHERE `for` = $id && `type` = $type");
		}
		// Registro de visitas para invitados
		if((int)$this->isCore->settings['c_hits_guest'] === 1 && !$this->isUser->is_member && !$visitado) {
			$this->insertarVisita(0, $id, $type);
			db_exec([__FILE__, __LINE__], 'query', $this->action('update', $type, $id));
		}
		// Actualización de visitas en el portal
		if($type === 2) {
			$this->sumPortal($uid);
		}
		return $visitado;
	}

	public function ultimasVisitas(int $id = 0, int $total = 15) {
		return result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT `v.id`, `v.user`, `v.for`, `v.type`, `v.date`, `v.ip`, `u.user_id`, `u.user_name` FROM @visitas AS v LEFT JOIN @miembros AS u ON `v.user` = `u.user_id` WHERE `v.for` = $id && `v.type` = 3 && `v.user` > 0 ORDER BY `v.date` DESC LIMIT $total"));
	}

	public function actualizarVisitas(int $id = 0, int $uid = 0, int $type = 0) {
		$total = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(id) AS total FROM @visitas WHERE type = $type AND `for` = $id"))['total'];
		$total = ($total + $this->countSharedIn($id, $uid));
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_hits` = $total WHERE post_id = $id");
		return $total;
	}

	private function countSharedIn(int $pid = 0, int $uid = 0) {
		global $tsCore;
		$in = $tsCore->setSecure($_GET['in']);
		$exists = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT stats_user FROM @posts_stats WHERE stats_post_id = $pid AND stats_in = '$in' LIMIT 1"));
		if($exists[0] === null AND !empty($in)) {
			$time = time();
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_stats(stats_in, stats_user, stats_post_id, stats_date) VALUES('$in', $uid, $pid, $time)");
		} else {
			$total = (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(sid) FROM @posts_stats WHERE stats_post_id = $pid"))[0];
			return $total;
		}
	}

	/**
	 * Actualiza la lista de últimas visitas en el portal
	 * 
	 * @param int $uid ID del usuario que realiza la visita
	*/
	private function sumPortal(int $uid = 0) {
		if($this->isCore->settings['c_allow_portal']) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT last_posts_visited FROM @portal WHERE user_id = $uid LIMIT 1"));
			$visited = safe_unserialize($data['last_posts_visited']);
			$total = safe_count($visited);
			if($total > 10) {
				array_splice($visited, 0, 1);
			}
			if(!in_array($postData['post_id'],$visited)) {
				$visited = [...$visited ,$postData['post_id']];
			}
			$visitedSerialized = serialize($visited);
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @portal SET last_posts_visited = '$visitedSerialized' WHERE user_id = $uid");
		}
	}


}