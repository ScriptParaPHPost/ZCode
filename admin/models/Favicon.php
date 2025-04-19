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

namespace admin\models;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

class Favicon {

	private $sizes = [512, 256, 128, 64, 32, 16];

	private $folder = 'favicon';

	private $extension = 'webp';

	private function getLinkFavicon() {
		global $tsCore;
		return $tsCore->setRoutes('assets', $this->folder) . '/';
	}

	private function getRootFavicon() {
		return TS_IMAGES . $this->folder . DIRECTORY_SEPARATOR;
	}

	public function getAllFavicons() {
		global $tsCore;
		$root_favicon = $this->getRootFavicon();
		$favicons = scandir($root_favicon);
    	$favdata = [];
		foreach($favicons as $fv => $icon) {
			if(in_array($icon, ['.', '..'])) continue;
			$imagen = $root_favicon . $icon;
			$size = getimagesize($imagen)[0];
			$favdata[] = [
				'px' => "32px",
				'size' => $size,
				'weight' => $tsCore->formatBytes(filesize($imagen)),
				'name' => ucfirst(str_replace('-',' ',pathinfo($imagen, PATHINFO_FILENAME))),
				'ext' => pathinfo($imagen, PATHINFO_EXTENSION),
				'link' => $this->getLinkFavicon() . $icon
			];
		}
		usort($favdata, function($a, $b) {
        return $a['size'] - $b['size'];
    	});
		return $favdata;
	}

	private function createFavicon($image, $size = '') {
	   global $tsCore;
	   // Si el tamaño está vacío, usa el tamaño original de la imagen
	   if (empty($size)) {
	      $newSize = (imagesx($image) > 1024 ? 1024 : imagesx($image));
	      $newName = $tsCore->setSEO($tsCore->settings['titulo']);
	   } else {
	      $newSize = $size;
	      $newName = "logo-$size";
	   }
	   // Redimensionar la imagen
	   $resized = imagescale($image, $newSize, $newSize);
	   $output_filename = $this->getRootFavicon() . "$newName.{$this->extension}";
	   // Guardar la imagen redimensionada
	   imagewebp($resized, $output_filename);
	   // Liberar la memoria
	   imagedestroy($resized);
	}

	private function resizeImage($filename) {
	   $image_info = getimagesize($filename);
	   $mime_type = $image_info['mime'];

	  	$image = match ($mime_type) {
		   'image/jpeg', 'image/jpg', 'image/jfif' => imagecreatefromjpeg($filename),
		   'image/png' => imagecreatefrompng($filename),
		   'image/gif' => imagecreatefromgif($filename),
		   'image/webp' => imagecreatefromwebp($filename),
		   default => die('Formato de imagen no soportado.'),
		};
	   $this->createFavicon($image);
	   foreach($this->sizes as $f => $size) {
	   	$this->createFavicon($image, $size);
	   }
	   imagedestroy($image);
	   return true;
	}

	public function uploadFavicon() {
		if(isset($_FILES['favicon'])) {
			$file_favicon_upload = $_FILES['favicon']['tmp_name'];
			$original_name = pathinfo($file_favicon_upload, PATHINFO_FILENAME) . ".{$this->extension}";
			$original_file = $this->getRootFavicon() . $original_name;
			$uploadOk = 1;
			// Verificar si el archivo es una imagen real
    		$check = getimagesize($file_favicon_upload);
    		if($check === false) {
       		$uploadOk = 0;
        		$msg = "0: El archivo no es una imagen.";
    		}
    		$size = getimagesize($file_favicon_upload)[0];
    		if($size <= 1024) {
       		$uploadOk = 0;
        		$msg = "0: El archivo no es una imagen.";
    		}
    		if($uploadOk === 1) {
	    		// Subir el archivo original
			   if (!move_uploaded_file($file_favicon_upload, $original_file)) {
			      $msg = "0; Lo siento, hubo un error al subir tu archivo.";
			   }
	    		if($this->resizeImage($original_file)) {
	    			unlink($original_file);
	    			$msg = '1: Favicon generados correctamente.';
	    		}
	    	}
	    	return $msg;

		} else return '0: No se ha enviado ninguna imagen.';
	}

}