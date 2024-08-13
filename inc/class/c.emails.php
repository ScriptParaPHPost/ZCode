<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Modelo para el control del envio de emails
 *
 * @name    c.emails.php
 * @author  Miguel92 & PHPost.es & Miguel92
 */
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);

class tsEmail {

	public $email_info = [];
	public $emailSubject;
	public $emailHeaders;
	public $emailBody;
	public $emailTo;

	public function __construct(string $tsEmailData = '', string $tsEmailRef = '') {
		$this->email_info['data'] = $tsEmailData;
		$this->email_info['ref'] = $tsEmailRef;
	}

	/**
	 * setHeaders()
	*/
	public function setHeaders() {
		global $tsCore;
		$headers = implode("\r\n", [
			'MIME-Version: 1.0',
			'X-Priority: 1',
		   'Content-type: text/html; charset=UTF-8',
		   'From: ' . $tsCore->settings['titulo'] . ' <'. $tsCore->settings['domain'] .'>',
		   'Reply-To: no-reply@' . $tsCore->settings['domain'],
		   'X-Mailer: PHP/' . PHP_VERSION
		]);
		//
		return $headers;
	}

	/**
	 * private function setBody()
	 * Generamos el contenido para enviar con plantilla
	*/
	private function setBody() {
		global $tsCore;
   	include_once TS_EXTRA . "emails/phpost.php";
   	// Buscamos para reemplazar
   	$search = ['{1}', '{2}', '{3}'];
   	// Por lo que vamos a reemplazar
   	$from = [$tsCore->settings['url'], $tsCore->settings['titulo'], $this->emailBody];
   	// Realizamos los cambios
		$contenido = str_replace($search, $from, $plantilla);

		return $contenido;	
	}

	/**
	 * public function setTo()
	*/
	function setTo(){
		return "=?UTF-8?Q?" . $this->emailTo . "?=";
	}

	/**
	 * public function setEmailSubject()
	*/
	function setEmailSubject(){
		switch($this->email_info['ref']) {
			case 'signup' :
				$this->emailSubject = "Por favor completa tu registro.";
			break;
		}
		// ENCODE SUBJECT FOR UTF8
		return "=?UTF-8?B?".base64_encode($this->emailSubject)."?=";
	}

	/**
	 * public function sendEmail()
	 * Para enviar el email
	*/
	public function sendEmail(){
		return mail(self::setTo(), self::setEmailSubject(), self::setBody(), self::setHeaders());
	}

}