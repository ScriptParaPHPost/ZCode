<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * @name Images.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Controlador para manipular imagenes
**/

class Images {

	/**
	 * Ruta del sitio.
	 * @var string
	 */
	protected $url;

	/**
	 * Ruta completa donde se guardan las imagenes.
	 * @var string
	 */
	protected $assets;


	/**
	 * Extensiones permitidas para las imágenes de portada.
	 * @var array
	 */
	public $en_arreglo = ['png', 'jpg', 'jpeg', 'webp', 'jfif'];

	/**
	 * Calidad de compresión para las imágenes de portada.
	 * @var int
	 */
	public $quality = 90;

	/**
	 * Longitud de los nombres generados para las imágenes de portada.
	 * @var int
	 */
	public $limitStr = 6;

	/**
	 * Tamaño máximo permitido para las imágenes de portada, en megabytes.
	 * @var int | Equivalente a 10MB = 10000000B
	 */
	public $megabytes = 10;
	protected $image_in_mb;

	/**
	 * Patrón para encontrar imágenes en el texto de la publicación.
	 * @var string
	 */
	protected $pattern = '/\[img(?:=|])([^]]+)\[\/img]|(?:\[img=)([^]]+)\]/i';

	/**
	  * Constructor de la clase. Inicializa la ruta de la imagen predeterminada.
	  */
	public function __construct() {
		global $tsCore;
		$this->url = $tsCore->settings['url'];
		$this->assets = $tsCore->settings['assets'];
		$this->image_in_mb = $this->megabytes * 1024 * 1024;
		$this->setFolderDestiny();
	}

	/**
    * Obtiene información sobre la imagen proporcionada.
    * 
    * @param string $image La ruta de la imagen.
    * @param string $type El tipo de información a obtener (extension, filename, basename).
    * @return mixed La información solicitada o false si no se reconoce el tipo.
   */
	protected function getInformationImage(string $image = '', string $type = '') {
		switch ($type) {
			case 'extension':
				return strtolower(pathinfo($image, PATHINFO_EXTENSION));
			break;
			case 'filename':
				return pathinfo($image, PATHINFO_FILENAME);
			break;
			case 'basename':
				return pathinfo($image, PATHINFO_BASENAME);
			break;
		}
	}

	/**
	 * Registra un mensaje de error.
	 * 
	 * @param string $message El mensaje de error a registrar.
	 * @return void
   */
	private function logError(string $message) {
    	error_log($message);
	}

	/**
    * Configura las carpetas de destino asegurándose de que existan.
    * Verifica y crea, si es necesario, los directorios utilizados para almacenar portadas y subidas.
    * 
    * @return void
   */
	private function setFolderDestiny() {
		foreach([TS_PORTADAS, TS_UPLOADS] as $verifyFolder) {
			if( !is_dir($verifyFolder) ) {
				mkdir($verifyFolder, 0777, true);
			}
		}
	}

	/**
    * Mueve la imagen de portada a la carpeta de destino y la guarda con compresión opcional.
    * 
    * @param array $files Los datos del archivo de imagen enviado.
    * @param string $image El nombre de la imagen.
    * @throws Exception Si ocurre un error al mover o guardar la imagen.
   */
	private function moveImage($files, string $imageToSave = null) {
		$image = TS_UPLOADS . $imageToSave;
		if(file_exists($image)) return $image;
		$imageData = file_get_contents($files["tmp_name"]);
		if ($imageData === false) {
			throw new Exception('Error al leer los datos de la imagen.');
		}
		$imageCreate = imagecreatefromstring($imageData);
		if ($imageCreate === false) {
			throw new Exception('No se pudo crear la imagen desde los datos.');
		}
		if (!move_uploaded_file($files["tmp_name"], $image)) {
			throw new Exception('No se pudo mover ni guardar la imagen.');
		}
		return $image;
	}

	/**
	  * Maneja la generación de nombres de archivos aleatorios para las imágenes de portada.
	  * @param string $filename El nombre de la imagen (opcional).
	  * @return string El nombre generado para la imagen de portada.
	  */
	private function setGenerateNewName(string $filename = ''): string {
		# MD5 lo usamos para la imagen temporal
		return md5($this->getInformationImage($filename, 'filename'));
	}

	/**
    * Obtiene la ruta completa basada en el tipo de ruta solicitada.
    * 
    * @param string $folder La carpeta relativa.
    * @param string $type El tipo de ruta ('link', 'temp', 'cover').
    * @return string La ruta completa.
   */
	private function getRoute(string $folder = '', string $type = 'link'): string {
		switch ($type) {
			case 'link':
				return $this->assets . '/images/' . $folder . '/';
			break;
			case 'temp':
				return TS_UPLOADS;
			break;
			case 'cover':
				return TS_PORTADAS;
			break;
		}
	}

	private function setNameCreate(string $image = '') {
		$createName = $this->setGenerateNewName($image);
		$getExtension = $this->getInformationImage($image, 'extension');
		return "$createName.$getExtension";
	}

	/**
    * Crea una imagen a partir de una URL y la guarda en una carpeta temporal.
    * 
    * @param string $image La URL de la imagen.
    * @param string $folder La carpeta de destino.
    * @return string|false El nombre del archivo creado o false si falla.
   */
	private function setCreateImage(string $image = '', string $folder = '', string $encoded = '') {
		// Generar un hash único basado en la URL de la imagen
		$name = $this->setNameCreate($image);
		$newfile = $this->getRoute($folder, 'temp') . $name;
		# Existe la portada!
		if(is_dir(TS_PORTADAS . $encoded) AND !empty($encoded)) return true;
		if(!file_exists($newfile)) {
			// Si el archivo no existe, intentar crear la imagen
			$imageContent = file_get_contents($image);
			// Verificar si se pudo obtener el contenido
			if ($imageContent === false) return false;
			// Guardar la imagen en el destino especificado
			file_put_contents($newfile, $imageContent);
		}
		// Devolver la ruta del archivo creado
		return $name;
	}

	/**
    * Redimensiona una imagen y la convierte al formato WebP en varios tamaños.
    * 
    * @param string $destDir El directorio de destino.
    * @param string $fileName El nombre del archivo de la imagen.
    * @return void
   */
   private function resizeAndConvertToWebP(string $destDir = '', string $fileName = '') {
   	global $tsCore;
		$sourceDir = TS_UPLOADS;
	   $quality = $this->quality;
	   // Define the sizes and their prefixes
	   $sizes = [
	      'sm' => ['width' => 120, 'height' => 90],
	      'md' => ['width' => 240, 'height' => 180],
	      'lg' => ['width' => 360, 'height' => 270],
	   ];
	   // Get the source image path
	   $sourcePath = $sourceDir . $fileName;
	   $imageInfo = getimagesize($sourcePath);
	   if (!$imageInfo) {
	      $this->logError("No se puede determinar el tipo de imagen de {$sourcePath}");
	   }
	   // Create an image resource from the source image
		$sourceImage = $tsCore->getFormatImage($imageInfo[2], $sourcePath, $imageInfo[2]);

      // Resize and save the images
		if(!is_dir($destDir)) mkdir($destDir, 0777, true);
	   foreach ($sizes as $prefix => $size) {
	   	$destinationPath = $destDir . TS_PATH . "image_$prefix.webp";
	   	if(file_exists($destinationPath)) return false;
      	$srcWidth = $imageInfo[0];
      	$srcHeight = $imageInfo[1];
      	$srcRatio = round($srcWidth / $srcHeight);
      	$destRatio = round($size['width'] / $size['height']);
	   	// Aspect
	   	if ($srcRatio > $destRatio) {
            // Source image is wider than destination aspect ratio
            $newHeight = $size['height'];
            $newWidth = round($srcWidth * ($size['height'] / $srcHeight));
            $cropX = round(($newWidth - $size['width']) / 2);
            $cropY = 0;
        	} else {
            // Source image is taller than destination aspect ratio
            $newWidth = $size['width'];
            $newHeight = round($srcHeight * ($size['width'] / $srcWidth));
            $cropX = 0;
            $cropY = round(($newHeight - $size['height']) / 2);
        	}
        	
        	$resizedImage = imagecreatetruecolor($size['width'], $size['height']);
        	imagecopyresampled($resizedImage, $sourceImage, -$cropX, -$cropY, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
        	if (!imagewebp($resizedImage, $destinationPath, $quality)) {
         	$this->logError("Error al guardar la imagen {$destinationPath}");
        	}
        	imagedestroy($resizedImage);
	   }
	   imagedestroy($sourceImage);
	   unlink($sourcePath);
	}

	/**
    * Transforma una imagen redimensionándola y convirtiéndola al formato WebP.
    * 
    * @param int $pid El ID del post.
    * @param string $image El nombre de la imagen.
    * @return array Un arreglo con las URLs de las imágenes redimensionadas.
   */
   private function transformImage(int $pid = 0, string $image = ''): array {
		$encoded = $this->setEncodeNameFolder($pid);
		$encodedFolder = TS_PORTADAS . $encoded;
		// Si no existe la carpeta con las imagenes, la generamos
		if(!is_dir($encodedFolder) OR $_GET["action"] === 'editar') {
			$this->resizeAndConvertToWebP($encodedFolder, $image);
		}
		// Obtenemos la url completa de las imagenes de la carpeta existente
		$images = $this->getPortadaLink($encoded);
		return $images;
	}

	/**
	 * Generamos la url para cada imagen
	 * 
	 * @param string $encode Nombre de la carpeta codificada
	 * @return array El arreglo con cada imagen de distinto tamaños
	*/
	private function getPortadaLink(string $encode = '') {
		$sizeOfCovers = ['sm', 'md', 'lg'];
		foreach($sizeOfCovers as $img) $images[$img] = $this->getRoute("portadas/$encode", 'link') . "image_$img.webp" . uniqid('?v1.2');
		return $images;
	}

	/**
	 * En caso de no poder obtener las imagenes, mostraremos estas.
	 * 
	 * @return array Arreglo de URLs de imágenes predeterminadas.
   */
	private function defaultImages() {
		return [
			'sm' => $this->assets . '/images/favicon/logo-64.webp',
			'md' => $this->assets . '/images/favicon/logo-128.webp',
			'lg' => $this->assets . '/images/favicon/logo-512.webp'
		];
	}
	/**
	 * Verifica si el tamaño de la imagen excede el límite permitido.
	 * 
	 * @param string $url La URL de la imagen o los datos del archivo.
	 * @param string $type El tipo de verificación ('local' o 'url').
	 * @return mixed true si excede el límite, o un mensaje de error.
   */
	private function isImageSizeExceedsLimit($url, string $type = 'local') { 
		// Verificar tamaño de la imagen | 2 MB
	   $this->megabytes = 2;
	   $message = 'La imagen debe pesar por mucho '.$this->megabytes.'MB, el tama&ntilde;o de tu archivo es mayor que el permitido.';
    	if($type === 'url') {
    		// Obtiene los encabezados de la URL
		   $headers = get_headers($url, 1);
		   if (isset($headers['Content-Length'])) {
		      $size = $headers['Content-Length'];
		      return ($size > $limit) ? true : $message;
		   } else return $message;
		} else if($type === 'local') {
			if ($portada["size"] > $this->image_in_mb) {
	         return $message;
	      }
		}
	}

	/**
	 * Actualiza la tabla de la base de datos con el nombre codificado de la carpeta.
	 * 
	 * @param int $pid El ID del post.
	 * @param string $encoded El nombre codificado de la carpeta.
	 * @return void
   */
	private function updateTable(int $pid = 0, string $encoded = '') {
		db_exec([__FILE__, __LINE__], 'query', "UPDATE @posts SET `post_portada` = '$encoded' WHERE `post_id` = $pid");
	}

	private function createImageOfLink($pid, $image, $folder, $encoded = '') {
		$returnImages = $this->defaultImages();
	   $extension = $this->getInformationImage($image, 'extension');

		if(in_array($extension, $this->en_arreglo)) {
			$imageCreated = $this->setCreateImage($image, $folder, $encoded);
		   if(!empty($imageCreated)) {
		   	$returnImages = $this->transformImage($pid, $imageCreated);
		   	$this->updateTable($pid, $encoded);
		   } else $returnImages = $this->defaultImages();
		}
		return $returnImages;
	}

	private function deleteFolder($pid) {
		$folderPath = TS_PORTADAS . $pid;
	   $files = scandir($folderPath);
	   foreach ($files as $file) {
        	if ($file === '.' && $file === '..') continue;
         $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
         if (is_file($filePath)) {
            unlink($filePath);
         }
    	}
    	rmdir($folderPath);
	}

	private function paramFiles($files) {
		if(is_array($files)) {
			// Generar un hash único basado en la URL de la imagen
			$imageName = $this->setNameCreate($files["name"]);
	    	$imageCreated = $this->moveImage($files, $imageName);
	    	$check = getimagesize($imageCreated);
	      if($check === false) {
	         return 'El archivo que vas a enviar no es una imagen válida, verifica la imagen del post ' . $check["mime"];
	      }
	      $this->isImageSizeExceedsLimit($files["size"], 'local');
	      # Añadimos a la variable la ruta de la imagen
	     	$portada = pathinfo($imageCreated, PATHINFO_BASENAME);
		} else {
			$portada = $this->setCreateImage($files, 'temp');
		}

		return $portada;
	}

	/**
	 * Genera un nombre codificado para la carpeta de imágenes basado en el ID del post.
	 * 
	 * @param int $folder_id El ID de la carpeta.
	 * @return string El nombre codificado de la carpeta.
   */
	public function setEncodeNameFolder(int $folder_id = 0) {
		return substr(md5("P$folder_id"), 0, $this->limitStr);
	}

	/**
    * Obtiene la imagen de portada para un post.
    * 
    * @param int $pid ID del post.
    * @param string|null $image URL de la imagen original.
    * @param string|null $content Si no tiene $image obtenemos imagen del contenido.
    * @return array Un arreglo con las URLs de las imágenes redimensionadas.
   */
   public function setImageCover(int $pid = 0, string $image = null, string $content = ''): array {
   	$encoded = $this->setEncodeNameFolder($pid);
   	$returnImages = $this->defaultImages();
   	#
   	// Si $image está vacío y no es una URL válida
   	if(empty($image) AND !filter_var($image, FILTER_VALIDATE_URL)) {
   		$returnImages = $this->getImageOfContent($content, $pid, $encoded);
        	$this->updateTable($pid, $encoded);
    	} else {
	   	// Si la longitud de $image es de 6 caracteres
	   	if(strlen($image) === 6) {
	   		if(!is_dir($this->getRoute('', 'cover') . $encoded)) {
	   			return $returnImages;
	   		}
	   		$returnImages = $this->getPortadaLink($encoded);
	   	}
	   	if(strlen($image) > 6 AND strlen($image) < 40) {
	   		$this->createImage($pid, $image);
        		$this->updateTable($pid, $encoded);
	   	}
	   	if(filter_var($image, FILTER_VALIDATE_URL) && strlen($image) !== 6) {
	   		$this->createImageOfLink($pid, $image, 'portadas', $encoded);
		   }
		}
		return $returnImages;
	}

	/**
    * Crea la imagen de la portada
    * 
    * @param int $pid ID del post.
    * @param string|null $image URL de la imagen original.
    * @return array Un arreglo con las URLs de las imágenes redimensionadas.
   */
	public function createImage(int $pid = 0, string $image = null) {
		$encoded = $this->setEncodeNameFolder($pid);
   	$returnImages = $this->defaultImages();
   	#
   	if(file_exists(TS_UPLOADS . $image)) {
			$encodedFolder = TS_PORTADAS . $encoded;
   		$this->resizeAndConvertToWebP($encodedFolder, $image);
			// Obtenemos la url completa de las imagenes de la carpeta existente
			$returnImages = $this->getPortadaLink($encoded);
   	}
		return $returnImages;
	}

   /**
    * Extrae todas las imágenes de un texto.
    * 
    * @param string|null $bodyContent El contenido del post.
    * @return array Un arreglo de URLs de imágenes extraídas o la imagen predeterminada si no se encuentra ninguna.
   */
   public function getImageOfContent(string $bodyContent = null, int $pid = 0, string $encoded = '') {
   	// Si no hay datos
     	if (empty($bodyContent)) {
         return $this->defaultImages();
     	}
    	// Extraer las imágenes
    	preg_match_all($this->pattern, $bodyContent, $matches);
    	// Combinar los resultados de ambas variantes de BBCode [img] y [img=]
    	$images = array_filter(array_merge($matches[1], $matches[2]));
    	// Si no se encontraron imágenes, devolver la imagen por defecto
    	if (empty($images)) {
    	   return $this->defaultImages();
    	}
    	// Devolver todas las imágenes encontradas
    	$images = $images[1] ?? $images[0];
		$imageCreated = $this->setCreateImage($images, 'portadas', $encoded);
		$images = !empty($imageCreated) ? $this->transformImage($pid, $imageCreated) : $this->defaultImages();
    	return $images;
   }
	
   /**
	 * Obtiene la imagen de entrada validando si es una URL o un archivo subido.
	 * 
	 * @return string URL o ruta de la imagen.
   */
	public function getImageOfInput() {
		$__VARIABLE = 'portada';
		$portada = '';
		if(isset($_POST[$__VARIABLE]) AND filter_var($_POST[$__VARIABLE], FILTER_VALIDATE_URL)) {
			$portada = $this->setCreateImage($_POST[$__VARIABLE], 'temp');
		} elseif (isset($_FILES[$__VARIABLE]) AND is_array($_FILES[$__VARIABLE])) {
			$portada = $this->paramFiles($_FILES[$__VARIABLE]);
		}
		return $portada;
	}

	# Actualizamos la portada
	public function updateImagePost(bool $update = true, int $pid = 0) {
		$image = isset($_FILES['portada']) ? $_FILES['portada'] : $_POST['portada'];
		$pid = (int)$_GET["pid"] ?? $pid;
	   $encoded = $this->setEncodeNameFolder($pid);
	   // Verificar si la carpeta existe
	   if (is_dir(TS_PORTADAS . $encoded)) {
	      $this->deleteFolder($encoded);
	   }
		if(filter_var($image, FILTER_VALIDATE_URL) && strlen($image) !== 6) {
			$this->createImageOfLink($pid, $image, 'portadas', $encoded);
		} else if(is_array($image)) {
			$image = pathinfo($this->paramFiles($image), PATHINFO_BASENAME);
			$this->transformImage($pid, $image);
		}
	   if($update) $this->updateTable($pid, $encoded);
	}

}