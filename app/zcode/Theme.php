<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Funciones globales
 *
 * @name    Theme.php
 * @author  Miguel92
 */

class Theme {

	/**
	 * Establece el esquema de color del usuario
	 *
	 * @global tsUser $tsUser Objeto global que contiene información del usuario
	 * @return array Array con el color y esquema del usuario o valores predeterminados
	 */
	public function setSchemeColor(): array {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			include TS_ZCODE . 'datos.php';
			// Obtiene el esquema de color del usuario desde la base de datos
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_scheme`, `user_color`, `user_customize` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			return [
				'color' => $tsColores[$data['user_color']] ?? 'default',
				'scheme' => $tsSchemes[$data['user_scheme']] ?? 'light'
			];
		}
		return ['color' => 'default', 'scheme' => 'light'];
	}

	/**
	 * Establece el esquema de color del usuario
	 *
	 * @global tsUser $tsUser Objeto global que contiene información del usuario
	 * @return array Array con el color y esquema del usuario o valores predeterminados
	 */
	public function setColorCustomize() {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			// Obtiene el esquema de color del usuario desde la base de datos
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_customize` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			return explode(';', $data["user_customize"]);
		}
	}

	public function setThemeFont(): array {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			include TS_ZCODE . 'datos.php';
			// Obtiene el esquema de color del usuario desde la base de datos
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_font_family`, `user_font_size` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			return [
				'family' => $data['user_font_family'] ?? 'tema',
				'size' => $data['user_font_size'] ?? 'md'
			];
		}
		return ['family' => 'tema', 'size' => 'md'];
	}

}