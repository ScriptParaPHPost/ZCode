<?php

/**
 * @name reCaptcha.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Para obtener y verificar captcha
**/

class reCaptcha {

	public $RECAPTCHA_TOKEN;

	private $API_RECAPTCHA_URL;

	private $API_SECRET_KEY;

	private $USER_IP;

	// ...
	public function __construct() {
		global $tsCore;
		//
		$this->API_RECAPTCHA_URL = "https://www.google.com/recaptcha/api/siteverify";
		$this->API_SECRET_KEY = $tsCore->settings['skey'];
		$this->USER_IP = $tsCore->getIP();
	}

	private function build_query() {
		$HTTP_BUILD_QUERY['secret'] = $this->API_SECRET_KEY;
		$HTTP_BUILD_QUERY['response'] = $this->RECAPTCHA_TOKEN;
		$HTTP_BUILD_QUERY['remoteip'] = $this->USER_IP;
		return http_build_query($HTTP_BUILD_QUERY);
	}

	private function curl_options() {
		return [
			CURLOPT_URL => $this->API_RECAPTCHA_URL,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $this->build_query(),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 10 // Set a reasonable timeout
		];
	}

	// Función para comprobar reCaptcha v3
	public function recaptcha_verify_human() {
		if (empty($this->RECAPTCHA_TOKEN)) return 'recaptcha: No hemos podido validar tu humanidad';
		//
		$init = curl_init();
		curl_setopt_array($init, $this->curl_options());
		$response = curl_exec($init);
		if (curl_errno($init)) {
			// Handle curl error if needed
			curl_close($init);
			return false;
		}
		curl_close($init);
		$responseData = json_decode($response, true);
		if (!is_array($responseData)) return false;
		return $responseData['success'] ?? false;
	}
}