<?php 

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

class Paginator {
	
	private $baseUrl;

	public function __construct() {
		global $tsCore;
		$this->baseUrl = $tsCore->settings['url'];
	}

	/**
	 * Sistema de paginación automática [2023]
	 * @author Miguel92
	 * basados completamente en estos mods de ellos
	 * @author mdulises
	 * @author KMario
	 * @author ReModWrite
	*/
	public function system_pagination(int $totalItems = 0, int $itemsPerPage = 0, string $inPage = '') {
		// Obtenemos la pagina actual
		$currentPage = !isset($_GET['page']) ? 1 : (int)$_GET['page'];
		// Si no existe devolvemos algo vacío
		if ($totalItems <= 0) return 0;
		$page = (empty($inPage) ? '' : $inPage) . "?page=";
		$pagination['current'] = $currentPage;
		// Empezamos con la estructura de la paginación
		$pagination['item'] = '<nav class="pagination">';
		// Calculamos el total de páginas necesarias.
		$totalPages = ceil($totalItems / $itemsPerPage);
		// Limitamos el valor de $currentPage para asegurarnos de que no se exceda el rango.
		$currentPage = max(1, min($currentPage, $totalPages));
		// Enlace a página anterior.
		if ($currentPage > 1) {
			$pagination['item'] .= "<div class=\"page-item\"><a class=\"prev page-numbers\" href=\"{$this->baseUrl}/$page" . ($currentPage - 1) . "\" title=\"P&aacute;gina anterior\">&laquo;</a></div>";
		}
		// Enlaces de primera y última página.
		if ($currentPage > 3) {
			$pagination['item'] .= "<div class=\"page-item\"><a class=\"page-numbers\" href=\"{$this->baseUrl}/$page1\">1</a></div>";
			if ($currentPage > 6) {
				$pagination['item'] .= "<div class=\"page-item off\"><span class=\"page-numbers\">...</span></div>";
			}
		}
		// Mostramos los enlaces de la paginación.
		$startPage = max(1, $currentPage - 2);
		$endPage = min($totalPages, $currentPage + 2);
		//
		for ($i = $startPage; $i <= $endPage; $i++) {
			if($currentPage === $i) {
				$pagination['item'] .= "<div class=\"page-item\"><span aria-current=\"page\" class=\"page-numbers current\">{$i}</span></div>";
			} else {
				$pagination['item'] .= "<div class=\"page-item\"><a class=\"page-numbers\" href=\"{$this->baseUrl}/$page{$i}\">{$i}</a></div>";
			}
		}
		// Enlaces después del número 6.
		if ($currentPage < $totalPages - 4) {
			$pagination['item'] .= "<div class=\"page-item off\"><span class=\"page-numbers\">...</span></div>";
			$pagination['item'] .= "<div class=\"page-item\"><a class=\"page-numbers\" href=\"{$this->baseUrl}/$page{$totalPages}\">{$totalPages}</a></div>";
		}
		// Enlace a página siguiente.
		if ($currentPage < $totalPages) {
			$pagination['item'] .= "<div class=\"page-item\"><a class=\"next page-numbers\" href=\"{$this->baseUrl}/$page" . ($currentPage + 1) . "\" title=\"P&aacute;gina siguiente\">&raquo;</a></div>";
		}
		// Finalizamos la paginación
		$pagination['item'] .= '</nav>';
		return $pagination;
	}

}