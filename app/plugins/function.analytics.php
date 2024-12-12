<?php

/**
 * Función para insertar el código de Google Analytics en un template Smarty
 *
 * @param array $params Parámetros pasados a la función (se espera 'id')
 * @param object $smarty Objeto Smarty (referencia opcional)
 * @return string Código HTML para Google Analytics o una cadena vacía si el ID no es válido
 */
function smarty_function_analytics(array $params, &$smarty) {
    
   /**
    * Valida si un ID de Google Analytics es válido
    *
    * @param string $id ID de Google Analytics a validar
    * @return bool Verdadero si el ID es válido, falso en caso contrario
    */
   function validarIDGoogleAnalytics($id) {
      // Expresiones regulares para los formatos de GA3 (UA) y GA4 (G-)
      $regex_ua = '/^UA-\d{7,9}-\d{1,2}$/';  // Formato Universal Analytics
      $regex_ga4 = '/^G-[A-Za-z0-9]{10}$/';  // Formato Google Analytics 4

      return preg_match($regex_ua, $id) || preg_match($regex_ga4, $id);
   }

   // Validar la existencia del parámetro 'id' y codificarlo de forma segura
   if (!isset($params['id']) || !is_string($params['id'])) {
      return ''; // Si no se pasa el parámetro, devolvemos una cadena vacía
   }

   $idGoogle = htmlspecialchars(trim($params['id']), ENT_QUOTES, 'UTF-8');

   // Validar el formato del ID
   if (!validarIDGoogleAnalytics($idGoogle)) {
      return ''; // Si el ID no es válido, no generamos el código
   }

   // Generar el código de Google Analytics
   $html = <<<HTML
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=$idGoogle"></script>
	<script>
	   window.dataLayer = window.dataLayer || [];
	   const gtag = () => dataLayer.push(arguments);
	   gtag('js', new Date());
	   gtag('config', '$idGoogle');
	</script>
	HTML;

   return $html; // Retornar el código generado
}
