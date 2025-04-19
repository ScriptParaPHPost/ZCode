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

use app\models\Core;

class reCaptcha {

   public $RECAPTCHA_TOKEN;
   private $API_URL;
   private $API_SECRET_KEY;
   private $USER_IP;
   private $SERVICE_TYPE; // 'recaptcha' o 'hcaptcha'

   public function __construct() {
      // Obtener las claves públicas y secretas desde la configuración
      $this->API_SECRET_KEY = (new Core)->settings['skey'];  // Usamos la misma clave secreta
      $this->USER_IP = (new Core)->getIP();

      // Determinar el tipo de servicio basado en la longitud de la clave secreta
      if (strlen($this->API_SECRET_KEY) > 40) {
         // Si la longitud de la clave secreta es mayor que 40, asumimos que es hCaptcha
         $this->SERVICE_TYPE = 'hcaptcha';
         $this->API_URL = "https://hcaptcha.com/siteverify";  // URL de verificación de hCaptcha
      } else {
         // Si la longitud es de 40 o menos, asumimos que es reCaptcha
         $this->SERVICE_TYPE = 'recaptcha';
         $this->API_URL = "https://www.google.com/recaptcha/api/siteverify";  // URL de verificación de Google reCAPTCHA
      }
   }

   private function build_query() {
      $HTTP_BUILD_QUERY['secret'] = $this->API_SECRET_KEY;
      $HTTP_BUILD_QUERY['response'] = $this->RECAPTCHA_TOKEN;
      $HTTP_BUILD_QUERY['remoteip'] = $this->USER_IP;
      return http_build_query($HTTP_BUILD_QUERY);
   }

   private function curl_options() {
      return [
         CURLOPT_URL => $this->API_URL,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $this->build_query(),
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_TIMEOUT => 10 // Set a reasonable timeout
      ];
   }

   // Función para comprobar reCaptcha v3 o hCaptcha
   public function verify_human() {
      if (empty($this->RECAPTCHA_TOKEN)) return 'No hemos podido validar tu humanidad';

      $init = curl_init();
      curl_setopt_array($init, $this->curl_options());
      $response = curl_exec($init);

      if (curl_errno($init)) {
         curl_close($init);
         return false;
      }

      curl_close($init);
      $responseData = json_decode($response, true);

      if (!is_array($responseData)) return false;

      // Verificación de éxito basada en el tipo de servicio
      if ($this->SERVICE_TYPE == 'recaptcha') {
         return $responseData['success'] ?? false;
      } else if ($this->SERVICE_TYPE == 'hcaptcha') {
         return $responseData['success'] ?? false;
      }

      return false; // En caso de que no se reconozca el tipo
   }
}
