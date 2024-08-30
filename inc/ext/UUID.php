<?php

class UUID {

	protected $namespace = "73216b04-eb2d-470b-8a91-fc9ea6d4039e";

 	/**
    * Genera un UUID de versión 3 basado en un namespace y un nombre.
    *
    * @param string $namespace El namespace en formato UUID.
    * @param string $name El nombre a ser usado para generar el UUID.
    * @return string|false El UUID generado o false si el namespace no es válido.
   */
   public function v3($name) {
      if (!$this->is_valid($this->namespace)) return false;
      $nhex = str_replace(['-', '{', '}'], '', $this->namespace);
      $nstr = hex2bin($nhex);
      $hash = md5($nstr . $name);
      return sprintf('%08s-%04s-%04x-%04x-%12s',
         substr($hash, 0, 8),
         substr($hash, 8, 4),
         (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
         (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
         substr($hash, 20, 12)
      );
   }

   /**
    * Genera un UUID de versión 4 aleatorio.
    *
    * @return string El UUID generado.
   */
   public function v4() {
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
         random_int(0, 0xffff), random_int(0, 0xffff),
         random_int(0, 0xffff),
         random_int(0, 0x0fff) | 0x4000,
         random_int(0, 0x3fff) | 0x8000,
         random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
      );
   }

   /**
    * Genera un UUID de versión 5 basado en un namespace y un nombre.
    *
    * @param string $namespace El namespace en formato UUID.
    * @param string $name El nombre a ser usado para generar el UUID.
    * @return string|false El UUID generado o false si el namespace no es válido.
   */
   public function v5($name) {
      if (!$this->is_valid($this->namespace)) return false;
      $nhex = str_replace(['-', '{', '}'], '', $this->namespace);
      $nstr = hex2bin($nhex);
      $hash = sha1($nstr . $name);
      return sprintf('%08s-%04s-%04x-%04x-%12s',
         substr($hash, 0, 8),
         substr($hash, 8, 4),
         (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
         (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
         substr($hash, 20, 12)
      );
   }

   /**
    * Verifica si un UUID es válido.
    *
    * @param string $uuid El UUID a ser validado.
    * @return bool true si el UUID es válido, false en caso contrario.
   */
   public function is_valid($uuid) {
      return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
   }
}
