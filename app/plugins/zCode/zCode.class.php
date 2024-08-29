<?php

/**
 * Autor: Miguel92
 * Fecha: Jul 03, 2024 
 * Nombre: SmartyZCode
 * Tipo: clase 
 * Version: 1.8
*/

class SmartyZCode {

	public $version;

	/**
    * Variable para almacenar datos necesarios
    * @var array
   */
	public $nucleo;

	 /**
    * Para almacenar las rutas de acceso a carpeta
    * @var array
   */
	private $allRoutes = [];

	/**
    * Variables de permisos
    * @var array
   */
	private $allow_extension = ['ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'avif'];

	/**
    * Variables para determinar el tipo
    * @var array
   */
	private $images_types = [
  	   'ico' => 'x-icon',
  	   'png' => 'png',
  	   'jpg' => 'jpeg',
  	   'jpeg' => 'jpeg',
  	   'webp' => 'webp',
  	   'svg' => 'svg+xml'
  	];

   /**
    * Acceso a carpeta de los recursos a usar
    * @var array
   */
  	private $resources = ['root', 'css', 'images', 'js'];

  	/**
    * Acceder a variables o a clases
    * @var array
   */
  	private $access = ['action', 'tsAction', 'tsFoto', 'tsMPs', 'tsNots', 'tsPage', 'tsPages', 'tsPerfil', 'tsPost'];

  	private $page_wysibb = ['agregar', 'posts', 'fotos', 'mensajes'];

  	/**
  	 * @access public
  	 * 
  	*/
  	public function __construct($smarty) {
  		global $tsCore, $tsUser;
  		
  		foreach($this->access as $class) $this->nucleo[$class] = $GLOBALS[$class] ?? null;

  		$this->allRoutes = $this->getRoutesOfDirectories($tsCore->settings, $smarty->template_dir);
  	}

  	/**
    * Obtiene las rutas de los directorios
    * 
    * @param array $themeRoute Rutas del tema
    * @param array $dirs Directorios
    * @return array Rutas configuradas
   */
  	private function getRoutesOfDirectories(array $themeRoute = [], array $dirs = []):array {
  		$setRoutes = ['links' => [], 'directories' => []];
  		foreach (['tema', 'assets'] as $link) {
         $theme = ($link === 'tema') ? $themeRoute[$link]['t_url'] : $themeRoute[$link];
         foreach ($this->resources as $source) {
            $isSource = ($source === 'root');
            $setRoutes['links'][$link][$source] = $theme . ($isSource ? '' : "/$source");
            $setRoutes['directories'][$link][$source] = $dirs[$link] . ($isSource ? '' : $source);
         }
      }
  		return $setRoutes;
  	}

  	/**
  	 * @access private
  	 * Función para recorres las carpetas
  	 * @param $filename Archivo a comprobar su existencia.
  	 * @return string Url del archivo existente.
  	*/
  	private function setFileExistsInRoute(string $filename = '') {
  		foreach($this->allRoutes['directories'] as $routeType => $folders) {
  			foreach($folders as $folderType => $folderPath) {
  				$filePath = $folderPath . ($folderType === 'root' ? '' : TS_PATH) . $filename;
  				if( file_exists($filePath) ) {
  					return $this->allRoutes['links'][$routeType][$folderType] . '/' . $filename;
  				}
  			}
  		}
  		return false;
  	}

  	private function getCached() {
  		return uniqid("?v{$this->version}");
  	}

  	/**
	 * @access private
    * Genera una etiqueta HTML para un recurso (CSS o JS) desde JSDelivr.
    * 
    * @param string $htmltag La ruta del recurso
    * @return string La etiqueta HTML generada. : ?string
   */
	private function generateHtmlTag(string $htmltag = '') {
		$extension = pathinfo($htmltag, PATHINFO_EXTENSION);
		$withoutCached = false;
		$fileCache = $htmltag . ($withoutCached ? '' : $this->getCached());
		switch ($extension) {
			case 'css':
				return "<link rel=\"stylesheet\" href=\"$fileCache\" type=\"text/css\"/>\n";
			break;
			case 'js':
				return "<script src=\"$fileCache\"></script>\n";
			break;
		}
	}

  	/**
	 * Verifica y retorna permisos específicos basados en las opciones dadas.
	 * 
	 * @param string $choice Opción principal de permiso.
	 * @param string $subchoice Sub-opción de permiso.
	 * @return bool Verdadero si el permiso se cumple, falso en caso contrario.
	*/
	public function setPermisson(string $choice = '', string $subchoice = ''): bool {
	   global $tsCore;
	   $permisos = [
	      'live' => (int)$tsCore->settings['c_allow_live'] === 1,
	      'notLive' => !in_array($this->nucleo['tsPage'], ['login', 'registro']),
	      'admin' => $this->nucleo['tsPage'] === $choice && $this->nucleo['action'] === $subchoice,
	      'php_files' => $this->nucleo['tsPage'] === "php_files/p.$subchoice.home"
	   ];
	   return $permisos[$choice] ?? false;
	}

  	/**
	 * Verifica permisos especiales del usuario.
	 * 
	 * @access private
	 * @return bool Verdadero si el usuario tiene permisos especiales, falso en caso contrario.
	*/
	private function setPermissonEspecial(): bool {
		global $tsUser;
	   $permisos = ['moacp', 'most', 'moayca', 'mosu', 'modu', 'moep', 'moop', 'moedcopo', 'moaydcp', 'moecp'];
	   # Es administrador!
	   if ($tsUser->is_admod) return true;
	   # Tiene permisos
	   foreach ($permisos as $permiso) {
	      if ($tsUser->permisos[$permiso] ?? false) return true;
	   }
	   # Si no es administrador y no tiene permisos!
	   return false;
	}

	/**
	 * Elimina un elemento de un array si existe.
	 * 
	 * @param array $arrayToSearch El array en el cual buscar el elemento.
	 * @param string $itemToFind El elemento a buscar y eliminar.
	 * @return array El array modificado.
	*/
  	private function deleteItemOf(array $arrayToSeach = [], string $itemToFind = '') {		
		$posicion = array_search($itemToFind, $arrayToSeach);
		if ($posicion !== false) {
			unset($arrayToSeach[$posicion]);
		}
		return array_values($arrayToSeach);
  	}

	/**
	 * Genera una etiqueta HTML para el archivo especificado si existe.
	 *
	 * @param string $string Nombre del archivo.
	 * @return ?string La etiqueta HTML generada o null si el archivo no existe.
	*/
	private function setBuildTag(string $string = ''): ?string {
	   $filename = $this->setFileExistsInRoute($string);
	   return !empty($filename) ? $this->generateHtmlTag($filename) : null;
	}

  	/**
	 * Asigna variables a un array de claves basado en las propiedades del objeto.
	 *
	 * @param array &$claves Array de claves al cual se añadirán las variables.
	 * @return void
	*/
	private function getVariables(array &$claves): void {
  		global $tsCore;
  		if(isset($this->nucleo['tsPost']['post_id'])) {
			$claves['postid'] = (int)$this->nucleo['tsPost']['post_id'];
			$claves['autor'] = (int)$this->nucleo['tsPages']['autor'];
		}
		if(isset($this->nucleo['tsFoto']['foto']['foto_id'])) {
			$claves['fotoid'] = (int)$this->nucleo['tsFoto']['foto']['foto_id'];
		}
		if($this->nucleo['tsPage'] === 'access' AND $_GET['action'] === 'registro') {
			$claves['pkey'] = $tsCore->settings['pkey'];
		}
  	}

  	/**
	 * Verifica si una cadena es numérica y devuelve su valor entero o la cadena entre comillas.
	 *
	 * @param string $string La cadena a verificar.
	 * @return mixed El valor entero si es numérico, de lo contrario la cadena entre comillas.
	 */
	private function isNumeric(string $string = '') {
	   return is_numeric($string) ? (int) $string : "'$string'";
	}

	/**
	 * Crea una cadena JavaScript que representa un objeto global basado en un array de claves.
	 *
	 * @param array $claves Array de claves para generar el objeto JavaScript.
	 * @return string La cadena JavaScript del objeto global.
	*/
	private function createObject(array $claves = [], $data = null): string {
  		global $tsUser, $tsCore;
	   include TS_ZCODE . 'datos.php';

	   $quitar = explode(';', $data);

  		$jsObjectString = "const ZCodeApp = {\n";
  		
	   foreach ($claves as $key => $value) {
	      if($value === NULL) continue;
	      if (is_array($value)) {
	         $jsObjectString .= "\t$key: {\n";
	         foreach($value as $subKey => $subValue) {
	         	if($subValue === NULL) continue;
	            $jsObjectString .= "\t\t$subKey: {$this->isNumeric($subValue)},\n";
	         }
	         $jsObjectString .= "\t},\n";
	      } else {
	         $jsObjectString .= "\t$key: {$this->isNumeric($value)},\n";
	      }
	   }	

	   if($quitar[0] !== 'colores') $jsObjectString .= "\tcolores: ". json_encode($tsColores, JSON_FORCE_OBJECT) .",\n";
	   if($quitar[1] !== 'themes') $jsObjectString .= "\tthemes: ". json_encode($tsSchemes) ."\n";
	   $jsObjectString .= "};";
	   
	   if($tsUser->uid != 0 AND $this->nucleo['tsPage'] == 'cuenta') {
			// Avatar por defecto en caso de no exister el avatar del usuario
			$avatar = $tsCore->getAvatar($tsUser->uid, 'use');
			$portada = '';//$this->nucleo['tsPerfil']['user_portada'];
			$portada = isset($portada) ? "\n\tavatar.cover = '$portada';" : '';
			$jsObjectString .= <<< LINEA
			\ndocument.addEventListener("DOMContentLoaded", function() {
				avatar.uid = {$tsUser->uid};
				avatar.current = '$avatar';$portada
			});
			LINEA;
		}
		
	   return trim($jsObjectString);
  	}

  	/**
	 * Añade archivos JavaScript a un array principal dependiendo de la página actual.
	 *
	 * @param array &$jsMain Array de archivos JavaScript principal al cual se añadirán los nuevos archivos.
	 * @param mixed $page Nombre de la página o array de nombres de páginas en las cuales se deben añadir los archivos.
	 * @param mixed $file Nombre del archivo JavaScript o array de nombres de archivos que se deben añadir.
	 *
	 * @return void
	*/
  	private function appendJS(array &$jsMain, $page, $file) {
	   if ((is_array($page) && in_array($this->nucleo['tsPage'], $page)) || $this->nucleo['tsPage'] === $page || $this->nucleo['action'] === $page) {
	      $jsMain = array_merge($jsMain, (array) $file);
	   }
	}

	/**
	 * TODAS LAS FUNCIONES PÚBLICAS DESDE AQUÍ...
	*/

	/**
	 * Busca y agrega las hojas de estilos.
	 * 
	 * @access public
	 * @param array|string $styles Las hojas de estilo a agregar.
	 * @return string Etiquetas HTML generadas para las hojas de estilo.
	*/
  	public function setStylesheets($styles): string {
	  	$tagsCreated = '';
  		# Si solamente es un solo archivo!
  		if(!is_array($styles)) return $this->setBuildTag($styles);
  		# En caso de ser array, continuaremos
  		$cssPage = $this->nucleo['tsPage'] . '.css';
  		$newStyles = [...$styles, $cssPage];
  		if (in_array($this->nucleo['tsPage'], ['admin', 'moderacion'])) {
  			# Quitamos 
  			$newStyles = $this->deleteItemOf($newStyles, 'moderacion.css');
  		}
  		# Añadimos el estilo del editor
	  	if(in_array($this->nucleo['tsPage'], $this->page_wysibb)) {
	  		$newStyles = [...$newStyles, "wysibb.css"];
	  	}
  		// Añadimos si esta en Admin > rangos
	  	if($this->setPermisson('admin', 'rangos')) {
	  		$newStyles = [...$newStyles, "colorpicker.css"];
	  	}
	  	if(in_array($this->nucleo['tsPage'], ['cuenta', 'login', 'registro'])) {
	  		$newStyles = [...$newStyles, "buttons-social.css"];
	  	}
	  	if($this->nucleo['tsPage'] === 'portal') {
		  	$newStyles = [...$newStyles, "perfil.css"];
	  	}
	  	foreach($newStyles as $style) {
	  		$tagsCreated .= $this->setBuildTag($style);
	  	}
	  	return trim($tagsCreated);
  	}

  	public function setStyleCustomized() {
  		global $tsCore, $tsUser;
  		if($tsUser->is_member > 0) {
	  		include TS_PLUGINS . 'zCode' . TS_PATH . 'zCode.customizer.php';
	  		$colores = $tsCore->setColorCustomize();
	  		return (safe_count($colores) <= 1) ? '' : generateThemeColors('customizer', $colores[0], $colores[1]);
  		} return '';
  	}

  	/**
  	 * @access public
  	 * Buscamos y agregamos los scripts
  	*/
  	public function setScripts($scripts) {
  		global $tsUser;
	  	$tagsCreated = '';
  		# Si solamente es un solo archivo!
  		if(!is_array($scripts)) return $this->setBuildTag($scripts);
  		$jsMain = ['zCode.js', 'plugins.js', ...$scripts];
  		# Añadimos el editor
	  	$this->appendJS($jsMain, $this->page_wysibb, 'wysibb.js');
	  	# Añadimos complementos a cuenta, comunidades...
	  	$this->appendJS($jsMain, 'home', "afiliado.js");
	  	$this->appendJS($jsMain, ['cuenta', 'comunidades'], ["croppr.js", "avatar.js"]);
	  	# Solo para registro
	  	if($this->nucleo['action'] === 'registro' AND $this->nucleo["tsPage"] !== 'admin') {
		  	$jsMain = [...$jsMain, "reCaptcha.js"];
	  	}
	  	# Añadimos complementos a cuenta, comunidades...
	  	if($this->nucleo['tsPage'] === 'admin') {
	  		if(empty($this->nucleo['action'])) {
		  		$jsMain = [...$jsMain, "emoji-toolkit.js", "timeago.min.js", "timeago.es.js"];
			}
		}
		# Post privado!
		if($this->nucleo['tsPost'][0] === 'privado') {
	  		$jsMain = [...$jsMain, "login.js"];
		}
		// Añadimos el archivo 'moderacion.js'
	  	if($this->setPermissonEspecial() AND $this->nucleo['tsPage'] != 'admin') {
	  		$jsMain = [...$jsMain, "moderacion.js"];
	  	}
	  	if($tsUser->is_member) {
	  		// Añadimos el archivo 'live.js'
		  	if($this->setPermisson('live') && $this->setPermisson('notLive')) $jsMain = [...$jsMain, "live.js"];
	  	}
		
	  	if($this->nucleo['tsPage'] === 'portal') {
		  	$jsMain = [...$jsMain, "perfil.js"];
	  	}
  		$jsPageType = ($this->nucleo['tsPage'] === 'access') ? 'action' : 'tsPage';
  		$jsPage = $this->nucleo[$jsPageType] . '.js';
	  	$jsMain = [...$jsMain, $jsPage];
	
	  	foreach($jsMain as $script) $tagsCreated .= $this->setBuildTag($script);
	  	return trim($tagsCreated);
  	}

  	/**
  	 * @access public
  	 * Añadimos variables globales antes de los javascripts
  	*/
  	public function setScriptLineGlobal($data = null) {
  		global $tsCore, $tsUser;
  		$claves = [];
  		if($tsUser->uid != 0) {
  			$claves['user_key'] = $tsUser->uid;
  		}
		$this->getVariables($claves);
		// Siempre
		$claves['images'] = [
			'assets' => $tsCore->settings['assets'] . '/images',
			'tema' => $tsCore->settings['images']
		];
		$claves['theme'] = $tsCore->settings['tema']['t_url'];
		$others = ['url', 'assets', 'domain', 'titulo', 'slogan', 'version'];
		foreach ($others as $key => $other) {
			$claves[$other] = $tsCore->settings[$other];
		}
		ksort($claves);
		return "<script>\n{$this->createObject($claves, $data)}\n</script>";
  	}

}