<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control denuncias
 *
 * @name    c.swat.php
 * @author  ZCode | PHPost
 */
class tsSwat {
	
	private $id;

	private $type;

	private $razon;

	private $extras;

	/*
		setDenuncia()
	*/
	public function setDenuncia($obj_id, $type = 'posts'){
		global $tsCore, $tsUser;
		// VARS
		$this->id = $obj_id;
		$this->type = $type;
		$this->razon = $tsCore->setSecure($_POST['razon']);
		$this->extras = $tsCore->setSecure($_POST['extras']);
		$date = time();
		$methodName = 'setDenuncia' . ucfirst($type);
    	if (method_exists($this, $methodName)) {
      	return $this->$methodName();
    	} else {
      	exit("El método $methodName no está definido en esta clase.");
    	}
	}

	private function setDenunciaPosts() {
		$tsUser = new tsUser;
		$date = time();
		// ¿ES MI POST O ESTÁ EN STICKY?
		$my_post = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `post_id`, `post_user`, `post_sticky` FROM @posts WHERE `post_id` = {$this->id} LIMIT 1"));
		// El post no existe
		if(empty($my_post['post_id'])) return '0: No puedes denunciar un post que no existe.';
		// Es mi post
		if($my_post['post_user'] == $tsUser->uid) return '0: No puedes denunciar tus propios post.';
		// Esta fijado, no puedes
		if((int)$my_post['post_sticky']) return '0: No puedes denunciar posts en sticky.';
		// Esta fijado, no puedes
		if($tsUser->is_admod) return '0: No puedes denunciar siendo moderador, pero puedes atender las denuncias de los usuarios.';
		// YA HA REPORTADO?
		$denuncio = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT `did` FROM @denuncias WHERE `obj_id` = {$this->id} AND `d_user` = {$tsUser->uid} AND `d_type` = 1"));
		// Ya denunciaste!
		if(!empty($denuncio)) return '0: Ya hab&iacute;as denunciado este post.';
		// CUANTAS DENUNCIAS LLEVA?
		$denuncias = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT did FROM @denuncias WHERE obj_id = {$this->id} && d_type = 1"));
		// OCULTAMOS EL POST SI YA LLEVA MAS DE 3 DENUNCIAS
		if($denuncias >= 2){
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_status` = 1 WHERE `post_id` = {$this->id}");
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET `stats_posts` = stats_posts - 1 WHERE `stats_no` = 1");
		}
		// INSERTAR NUEVA DENUNCIA
		return (db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @denuncias (`obj_id`, `d_user`, `d_razon`, `d_extra`, `d_type`, `d_date`) VALUES ({$this->id}, {$tsUser->uid}, '{$this->razon}', '{$this->extras}', 1, $date)")) ? '1: La denuncia fue enviada.' : '0: Error, int&eacute;ntalo m&aacute;s tarde.';
	}

	private function setDenunciaFoto() {
		$tsUser = new tsUser;
		$date = time();
		// ¿ES MI FOTO O ESTÁ OCULTA?
		$my_photo = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `foto_id`, `f_user`, `f_status` FROM @fotos WHERE `foto_id` = {$this->id} LIMIT 1"));
		// la foto no existe
		if(empty($my_photo['foto_id'])) return '0: Esta foto no existe';	
		// Es mi foto
		if($my_photo['f_user'] == $tsUser->uid) return '0: No puedes denunciar tus propias fotos.';
		// La foto esta oculta, no puedes
		if((int)$my_photo['f_status']) return '0: No puedes denunciar fotos ocultas.';
		// YA HA REPORTADO?
		$denuncio = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT `did` FROM @denuncias WHERE `obj_id` = {$this->id} AND `d_user` = {$tsUser->uid} AND `d_type` = 4"));
		// Ya la habías denunciado	
		if(!empty($denuncio)) return '0: Ya hab&iacute;as denunciado esta foto.';
		// CUANTAS DENUNCIAS LLEVA?
		$denuncias = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT `did` FROM @denuncias WHERE `obj_id` = {$this->id}"));
		// OCULTAMOS LA FOTO SI YA LLEVA MÁS DE 3 DENUNCIAS
		if($denuncias >= 2){
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @fotos SET `f_status` = 1 WHERE `foto_id` = {$this->id}");
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @stats SET `stats_fotos` = stats_fotos - 1 WHERE `stats_no` = 1");
		}
		// INSERTAR NUEVA DENUNCIA
		return (db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @denuncias (`obj_id`, `d_user`, `d_razon`, `d_extra`, `d_type`, `d_date`) VALUES ({$this->id}, {$tsUser->uid}, '{$this->razon}', '{$this->extras}', 4, $date)")) ? '1: La denuncia fue enviada.' : '0: Error, int&eacute;ntalo m&aacute;s tarde.';
	}

	private function setDenunciaMensaje() {
		$tsUser = new tsUser;
		$date = time();
		// YA HA REPORTADO?
		$denuncio = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT `did` FROM @denuncias WHERE `obj_id` = {$this->id} AND d_user = {$tsUser->uid} AND `d_type` = 2"));
		// Ya lo habías denunciado 
		if(!empty($denuncio)) return '0: Ya hab&iacute;as denunciado este mensaje. Nuestros moderadores ya lo analizan.';
		// DONDE LO BORRAREMOS?
		$where = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `mp_id`, `mp_to`, `mp_from` FROM @mensajes WHERE `mp_id` = {$this->id} LIMIT 1"));
		// El mensaje no existe	 
		if(empty($where['mp_id'])) return '0: Opps... Este mensaje no existe.';
		//
		if($where['mp_to'] == $tsUser->uid) $del_table = 'mp_del_to';
		elseif($where['mp_from'] == $tsUser->uid) $del_table = 'mp_del_from';
		// INSERTAR NUEVA DENUNCIA
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @denuncias (obj_id, d_user, d_razon, d_extra, d_type, d_date) VALUES ({$this->id}, {$tsUser->uid}', 0, '', 2, $date)")){
			// BORRAMOS
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @mensajes SET $del_table = 1 WHERE `mp_id` = {$this->id}");
			return '1: Has denunciado un mensaje como correo no deseado.';
		} else return '0: Error! Int&eacute;ntalo m&aacute;s tarde.';
	}

	private function setDenunciaUsuario() {
		$tsUser = new tsUser;
		$date = time();
		$oid = (int)$this->id;
		// YA HA REPORTADO?
		$denuncio = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT did FROM @denuncias WHERE obj_id = $oid AND d_user = {$tsUser->uid} AND d_type = 3"));
		// Ya lo habías denunciado!			 
		if(!empty($denuncio)) return '0: Ya hab&iacute;as denunciado a este usario.';

		$username = $tsUser->getUserName($oid);
		// El usuario no existe
		if(empty($username)) return '0: Opps... Este usuario no existe.';
		// LO REPORTAMOS...
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @denuncias (obj_id, d_user, d_razon, d_extra, d_type, d_date) VALUES ($oid, {$tsUser->uid}, '{$this->razon}', '{$this->extras}', 3, $date)")) {
			// SUMAMOS
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @miembros SET `user_bad_hits` = user_bad_hits + 1 WHERE `user_id` = $oid");
			return '1: Este usuario ha sido denunciado.';
		} else return '0: Error! Int&eacute;ntalo m&aacute;s tarde.';
	}
}