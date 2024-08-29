<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los afiliados
 *
 * @name    c.afiliado.php
 * @author  ZCode | PHPost
 */
class tsAfiliado {


	public function getAfiliados(string $type = 'home'){
		$select = "aid, a_titulo, a_url, a_banner, a_descripcion";
		if($type === 'admin') $select .= ", a_sid, a_hits_in, a_hits_out, a_date, a_active";
		//
		$from = ($type === 'home') ? "WHERE a_active = 1 ORDER BY RAND() LIMIT 5" : "";
		//
		return result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT $select FROM @afiliados $from"));
	}
	public function getAfiliado(string $type = 'home'){
		$aid = ($type === 'home') ? (int)$_POST['ref'] : (int)$_GET['aid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT aid, a_titulo, a_url, a_banner, a_descripcion FROM @afiliados WHERE aid = $aid"));
		return $data;
	}
	public function newAfiliado(){
		global $tsCore, $tsMonitor;
		//
		$dataIn['titulo'] =htmlspecialchars($tsCore->parseBadWords($_POST['atitle']));
		$dataIn['url'] = htmlspecialchars($tsCore->parseBadWords($_POST['aurl']));
		$dataIn['banner'] = htmlspecialchars($tsCore->parseBadWords($_POST['aimg']));
		$dataIn['descripcion'] = htmlspecialchars($tsCore->parseBadWords($_POST['atxt']));
		$dataIn['sid'] = (int)$_POST['aID'];
		$dataIn['date'] = time();
		// COMPROBAMOS TODOS LOS CAMPOS
		if(!$dataIn['titulo'] || !$dataIn['url'] || $dataIn['url'] == 'http://' || !$dataIn['banner'] || $dataIn['banner'] == 'http://' || !$dataIn['descripcion']) die('2: Faltan datos');
		// FILTRAMOS URL
		if(!filter_var($dataIn['url'], FILTER_VALIDATE_URL)) die('0: Url incorrecta'); 
		//
		if(insertDataInBase([__FILE__, __LINE__], '@afiliados', $dataIn, 'a_')) {
			$afid = db_exec('insert_id');
		  	// AVISO
			$aviso = "<center><a href=\"{$dataIn['url']}\"><img src=\"{$dataIn['banner']}\" title=\"{$dataIn['titulo']}\"/></a></center> <br /><br /> {$dataIn['titulo']} quiere ser su afiliado, dir&iacute;jase a la administraci&oacute;n para aceptar o cancelarla.";
			$tsMonitor->setAviso(1,'Nueva afiliaci&oacute;n', $aviso, 0);
			//
			$entit = $tsCore->settings['titulo'];
			$enurl = $tsCore->settings['url'].'/?ref='.$afid;
			$enimg = $tsCore->settings['banner'];
			//
			$return = '1: <div class="emptyData">Tu afiliaci&oacute;n ha sido agregada!</div><br>';
			$return .= '<div style="padding:0 35px;">Se le ha notificado al administrador tu afiliaci&oacute;n para que la apruebe, mientras tanto copia el siguiente c&oacute;digo, ser&aacute; con el cual nos debes enlazar.<br><br>';
			$return .= '<div class="form-line">';
			$return .= '<label for="atitle">C&oacute;digo HTML</label>';
			$return .= '<textarea tabindex="4" rows="10" style="height:60px; width:295px" onclick"select(this)"><a href="'.$enurl.'" target="_blank" title="'.$entit.'"><img src="'.$enimg.'"></a></textarea>';
			$return .= '</div>';
			$return .= '</div>';
		}
		//
		return $return;
	}
	public function EditarAfiliado() {
		global $tsCore;
		//
		$afiliado = (int)$_GET['aid'];
		$newData = [
			'titulo' => $tsCore->parseBadWords($_POST['af_title']),
			'url' => $tsCore->parseBadWords($_POST['af_url']),
			'banner' => $tsCore->parseBadWords($_POST['af_banner']),
			'descripcion' => $tsCore->parseBadWords($_POST['af_desc'])
		];
		// VERIFICAMOS DATOS
		if(!$afiliado || !$newData['titulo'] || !$newData['url'] || !$newData['banner'] || !$newData['descripcion']) return '0: Faltan datos';
		// FILTRAMOS URL
		if(!filter_var($newData['url'], FILTER_VALIDATE_URL)){ return '0: Url incorrecta'; }
		// ACTUALIZAMOS TABLA
		$afs = $tsCore->getIUP($newData , 'a_');
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @afiliados SET $afs WHERE aid = $afiliado")) ? '1: Guardado' : '0: Ocurri&oacute; un error';
	}
	public function DeleteAfiliado(int $aid = 0){
		global $tsUser;
		if($tsUser->is_admod == 1) {
			if(deleteFromId([__FILE__, __LINE__], '@afiliados', "aid = $aid")) return '1: Afiliado eliminado';
		} else return '0: T&uacute;o, no puedes hacer eso';
	}
	public function SetActionAfiliado(){
		global $tsUser;
		$afiliado = (int)$_POST['aid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT a_active FROM @afiliados WHERE aid = $afiliado"));
		$status = ($data['a_active'] === 1);
		$active = $status ? 0 : 1;
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @afiliados SET a_active = $active WHERE aid = $afiliado")) return ($status ? 2 : 1) . ': Afiliado ' . ($status ? 'des' : '') . 'habilitado';
		else return '0: Ocurri&oacute, un error';
	}
	public function urlOut(){
		global $tsCore;
		  //
		$ref = (int)$_GET['ref'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT a_url, a_sid FROM @afiliados WHERE aid = $ref LIMIT 1"));
		if(isset($data['a_url'])) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @afiliados SET a_hits_out = a_hits_out + 1 WHERE aid = $ref");
			// Y REDIRECCIONAMOS
			$enref = empty($data['a_sid']) ? '/' : '/?ref='.$data['a_sid']; // REFERIDO
			// REDIRECCIONAMOS
			$tsCore->redirectTo($data['a_url'] . $enref);
			exit();
		} else $tsCore->redirectTo($tsCore->settings['url']);
	}
	public function urlIn(){
		global $tsCore;
		$ref = (int)$_GET['ref'];
		if($ref > 0) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @afiliados SET a_hits_in = a_hits_in + 1 WHERE aid = $ref");
		}
		$tsCore->redirectTo($tsCore->settings['url']);
	}
}