<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

class Theme {

	/**
	 * Establece el esquema de color del usuario
	 *
	 * @global tsUser $tsUser Objeto global que contiene información del usuario
	 * @return type String con el color, el esquema del usuario o valores predeterminados
	 */
	public function setSchemeColor(string $type = '') {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			include TS_ZCODE . 'datos.php';
			// Obtiene el esquema de color del usuario desde la base de datos
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_scheme`, `user_color`, `user_customize` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			$options = [
				'color' => $tsColores[$data['user_color']] ?? 'default',
				'scheme' => $tsSchemes[$data['user_scheme']] ?? 'light'
			];
			return $options[$type];
		} else {
			$options = ['color' => 'default', 'scheme' => 'light'];
			return $options[$type];
		}
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

	public function setThemeFont(string $type = '') {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			include TS_ZCODE . 'datos.php';
			// Obtiene el esquema de color del usuario desde la base de datos
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_font_family`, `user_font_size` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			$options = [
				'family' => $data['user_font_family'] ?? 'tema',
				'size' => $data['user_font_size'] ?? 'md'
			];
			return $options[$type];
		} else {
			$options = ['family' => 'tema', 'size' => 'md'];
			return $options[$type];
		}
	}

	public function getSettingPageBox() {
		global $tsUser;
		// Verifica si el usuario está registrado
		if ($tsUser->is_member) {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `user_pagebox` FROM @perfil WHERE `user_id` = {$tsUser->uid}"));
			return (int)$data["user_pagebox"] === 1;
		}
	}

	public function getSettingsTheme() {
		$attrs = [
			"data-theme=\"{$this->setSchemeColor('scheme')}\"",
			"data-theme-color=\"{$this->setSchemeColor('color')}\"",
			"data-font-family=\"{$this->setThemeFont('family')}\"",
			"data-font-size=\"{$this->setThemeFont('size')}\"",
		];
		return join(' ', $attrs);
	}

	public function preloadFont() {
		global $tsCore;
		$fontFile = match($this->setThemeFont('family')) {
			"dinpro" => "DINPro-CondensedRegular.woff2",
			"inter" => "Inter.woff2",
			"neomatrix" => "NeomatrixCode.ttf",
			"pixellari" => "Pixellari.ttf",
			"roboto" => "RobotoMono.ttf",
			"ubuntu" => "UbuntuMono.ttf",
			default => "Inter.woff2"
		};
		$type = explode('.', $fontFile)[1];
		$file = $tsCore->setRoutes('assets', 'fonts') . '/' . $fontFile;
		return "<link rel=\"preload\" href=\"{$file}\" as=\"font\" type=\"font/{$type}\" crossorigin=\"anonymous\">";
	}

}