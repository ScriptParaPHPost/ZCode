<?php
declare(strict_types=1);

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Modelo para el control del envío de emails.
 * @name    c.emails.php
 * @author  ZCode | PHPost & Miguel92
 */

ini_set('error_log', DIR_ERROR_LOG . 'mail_error.log');
ini_set('mail.add_x_header', '1');

class tsEmail {

   private array $emailInfo = [];

   public string $emailSubject = '';

   public string $emailHeaders = '';

   public string $emailBody = '';

   public string $emailTo = '';

   public string $emailTemplate = 'zcode';

   public function __construct(string $emailData = '', string $emailRef = '') {
      $this->emailInfo = [
         'data' => $emailData,
         'ref' => $emailRef
      ];
   }

   /**
    * Setea los encabezados para el correo.
    */
   private function setHeaders(): string {
      global $tsCore;
      return implode("\r\n", [
      	'MIME-Version: 1.0',
      	'X-Priority: 1',
      	'Content-type: text/html; charset=UTF-8',
      	sprintf('From: %s <%s>', $tsCore->settings['titulo'], $tsCore->settings['domain']),
      	sprintf('Reply-To: no-reply@%s', $tsCore->settings['domain']),
      	'X-Mailer: PHP/' . PHP_VERSION
      ]);
   }

   /**
    * Genera el cuerpo del correo utilizando una plantilla.
    */
   private function setBody(): string {
      global $tsCore;
      include_once TS_EXTRA . "emails/" . $this->emailTemplate . ".php";
      
      // Definir búsqueda y reemplazo
   	$placeholders = ['{url}', '{titulo}', '{slogan}', '{contenido}', '{asunto}'];
   	// Por lo que vamos a reemplazar
   	$replacements = [
   		$tsCore->settings['url'], 
   		$tsCore->settings['titulo'], 
   		$tsCore->settings['slogan'], 
   		$this->emailBody, 
   		htmlentities($this->emailSubject, ENT_QUOTES | ENT_HTML401, 'UTF-8')
   	];
      
      // Reemplazar contenido en la plantilla
      return str_replace($placeholders, $replacements, $plantilla);
   }

   /**
    * Formatea la dirección de correo.
    */
   private function setTo(): string {
      return sprintf("=?UTF-8?Q?%s?=", $this->emailTo);
   }

   /**
    * Establece el asunto del correo en función de la referencia.
    */
   private function setEmailSubject(): string {
      $subjects = [
         'signup' => 'Por favor completa tu registro.'
      ];
      $this->emailSubject = $subjects[$this->emailInfo['ref']] ?? $this->emailSubject;
      
      return sprintf("=?UTF-8?B?%s?=", base64_encode($this->emailSubject));
   }

   /**
    * Envía el correo utilizando la función `mail()`.
    */
   public function sendEmail(): bool {
      if (!filter_var($this->emailTo, FILTER_VALIDATE_EMAIL)) {
         return false; // Validación del email
      }
      return mail($this->setTo(), $this->setEmailSubject(), $this->setBody(), $this->setHeaders());
   }
}