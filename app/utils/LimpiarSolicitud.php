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

class LimpiarSolicitud {

   private array $get = [];
   private array $post = [];
   private array $cookie = [];

   public function __construct() {
		$this->validarGlobals();
		$this->validarReferer();
		$this->validarClaves();
		$this->sanitizarEntradas();
   }


   /**
    * Valida que no se esté inyectando la variable GLOBALS ni claves numéricas
    */
   private function validarGlobals(): void
   {
		if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS'])) {
         http_response_code(400);
         exit('Solicitud no válida: acceso a GLOBALS');
      }
   }

   /**
    * Evita ataques por referer cruzado (CSRF básico)
    */
   private function validarReferer(): void
   {
      $referer = $_SERVER['HTTP_REFERER'] ?? '';
      $host = $_SERVER['HTTP_HOST'] ?? '';
      $refererHost = parse_url($referer, PHP_URL_HOST);

      if (!empty($referer) && $refererHost && $refererHost !== $host && $_SERVER['REQUEST_METHOD'] === 'POST') {
         http_response_code(403);
         exit('Solicitud no autorizada.');
      }
   }

   /**
    * Valida que las claves de entrada no sean numéricas
    */
   private function validarClaves(): void
   {
      $fuentes = [$_GET, $_POST, $_COOKIE, $_FILES];
      foreach ($fuentes as $fuente) {
         foreach (array_keys($fuente) as $key) {
            if (is_numeric($key)) {
               http_response_code(400);
               exit('Claves numéricas no permitidas.');
            }
         }
      }
   }

   /**
    * Sanitiza GET, POST y COOKIE usando filter_input_array
    */
   private function sanitizarEntradas(): void
   {
      $this->get = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
      $this->post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
      $this->cookie = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];

      // Para asegurarte que $_REQUEST no contenga sorpresas
      $_REQUEST = $this->post + $this->get;
   }

	public function run(array $targets = ['post', 'get', 'cookie']): void
	{
	   foreach ($targets as $t) {
	       if (method_exists($this, $t)) {
	           // Sanitiza y reemplaza directamente en el superglobal
	           $this->$t();
	       }
	   }
	}

	private function post(): void
	{
	   foreach ($_POST as $key => $value) {
	      $_POST[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	   }
	}

	private function get(): void
	{
	   foreach ($_GET as $key => $value) {
	      $_GET[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	   }
	}

	private function cookie(): void
	{
	   foreach ($_COOKIE as $key => $value) {
	      $_COOKIE[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	   }
	}

}
