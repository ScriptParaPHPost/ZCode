<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.protected_mail.php
 * Type:     modifier
 * Name:     protected_mail
 * -------------------------------------------------------------
 * Este modificador convierte direcciones de correo electrónico en un formato protegido, envolviéndolas en un elemento span
 * con atributos de datos personalizados para evitar que los bots de spam las capturen fácilmente.
 *
 * @param array $params Un array asociativo con los elementos 'key' y 'public':
 *                      - 'key' (string): Una clave única asociada con el correo electrónico.
 *                      - 'public' (string): La dirección de correo electrónico pública.
 * @return string Elemento span en HTML con la información del correo electrónico protegido.
 * @throws InvalidArgumentException si los parámetros requeridos faltan o están vacíos.
*/
function smarty_modifier_protected_mail($params) { 
   // Aseguramos que 'key' & 'public' no esten vacios
   if (empty($params['key']) || empty($params['public'])) {
      throw new InvalidArgumentException('Los parametros "key" y "public" no pueden estar vacios.');
   }

   // Prevenimos ataques XSS
   $key = htmlspecialchars((string) $params['key'], ENT_QUOTES, 'UTF-8');
   $public = htmlspecialchars((string) $params['public'], ENT_QUOTES, 'UTF-8');
	
	$keyAttr = "data-key=\"$key\"";
	$publicAttr = "data-public=\"$public\"";

	$response = "<span $keyAttr $publicAttr id=\"protected_mail\">[EMAIL_PROTECTED]</span>";

	return $response;
}