<?php

/**
 * @name migrator.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.10
 * @description Para actualizar la base de datos sin intervension
**/

class Avatar {

	private $tsCore;

	public function __construct($tsCore) {
		$this->tsCore = $tsCore;
	}

	public function moveAvatars() {
		# Seleccionamos a todos los usuarios
		$users = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM @miembros"));
		foreach($users as $uid => $user) {
			$user_id = (int)$user['user_id'];
			$name_folder = "user$user_id";
			$avatar = TS_AVATAR . "$user_id.webp";
			$avatar_dest = TS_AVATAR . $name_folder . TS_PATH . "web.webp";
			if(!is_dir(TS_AVATAR . $name_folder)) {
				mkdir(TS_AVATAR . $name_folder, 0777, true);
			}
			if(copy($avatar, $avatar_dest)) {
				unlink($avatar);
			}
		}
	}

	public function createAvatarSocial($destinationPath, $sourcePath) {
		$isTempFile = false;
	   // Si la fuente es una URL, descargar la imagen primero
	   if (filter_var($sourcePath, FILTER_VALIDATE_URL)) {
	      $sourcePath = $this->downloadImage($sourcePath);
	      if (!$sourcePath) {
	         return false;
	      }
	      $isTempFile = true;
	   }
	   if (!file_exists($sourcePath)) return false;
	  	// Obtener la información de la imagen
	  	$imageInfo = getimagesize($sourcePath);
	  	if ($imageInfo === false) return false;


		$sourceImage = $this->tsCore->getFormatImage($imageInfo[2], $sourcePath, $imageInfo[2]);

	   // Manejar el error si la creación de la imagen falla
	   if ($sourceImage === false) return false;

	   // Calcular las nuevas dimensiones manteniendo la proporción
	   $newWidth = 160;
	   $newHeight = 160;
	   list($width, $height) = $imageInfo;
	   if ($width > $height) {
	      $newHeight = intval($height * $newWidth / $width);
	   } else {
	      $newWidth = intval($width * $newHeight / $height);
	   }
	   $scaledImage = imagecreatetruecolor($newWidth, $newHeight);
	   imagecopyresampled($scaledImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	   $action = (imagewebp($scaledImage, $destinationPath)) ? true : false;
	   // Liberar la memoria
	   imagedestroy($sourceImage);
	   imagedestroy($scaledImage);
	   // Eliminar el archivo temporal si existe
	   if ($isTempFile && file_exists($sourcePath)) {
	      unlink($sourcePath);
	   }
	   return $action;
	}

	private function downloadImage($url) {
	   $tempPath = tempnam(TS_UPLOADS, 'img_');
	   $imgContent = file_get_contents($url);
	   if ($imgContent !== false) {
	      file_put_contents($tempPath, $imgContent);
	      return $tempPath;
	   }
	   return false;
	}

}