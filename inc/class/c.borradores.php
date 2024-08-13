<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los borradores
 *
 * @name    c.borradores.php
 * @author  Miguel92 & PHPost.es
 */
class tsDrafts {

	/*
		newDraft()
	*/
	public function newDraft($save = false){
		global $tsCore, $tsUser;
		//
		$draftData = [
			'date' => time(),
			'title' => $tsCore->setSecure($tsCore->parseBadWords($_POST['titulo']), true),
			'body' => $tsCore->setSecure($_POST['cuerpo'], true),
			'tags' => $tsCore->setSecure($tsCore->parseBadWords($_POST['tags']), true),
			'category' => $tsCore->setSecure($_POST['categoria']),
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
					$updates = $tsCore->getIUP($draftData, 'b_');
					if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_borradores SET '.$updates.' WHERE bid = $bid AND b_user = {$tsUser->info['user_id']}")) return '1: '.$bid;
					else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
		   	} else {
					// INSERT
			    	if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @posts_borradores (`b_user`, `b_date`, `b_title`, `b_body`, `b_tags`, `b_category`, `b_private`, `b_block_comments`, `b_sponsored`, `b_sticky`, `b_smileys`, `b_visitantes`, `b_status`) VALUES ({$tsUser->info['user_id']}, {$draftData['date']}, '{$draftData['title']}', '{$draftData['body']}', '{$draftData['tags']}', {$draftData['category']}, {$draftData['private']}, {$draftData['block_comments']}, {$draftData['sponsored']}, {$draftData['sticky']}, {$draftData['smileys']}, {$draftData['visitantes']}, 1)")) return '1: '.db_exec('insert_id');
			   	else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
				}
			} else $return = 'Categor&iacute;a';
		} else $return = 'T&iacute;tulos';
		//
		return '0: El campo <b>'.$return.'</b> es requerido para esta operaci&oacute;n';
		//
	}
	/*
		getDrafts()
	*/
	public function getDrafts(){
		global $tsCore, $tsUser;
		//
		$drafts = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_nombre, c.c_seo, c.c_img, b.bid, b.b_title, b.b_date, b.b_status, b.b_causa FROM @posts_categorias AS c LEFT JOIN @posts_borradores AS b ON c.cid = b.b_category WHERE b.b_user = {$tsUser->info['user_id']} ORDER BY b.b_date"));
		// SET
		$tipos = ['eliminados', 'borradores'];
		foreach($drafts as $draft){
         $causa = empty($draft['b_causa']) ? 'Eliminado por el autor' : htmlspecialchars($draft['b_causa']);
         $newdate = $tsCore->setHace($draft['b_date'], true);
         $draftjson = [
         	"id" => $draft['bid'],
         	"titulo" => $draft['b_title'],
         	"categoria" => $draft['c_seo'],
         	"imagen" => $draft['c_img'],
         	"fecha_guardado" => $newdate,
         	"causa" => $causa,
         	"categoria_name" => $draft['c_nombre'],
         	"tipo" => $tipos[$draft['b_status']],
         	"url" => "{$tsCore->settings['url']}/agregar/{$draft['bid']}",
         	"fecha_print" => $newdate
         ];
			$dft[] = json_encode($draftjson, JSON_FORCE_OBJECT);
		}
		return is_array($dft) ? join(',', $dft) : '';
	}
	/*
		getDraft()
	*/
	public function getDraft(int $status = 1){
		global $tsCore, $tsUser;
		//
		$bid = (int)$_GET['action'];
      return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT bid, b_user, b_date, b_title, b_body, b_tags, b_category, b_private, b_block_comments, b_sponsored, b_sticky, b_smileys, b_post_id, b_status, b_causa FROM @posts_borradores WHERE `bid` = $bid AND `b_user` = {$tsUser->info['user_id']} AND b_status = $status LIMIT 1"));
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