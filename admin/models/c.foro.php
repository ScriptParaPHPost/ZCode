<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los foro
 *
 * @name    c.foro.php
 * @author  Miguel92
 */

class tsForo {

	public function getForos() {
		global $tsCore;
		# Obtenemos todos los foros
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT fid, super_nombre, super_descripcion, super_color, super_img FROM @posts_supercategorias"));
		# Mostraremos 3 categorías
		$max_display = 3;
		foreach($data as $k => $super) {
			$data[$k]['super_img'] = $tsCore->imageCat($super['super_img'] ?? '1f30d.svg');
			$subcategorias = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c_nombre, c_seo FROM @posts_categorias WHERE c_foro = {$super['fid']}"));

			# Solo mostraremos 3
			$total_tags = safe_count($subcategorias);
			$remaining_tags = $total_tags - $max_display;
			$data[$k]['super_subcategorias'] = array_slice($subcategorias, 0, $max_display);
	    	$data[$k]['remaining_tags'] = ($remaining_tags > 0) ? "+{$remaining_tags}" : "";
		}
		return $data;
	}

	public function getForo() {
		$fid = (int)$_GET['fid'];
		# Obtenemos todos los foros
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT super_nombre, super_descripcion, super_color, super_img FROM @posts_supercategorias WHERE fid = $fid"));
		return $data;
	}

	private function dataCat(string $type = '', int $orden = 0) {
		global $tsCore;
		if(isset($_POST['save'])) unset($_POST['save']);
		$nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['super_nombre']));
		$categoria = [
			"nombre" => $nombre,
			"descripcion" => $tsCore->setSecure($_POST['super_descripcion']),
			"color" => $tsCore->setSecure($_POST['super_color']),
			"img" => $tsCore->setSecure($_POST['super_img'])
		];
		return $categoria;
	}

	public function saveCategoria() {
		global $tsCore;
		$fid = (int)$_GET['fid'];
		$categoria = $tsCore->getIUP($this->dataCat(), 'super_');
		# Guardamos en la tabla
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts_supercategorias SET $categoria WHERE fid = $fid"));
	}

	public function newCategoria() {
		global $tsCore;
		# Insertamos los datos
		if(isset($_POST['save'])) unset($_POST['save']);
		if (insertDataInBase([__FILE__, __LINE__], '@posts_supercategorias', $_POST)) return true;
	}

	public function delCategoria() {
		global $tsCore;
		$fid = (int)$_POST['fid'];
		if (deleteFromId([__FILE__, __LINE__], '@posts_supercategorias', "fid = $fid")) return '1: Categoría eliminada';
		return '0: Problemas al eliminar.';
	}

}