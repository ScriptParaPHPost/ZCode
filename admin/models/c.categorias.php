<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de las categorias
 *
 * @name    c.categorias.php
 * @author  ZCode | PHPost
 */

class tsCategorias {

		# ===================================================
	# CATEGORIAS
	# * dataCat() :: Armamos el array para saveCat() & newCat()
	# * saveOrden() :: Guardamos el orden de las categorias
	# * getCat() :: Obtenemos la categoria
	# * saveCat() :: Guardamos los nuevos datos de la categoría
	# * MoveCat() :: Mover de categoría
	# * newCat() :: Creamos una nueva categoría
	# * delCat() :: Eliminamos la categoría 
	# ===================================================
	private function dataCat(string $type = '', int $orden = 0) {
		global $tsCore;
		$nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		$seo = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_seo']));
		$categoria = [
			"nombre" => $nombre,
			"seo" => $seo,
			"foro" => $tsCore->setSecure($_POST['c_foro']),
			"img" => $tsCore->setSecure($_POST['c_img']),
			"color" => $tsCore->setSecure($_POST['c_color']),
			"descripcion" => $tsCore->setSecure($_POST['c_descripcion']),
		];
		if($type === 'nueva') $categoria['orden'] = $orden;
		return $categoria;
	}
	private function getSqlCats(int $cid = 0) {
		$where = ($cid === 0) ? "" : " WHERE cid = $cid";
		return db_exec([__FILE__, __LINE__], 'query', "SELECT cid, c_orden, c_foro, c_nombre, c_descripcion, c_seo, c_img, c_color, c_private FROM @posts_categorias$where");
	}
	public function saveOrden() {
		$ordenado = [];
		# Obtenemos lista con el nuevo orden
		$nuevo_orden = 1;
		foreach (explode(',', $_POST["cats"]) as $orden) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_categorias SET c_orden = $nuevo_orden WHERE cid = $orden");
			array_push($ordenado, $nuevo_orden);
			$nuevo_orden++;
		}
	}
	public function getCats() {
		global $tsCore;
		# Obtenemos la información
		$data = result_array($this->getSqlCats());
		foreach($data as $k => $super) {
			$data[$k]['c_img'] = $tsCore->imageCat($super['c_img'] ?? '1f30d.svg');
		}
		# Retornamos los daots
		return $data;
	}
	public function getCat() {
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		# Obtenemos la información
		$data = db_exec('fetch_assoc', $this->getSqlCats((int)$_GET['cid']));
		# Retornamos los daots
		return $data;
	}
	public function saveCat() {
		global $tsCore;
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		$categoria = $tsCore->getIUP($this->dataCat(), 'c_');
		# Guardamos en la tabla
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_categorias SET $categoria WHERE cid = $cid")) return true;
	}
	public function MoveCat() {
		$new_category = (int)$_POST['newcid'];
		$old_category = (int)$_POST['oldcid'];
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_category = $new_category WHERE post_category = $old_category")) return true;
	}
	public function newCat() {
		global $tsCore;
		# Valores
		$c_nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		# Orden
		$orden = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS total FROM @posts_categorias'))['total'] + 1;
		# Insertamos los datos
		$categoria = $this->dataCat('nueva', $orden);
		if (insertDataInBase([__FILE__, __LINE__], '@posts_categorias', $categoria, 'c_')) return true;
	}
	public function delCat() {
		global $tsCore;
		//
		$cid = (int)$_GET['cid'];
		$ncid = (int)$_POST['ncid'];
		// MOVER
		if (empty($ncid) and $ncid === 0) return 'Antes de eliminar una categor&iacute;a debes elegir a donde mover sus subcategor&iacute;as.';
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET post_category = $ncid WHERE post_category = $cid")) {
			if(removeDataById([__FILE__, __LINE__], '@posts_categorias', "cid = $cid")) return true;
		// SI LLEGÓ HASTA AQUI HUBO UN ERROR.
		} else return 'Lo sentimos ocurri&oacute; un error';
	}

}