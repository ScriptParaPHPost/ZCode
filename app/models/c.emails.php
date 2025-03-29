<?php 

if (!defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

ini_set('error_log', EMAIL_LOG);
ini_set('mail.add_x_header', '1');

class tsEmail {

   private array $emailInfo = [];

   public string $emailSubject = '';

   public string $emailHeaders = '';

   public string $emailBody = '';

   public string $emailTo = '';

   public string $emailTemplate = 'zcode';

   private $core;

   public function __construct(string $template = '', string $subjects = '') {
      $this->core = new tsCore;
      $this->emailTemplate = $template;
      $this->emailHeaders = $subjects;
      $this->emailInfo = [
         'data' => $this->core->setSecure('nope'),
         'ref' => $this->core->setSecure('chuck testa!')
      ];
   }

   /**
    * Setea los encabezados para el correo.
    */
   private function setHeaders(): string {
      return implode("\r\n", [
         'MIME-Version: 1.0',
         'X-Priority: 1',
         'Content-type: text/html; charset=UTF-8',
         sprintf('From: %s <%s>', $this->core->setSecure($this->core->settings['titulo']), $this->core->setSecure($this->core->settings['domain'])),
         sprintf('Reply-To: no-reply@%s', $this->core->setSecure($this->core->settings['domain'])),
         'X-Mailer: PHP/' . PHP_VERSION
      ]);
   }

   /**
    * Genera el cuerpo del correo utilizando una plantilla.
    */
   private function setBody(): ?string {
      include_once TS_EXTRA . "emails/" . $this->core->setSecure($this->emailTemplate) . ".php";
      
      // Definir búsqueda y reemplazo
      $placeholders = ['{url}', '{titulo}', '{slogan}', '{contenido}', '{asunto}'];
      // Por lo que vamos a reemplazar
      $replacements = [
         $this->core->setSecure($this->core->settings['url']), 
         $this->core->setSecure($this->core->settings['titulo']), 
         $this->core->setSecure($this->core->settings['slogan']), 
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
      return sprintf("=?UTF-8?Q?%s?=", $this->core->setSecure($this->emailTo));
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