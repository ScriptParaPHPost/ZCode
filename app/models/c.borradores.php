<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los borradores
 *
 * @name    c.borradores.php
 * @author  ZCode | PHPost
 */

require TS_MODELS . "c.helper.php";

class tsDrafts {

	protected $core;

	protected $user;

	protected $helper;

	public function __construct() {
		global $tsCore, $tsUser;
		$this->core = $tsCore;
		$this->user = $tsUser;
		$this->helper = new Helper;
	}

	/*
		newDraft()
	*/
	public function newDraft($save = false){
		$draftData = [
			'date' => time(),
			'title' => $this->core->setSecure($this->core->parseBadWords($_POST['titulo']), true),
			'body' => $this->core->setSecure($_POST['cuerpo'], true),
			'tags' => $this->core->setSecure($this->core->parseBadWords($_POST['tags']), true),
			'category' => $this->core->setSecure($_POST['categoria']),
			'fuentes' => $this->core->setSecure($_POST['fuentes'], true),
			'private' => empty($_POST['privado']) ? 0 : 1,
			'block_comments' => empty($_POST['sin_comentarios']) ? 0 : 1,
			'sponsored' => empty($_POST['patrocinado']) ? 0 : 1,
         'sticky' => empty($_POST['sticky']) ? 0 : 1,
			'smileys' => empty($_POST['smileys']) ? 0 : 1,
			'visitantes' => empty($_POST['visitantes']) ? 0 : 1,
		];
		//
		if(!empty($draftData['title'])) {
			if(!empty($draftData['category']) && $draftData['category'] > 0) {
				if($save) {
					// UPDATE
					$bid = (int)$_POST['borrador_id'];
					$updates = $this->core->getIUP($draftData, 'b_');
					if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_borradores SET '.$updates.' WHERE bid = $bid AND b_user = {$this->user->info['user_id']}")) return '1: '.$bid;
					else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
		   	} else {
					// INSERT
			    	if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_borradores (`b_user`, `b_date`, `b_title`, `b_body`, `b_tags`, `b_category`, `b_fuentes`, `b_private`, `b_block_comments`, `b_sponsored`, `b_sticky`, `b_smileys`, `b_visitantes`, `b_status`) VALUES ({$this->user->info['user_id']}, {$draftData['date']}, '{$draftData['title']}', '{$draftData['body']}', '{$draftData['tags']}', {$draftData['category']}, '{$draftData['fuentes']}', {$draftData['private']}, {$draftData['block_comments']}, {$draftData['sponsored']}, {$draftData['sticky']}, {$draftData['smileys']}, {$draftData['visitantes']}, 1)")) return '1: '.db_exec('insert_id');
			   	else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
				}
			} else $return = 'Categor&iacute;a';
		} else $return = 'T&iacute;tulos';
		//
		return '0: El campo <b>'.$return.'</b> es requerido para esta operaci&oacute;n';
		//
	}

	private function ordenarBorradores(string $order = '') {
		return match($order) {
			'fecha' => 'b.b_date',
			'titulo' => 'b.b_title',
			'categoria' => 'c.c_nombre',
			default => 'b.b_date'
		};
	}

	public function obtenerBorradores(string $action = '', string $order = ''): array {
		// Id del usuario
		$uid = $this->helper->UserId();
		$order = $this->ordenarBorradores($order);
		if(!empty($action)) {
			$uid .= " AND b.b_status = " . ($action === 'eliminados' ? 0 : 1);
		}
		//
		$borradores = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_nombre, c.c_seo, c.c_img, b.bid, b.b_title, b.b_date, b.b_status, b.b_causa FROM @posts_categorias AS c LEFT JOIN @posts_borradores AS b ON c.cid = b.b_category WHERE b.b_user = $uid ORDER BY $order DESC"));
		// Tipos
		$tipos = ['eliminados', 'borradores'];
		foreach($borradores as $bid => $borrador) {
			$borradores[$bid]['b_causa'] = $borrador['b_causa'] ?? 'Eliminado por el autor';
			$borradores[$bid]['b_date'] = $this->helper->formatearFecha($borrador['b_date'], true);
			$borradores[$bid]['c_img'] = $this->helper->categoriaImagen($borrador['c_img']);
			$borradores[$bid]['b_status'] = $tipos[$borrador['b_status']];
		}
		return $borradores;
	}

	public function contarPorCategoria(): array {
   	$uid = $this->helper->UserId();
   	//
    	$resultados = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.cid, c.c_nombre, c.c_seo, c.c_img, COUNT(b.bid) AS total FROM @posts_categorias AS c INNER JOIN @posts_borradores AS b ON c.cid = b.b_category WHERE b.b_user = $uid GROUP BY c.cid HAVING total > 0 ORDER BY total DESC"));
   	return $resultados;
	}

	//
	public function contarPorEstado() {
   	$uid = $this->helper->UserId();
   	//
 		$resultados = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT SUM(CASE WHEN b_status = 1 THEN 1 ELSE 0 END) AS borradores, SUM(CASE WHEN b_status = 0 THEN 1 ELSE 0 END) AS eliminados, COUNT(*) AS total FROM     @posts_borradores WHERE b_user = $uid"));
		return $resultados;
	}

	public function obtenerBorrador(int $status = 1, int $bid = 0) {
		$uid = $this->helper->UserId();
		$resultado = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT bid, b_title, b_body, b_tags, b_date, b_status, b_causa FROM @posts_borradores WHERE `bid` <> $bid AND `b_user` = $uid AND b_status = $status LIMIT 1"));
		$resultado['b_date'] = $this->helper->formatearFecha($resultado['b_date'], true);
		$resultado['b_tags'] = array_map('trim', explode(',', $resultado['b_tags']));
		//
		return $resultado;
	}

	/*
		delDraft()
	*/
	public function delDraft(){
		global $tsCore, $tsUser;
		//
		$bid = (int)$_POST['borrador_id'];
      if(db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @posts_borradores WHERE `bid` = $bid AND `b_user` = {$tsUser->info['user_id']}")) return '1: Borrador eliminado';
		else return '0: Ocurri&oacute; un error';
	}

	
	
}