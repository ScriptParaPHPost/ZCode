<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\utils;

class OAuthentication {

	private $version;

	public function	__construct() {
	}

	public function version(string $version = '') {
		$this->version = $version;
	}
	
	private function setEndpointGitHub(string $type = ''): string {
		$link = 'https://github.com';
		$endpoint = [
			'authorize' => "$link/login/oauth/authorize",
			'token' => "$link/login/oauth/access_token",
			'revoke' => "",
			'user' => "https://api.github.com/user",
			'scope' => "user"
		];
		return $endpoint[$type];
	}

	private function setEndpointDiscord(string $type = ''): string {
		$link = 'https://discord.com';
		$endpoint = [
			'authorize' => "$link/oauth2/authorize",
			'token' => "$link/api/oauth2/token",
			'revoke' => "$link/api/oauth2/token/revoke",
			'user' => "$link/api/v10/users/@me",
			'scope' => "email identify"
		];
		return $endpoint[$type];
	}

	private function setEndpointGoogle(string $type = ''): string {
		$link = 'https://accounts.google.com';
		$secondary = 'https://www.googleapis.com';
		$endpoint = [
			'authorize' => "$link/o/oauth2/auth",
			'token' => "$link/o/oauth2/token",
			'revoke' => "",
			'user' => "$secondary/oauth2/{$this->version}/userinfo",
			'scope' => "$secondary/auth/userinfo.email $secondary/auth/userinfo.profile"
		];
		return $endpoint[$type];
	}

	private function setEndpointFacebook(string $type = ''): string {
		$link = 'https://www.facebook.com';
		$secondary = 'https://graph.facebook.com';
		$endpoint = [
			'authorize' => "$link/{$this->version}/dialog/oauth",
			'token' => "$secondary/oauth/access_token",
			'revoke' => "",
			'user' => "$secondary/{$this->version}/me?fields=id,name,email,picture,short_name",
			'scope' => "email,public_profile"
		];
		return $endpoint[$type];
	}

	public function getEndPoints(string $social = '', string $type = '') {
		return match($social) {
			'github' => $this->setEndpointGitHub($type),
			'discord' => $this->setEndpointDiscord($type),
			'google' => $this->setEndpointGoogle($type),
			'facebook' => $this->setEndpointFacebook($type)
		};
	}

	/**
	 * Genera URLs de autorización OAuth para diferentes proveedores sociales.
	 *
	 * @return array Un array asociativo con el nombre del proveedor como clave y la URL de autorización como valor.
	 */
	public function OAuth(string $redirect = ''): array {
	   // Obtener la lista de proveedores OAuth
	   $OAuths = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social'));
	   $routes = [];
	    
	   foreach ($OAuths as $auth) {
	      // Preparar los parámetros para la solicitud OAuth
	      $parameters['client_id'] = $auth['social_client_id'];
	      $parameters['scope'] = $this->getEndPoints($auth['social_name'], 'scope');
	      $parameters['response_type'] = ($auth['social_name'] === 'github') ? '' : 'code';
	      $parameters['redirect_uri'] = $auth['social_redirect_uri'];
	      // Eliminar el parámetro response_type si es 'github'
	      if ($auth['social_name'] === 'github') {
	         unset($parameters['response_type']);
	      }
	      if(in_array($auth['social_name'], ['google', 'discord'])) {
				$parameters['prompt'] = 'consent';
			}
	      // Construir la URL de autorización
	      $queryString = http_build_query(array_filter($parameters));
	      $authorizeUrl = $this->getEndPoints($auth['social_name'], 'authorize');
	      $routes[$auth['social_name']] = "$authorizeUrl?$queryString";
	   }
	   return $routes;
	}

}