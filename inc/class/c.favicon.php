<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de los favicon
 *
 * @name    c.favicon.php
 * @author  Miguel92
 */

class tsFavicon {

	private $sizes = [512, 256, 128, 64, 32, 16];

	private $folder = 'favicon';

	private $extension = 'webp';

	private function getLinkFavicon() {
		global $tsCore;
		return $tsCore->settings[$this->folder] . '/';
	}

	private function getRootFavicon() {
		return TS_IMAGES . $this->folder . TS_PATH;
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
	      $newSize = imagesx($image);
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

	  	switch ($mime_type) {
	      case 'image/jpeg':
	      case 'image/jpg':
        	case 'image/jfif':
	         $image = imagecreatefromjpeg($filename);
	      break;
	      case 'image/png':
	         $image = imagecreatefrompng($filename);
	      break;
	      case 'image/gif':
	         $image = imagecreatefromgif($filename);
	      break;
	      case 'image/webp':
            $image = imagecreatefromwebp($filename);
         break;
	      default:
	         die('Formato de imagen no soportado.');
	   }
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
    		if($size < 1024) {
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