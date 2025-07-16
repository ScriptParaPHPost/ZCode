<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Clase para el manejo de los helper
 *
 * @name    c.helper.php
 * @author  Miguel92
 */

class Helper {

	protected object $Core;
	protected object $User;

	public function __construct() {
		$this->Core = new tsCore;
		$this->User = new tsUser;
	}

	public function UserId(): int {
		return (int)$this->User->info['user_id'] ?? 0;
	}

	public function Username(): string {
		return (string)$this->User->info['user_name'] ?? '';
	}

	public function formatearFecha(int $date = 0, bool $show = false): string {
		# Creamos
		$tiempo = time() - $date;
		if($date <= 0) return "Nunca";
		// Declaración de unidades de tiempo, aunque es un aproximado
		// Ya que existe años bisiestos 366 días
		$unidades = [
		  31536000 => ["a&ntilde;o", "a&ntilde;os"],
		  2678400 => ["mes", "meses"],
		  604800 => ["semana", "semanas"],
		  86400 => ["d&iacute;a", "d&iacute;as"],
		  3600 => ["hora", "horas"],
		  60 => ["minuto", "minutos"],
		];
		foreach($unidades as $segundos => $nombre){
			$round = round($tiempo / $segundos);
			$s = ($segundos === 2678400) ? 'es' : 's';
			if($tiempo <= 60) $hace = "instantes";
			else {
				if($round > 0) {
					$hace = "{$round} {$nombre[($round > 1 ? 1 : 0)]}";
					break;
				}
			}
		}
		// Si se ha establecido la opción $show, se agrega 'Hace' al resultado
		return (string)($show ? "Hace " : "") . $hace;
	}

	public function categoriaImagen(string $seo = ''): string {
		return (string)$this->Core->settings['categories'] . '/' . $seo;
	}
}