<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.1.15
**/

class tsCore {


	public $settings;	// CONFIGURACIONES DEL SITIO

	/**
	 * Determina si la conexi�n es segura (HTTPS) y devuelve el esquema de URL correspondiente.
	 *
	 * @return string El esquema de URL (http:// o https://).
	 */
	private function getSSLProtocol() {
		$ssl = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') $ssl .= 's';
		return $ssl;
	}

	private function withoutSSL() {
		$query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT url FROM @configuracion WHERE tscript_id = 1"));
		return $query['url'];
	}

	public function __construct() {
		// CARGANDO CONFIGURACIONES
		$this->settings = $this->getSettings();
		$this->settings['domain'] = $this->withoutSSL();
	}

	/*
		getSettings() :: CARGA DESDE LA DB LAS CONFIGURACIONES DEL SITIO
	*/
	public function getSettings() {
		$query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM @configuracion WHERE tscript_id = 1"));
		$query['url'] = $this->getSSLProtocol() . '://' . $query['url'];
		return $query;
	}

	public function setRoutes(?string $param = null, ?string $extra = null): string|array {
   	// Definir rutas base
   	$basePaths = [
   	   'tema' => "{$this->settings['url']}/themes/{$this->settings['tema']}",
   	   'assets' => "{$this->settings['url']}/assets"
   	];
   	$mystorage = "{$this->settings['url']}/storage";
   	$myimages = "{$basePaths['assets']}/images";
   	
   	// Generar rutas para CSS, JS e imágenes dentro de cada ruta base
   	$routes = array_map(fn($path) => ['base' => $path,'css' => "$path/css",'js' => "$path/js",'images' => "$path/images"], $basePaths);
   	
   	// URLs generales
   	$routes['url'] = $this->settings['url'];
   	$routes['domain'] = $this->withoutSSL();
   	$routes['canonical'] = urlencode("{$this->getSSLProtocol()}:/{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
   	// Rutas de recursos específicos
   	$routes['assets'] = array_merge($routes['assets'], [
   	   'favicon' => "$myimages/favicon",
   	   'categorias' => "$myimages/categorias",
   	   'fonts' => "{$basePaths['assets']}/fonts"
   	]);
   	// Rutas de almacenamiento
   	foreach(['base', 'avatar', 'portadas', 'uploads'] as $store) {
   		$routes['storage'][$store] = ($store === 'base' ? $mystorage : "$mystorage/$store");
   	}

   	// Rutas de logotipos
   	foreach([32, 64, 128, 256] as $size) $routes['logos'][$size] = "{$routes['assets']['favicon']}/logo-$size.webp";
   	$routes['logos']['big'] = "$myimages/favicon/{$this->setSEO($this->settings['titulo'])}.webp";
   	// Retornar la estructura de rutas según los parámetros
   	return $param === null ? $routes : ($extra === null ? ($routes[$param] ?? []) : ($routes[$param][$extra] ?? ''));
	}

	
	public function getNovemods() {
		$datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT 
			(SELECT COUNT(post_id) FROM @posts WHERE post_status = 3) as revposts, 
			(SELECT COUNT(cid) FROM @posts_comentarios WHERE c_status = 1 ) as revcomentarios, 
			(SELECT COUNT(DISTINCT obj_id) FROM @denuncias WHERE d_type = 1) as repposts, 
			(SELECT COUNT(DISTINCT obj_id) FROM @denuncias WHERE d_type = 2) as repmps, 
			(SELECT COUNT(DISTINCT obj_id) FROM @denuncias WHERE d_type = 3) as repusers, 
			(SELECT COUNT(DISTINCT obj_id) FROM @denuncias  WHERE d_type = 4) as repfotos, 
			(SELECT COUNT(susp_id) FROM @suspension) as suspusers, 
			(SELECT COUNT(post_id) FROM @posts WHERE post_status = 2) as pospelera, 
			(SELECT COUNT(foto_id) FROM @fotos WHERE f_status = 2) as fospelera')
		);
		$datos['total'] = $datos['repposts'] + $datos['repfotos'] + $datos['repmps'] + $datos['repusers'] + $datos['revposts'] + $datos['revcomentarios'];
		return $datos;  
	}
	/*
		getCategorias()
	*/
	public function getCategorias() {
		// CONSULTA
		$categorias = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT cid, c_orden, c_nombre, c_seo, c_color, c_descripcion, c_img FROM @posts_categorias ORDER BY c_orden'));
		foreach($categorias as $cid => $cat) {
			$categorias[$cid]['c_img'] = $this->setRoutes('assets', 'categorias') . "/{$cat['c_img']}";
		}
		return $categorias;
	}

	public function imageCat(string $cat = '') {
		return $this->setRoutes('assets', 'categorias') . "/$cat";
	}

	/*
		getTema()
	*/
	public function getTema() {
		$data['t_url'] = $this->settings['url'] . '/themes/' . $this->settings['tema'];
		//
		return $data;
	}

	/**
	 * Obtiene noticias activas de la base de datos, ordenadas aleatoriamente.
	 *
	 * @return array Las noticias con su cuerpo parseado.
	*/
	public function getNews() {
		// Inicializar array de datos
		// Ejecutar consulta para obtener noticias activas ordenadas aleatoriamente
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT not_id, not_body, not_type FROM @noticias WHERE not_active = 1 ORDER BY RAND()'));
		// Procesar resultados de la consulta
		foreach($data as $nid => $new) {
			$data[$nid]['not_type'] = (int)$new['not_type'];
			// Parsear BBCode en el cuerpo de la noticia
			$data[$nid]['not_body'] = $this->parseBBCode($new['not_body'], 'news');
		}
		// Retornar los datos procesados
		return $data;
	}
	 
	/**
	 * Censura las palabras malas en una cadena dada.
	 *
	 * @param string $c La cadena a procesar.
	 * @param bool $s Indica si se deben incluir todas las palabras malas, no solo las del tipo 0.
	 * @return string La cadena procesada con las palabras malas censuradas.
	*/
	public function parseBadWords(string $censurar = '', bool $type = FALSE)  {
		if (empty($censurar)) {
			return $censurar; // Retornar inmediatamente si la cadena est� vac�a.
		}
		// Construir la consulta
		$query = 'SELECT word, swop, method, type FROM @badwords';
		if (!$type) {
			$query .= ' WHERE type = \'0\'';
		}
		$query = result_array(db_exec([__FILE__, __LINE__], 'query', $query));
		
		foreach($query AS $badword) {
			$search = ((int)$badword['method'] == 0) ? $badword['word'] : "{$badword['word']} ";
			$replace = ((int)$badword['type'] == 1) ? '<img title="' . $this->setSecure($badword['word']) . '" src="' . $this->setSecure($badword['swop']) . '" align="absmiddle"/>' : "{$badword['swop']} ";
			$censurar = str_ireplace($search, $replace, $censurar);
		}
		return $censurar;
	}        
	
	/*
		setLevel($tsLevel) :: ESTABLECE EL NIVEL DE LA PAGINA | MIEMBROS o VISITANTES
	*/
	public function setLevel(?int $tsLevel = null, bool $message = false) {
		global $tsUser;
		// Los mensajes
		$setMessages = [
			1 => 'Esta p&aacute;gina solo es vista por los visitantes.',
			2 => 'Para poder ver esta p&aacute;gina debes iniciar sesi&oacute;n.',
			3 => 'Estas en un &aacute;rea restringida solo para moderadores.',
			4 => 'Estas intentando algo no permitido.'
		];
		// Definimos los accesos!
		$conditions = [
			0 => true, // CUALQUIERA
			1 => $tsUser->is_member === 0, // SOLO VISITANTES
			2 => $tsUser->is_member === 1, // SOLO MIEMBROS
			3 => $tsUser->is_admod || (!empty($tsUser->permisos) && isset($tsUser->permisos['moacp']) && $tsUser->permisos['moacp']), // SOLO MODERADORES
			4 => $tsUser->is_admod === 1 // SOLO ADMIN
		];
		$tsLevel = $tsLevel ?? 0;
		
		if (isset($conditions[$tsLevel]) && $conditions[$tsLevel]) return true;
		// Manejo de mensajes de error
		if ($message) return ['titulo' => 'Error', 'mensaje' => $setMessages[$tsLevel] ?? 'Error desconocido.'];
		// Redireccionamiento
		$redirects = ((int)$tsLevel === 1) ? '/' : '/login/?r='.$this->currentUrl();
		$this->redirectTo($redirects);	   
	}

	/**
	 * Redirige a una p�gina espec�fica dentro del sitio.
	 *
	 * @param string $page La p�gina a la que redirigir.
	 * @param string $subpage La subp�gina opcional a la que redirigir.
	 * @param string $param Los par�metros opcionales de la URL.
	 * @return void
	*/
	public function redireccionar(string $page = '', string $subpage = '', string $param = '') {
		// Construir la URL de destino
		$url = "{$this->settings['url']}/$page/$subpage";
		if (!empty($param)) $url .= "?$param";
		// Redirigir al usuario
		$this->redirectTo($url);
	}
	
	/**
	 * Redirige a la URL proporcionada.
	 *
	 * @param string $tsDir La URL a la que redirigir.
	 * @return void
	 */
	public function redirectTo(string $tsDir = '/') {
		$reloader = $tsDir === '/' ? $this->settings['url'] : $tsDir;
		header("Location: $reloader");
		exit();
	}

	/*
		getDomain()
	*/
	public function getDomain() {
		$domain = explode('/', $this->withoutSSL());
		$domain = (is_array($domain)) ? explode('.', $domain[0]) : explode('.', $domain);
		//
		$t = safe_count($domain);
		$domain = $domain[$t - 2] . '.' . $domain[$t - 1];
		//
		return $domain;
	}
	/*
		currentUrl()
	*/
	public function currentUrl(){
		$current_url = $this->getSSLProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return urlencode($current_url);
	}

	/**
	 * Realiza una sanitizaci�n de cadenas para evitar inyecciones SQL y XSS.
	 * 
	 * @param string $string La cadena a sanitizar.
	 * @param bool $xss Si se debe aplicar filtrado XSS.
	 * @return string La cadena sanitizada.
	 */
	public function setSecure($string = null, bool $xss = false) {
		if (empty($string)) {
			return $string;
		}
		// Escapar el valor para evitar inyecciones SQL
		$string = db_exec('real_escape_string', $string);
		// Aplicar filtrado XSS si es necesario
		if ($xss) {
			$string = htmlspecialchars($string, ENT_COMPAT | ENT_QUOTES, 'UTF-8');
		}
		// Retornamos la informaci�n sanitizada
		return $string;
	}
	
	/*
		antiFlood()
	*/
	public function antiFlood($print = true, $type = 'post', $msg = '') {
		global $tsUser;
		//
		$now = time();
		$msg = empty($msg) ? 'No puedes realizar tantas acciones en tan poco tiempo.' : $msg;
		//
		$_SESSION['flood'][$type] = (!isset($_SESSION['flood'][$type])) ? '' : 3;
		$limit = $tsUser->permisos['goaf'];
		$resta = $now - $_SESSION['flood'][$type];
		if($resta < $limit) {
			$msg = '0: '.$msg.' Int&eacute;ntalo en '.($limit - $resta).' segundos.';
			// TERMINAR O RETORNAR VALOR
			if($print) die($msg);
			else return $msg;
		} else {
			// ANTIFLOOD
			$_SESSION['flood'][$type] = (empty($_SESSION['flood'][$type])) ? time() : $now;
			// TODO BIEN
			return true;
		}
	}
	
	/**
	 * Convierte una cadena en un formato amigable para SEO.
	 * 
	 * @param string $string La cadena a convertir.
	 * @param bool $lower Si se debe convertir a min�sculas.
	 * @return string La cadena convertida.
	 */
	public function setSEO($string, $lower = false) {
	   // Convertir la cadena a UTF-8 y entidades HTML
	   $string = mb_convert_encoding($string ?? '', 'UTF-8', 'auto');
	   $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
	   // Reemplazar entidades HTML comunes en espa�ol por sus equivalentes
	   $string = preg_replace('~&([a-zA-Z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
	   // Decodificar entidades HTML
	   $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	   // Reemplazar cualquier car�cter no alfanum�rico por guiones
	   $string = preg_replace('~[^0-9a-z]+~i', '-', $string);
	   // Convertir a min�sculas si es necesario
		if ($lower) {
			$string = strtolower($string);
		}
	   // Eliminar guiones al inicio y al final, y convertir a min�sculas
	   return trim($string, '-');
	}

	/*
		parseBBCode($bbcode)
	*/
	public function parseBBCode($bbcode, $type = 'normal') {
		// Class BBCode
		include_once TS_EXTRA . 'bbcode.inc.php';
		$parser = new BBCode();
		// Seleccionar texto
		$parser->setText($bbcode);
		//
		$buttons = [
			'normal' => ['url', 'code', 'quote', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler', 'video', 'hr', 'sub', 'sup', 'table', 'td', 'tr', 'ul', 'li', 'ol', 'notice', 'info', 'warning', 'error', 'success'],
		  'firma' => ['url', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler'],
		  'news' => ['url', 'b', 'i', 'u', 's']
		];
		// Determinar si el tipo es 'normal' o 'smiles', en cuyo caso usar� los botones de 'normal'
		$allowed_buttons = ($type === 'normal' || $type === 'smiles') ? $buttons['normal'] : $buttons[$type];
		$parser->setRestriction($allowed_buttons);
		// Parsear menciones si el tipo es 'normal' o 'smiles'
		if ($type === 'normal' || $type === 'smiles') {
			$parser->parseMentions();
		}
		// Parsear smiles si el tipo es 'normal', 'smiles' o 'news'
		$parser->parseSmiles();
		// Retornar resultado en HTML
		return $parser->getAsHtml();
	}

	/**
	 * @name setMenciones
	 * @access public
	 * @param string $html
	 * @return string
	 * @info Pone los links a los mencionados
	 * @note Esta funci�n se ha reemplazado por $parser->parseMentions(). Se recomienda exclusivamente para compatibilidad en 	versiones anteriores.
	*/
	public function setMenciones($html) {
		global $tsUser;
		// Buscar usuarios mencionados con el patr�n @username
		if (preg_match_all('/\B@([a-zA-Z0-9_-]{4,16})\b/', $html, $users)) {
			$menciones = $users[1];
			// Verificar y reemplazar menciones con enlaces
			foreach ($menciones as $user) {
				$uid = $tsUser->getUserID($user);
				 if (!empty($uid)) {
					$html = str_replace("@$user", "@<a href=\"{$this->settings['url']}/perfil/$user\">$user</a>", $html);
				}
			}
		}
		// Retornar el HTML modificado
		return $html;
	}

	/**
	 * Establece el l�mite de p�ginas y el inicio para la paginaci�n.
	 *
	 * @param int $tsLimit El l�mite de resultados por p�gina.
	 * @param bool $start Indica si se debe establecer el inicio de la paginaci�n.
	 * @param int $tsMax El n�mero m�ximo de resultados permitidos.
	 * @return string El inicio y el l�mite de resultados como una cadena.
	*/
	public function setPageLimit($tsLimit, $start = false, $tsMax = 0) {
		// Inicializar el inicio de la paginaci�n
		$tsStart = 0;
		// Establecer el inicio de la paginaci�n si es necesario
		if ($start !== false) {
			$tsStart = isset($_GET['s']) ? (int) $_GET['s'] : 0;
			// Establecer el inicio en 0 si se excede el l�mite m�ximo
			if ($this->setMaximos($tsLimit, $tsMax)) {
				$tsStart = 0;
			}
		} else {
			// Calcular el inicio basado en el n�mero de p�gina
			$pageNumber = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
			$tsStart = ($pageNumber - 1) * $tsLimit;
		}
		// Retornar el inicio y el l�mite de resultados
		return "$tsStart,$tsLimit";
	}

	/**
	 * Verifica si se excede el l�mite m�ximo de p�ginas.
	 *
	 * @param int $tsLimit El l�mite de resultados por p�gina.
	 * @param int $tsMax El n�mero m�ximo de resultados permitidos.
	 * @return bool True si se excede el l�mite m�ximo, false en caso contrario.
	*/
	public function setMaximos(int $tsLimit = 0, int $tsMax = 0) {
		// MAXIMOS || PARA NO EXEDER EL NUMERO DE PAGINAS
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
		$ban1 = ($page * $tsLimit);
		if($tsMax < $ban1){
			$ban2 = $ban1 - $tsLimit;
			if($tsMax < $ban2) return true;
		} 
		//
		return false;
	}

	/**
	 * Genera informaci�n sobre la paginaci�n de un conjunto de resultados.
	 *
	 * @param int $tsTotal El n�mero total de resultados.
	 * @param int $tsLimit El l�mite de resultados por p�gina.
	 * @return array La informaci�n de paginaci�n.
	 */
	public function getPages(int $tsTotal = 0, int $tsLimit = 0) {
		// Verificar si el l�mite es v�lido
		if ($tsLimit <= 0) {
			return []; // Devolver un array vac�o si el l�mite es cero o negativo
		}
		// Calcular el n�mero total de p�ginas
		$tsPages = ceil($tsTotal / $tsLimit);
		// Obtener el n�mero de p�gina actual
		$tsPage = isset($_GET['page']) ? max(1, min($_GET['page'], $tsPages)) : 1;
		// Verificar si el n�mero de p�gina actual excede el total de p�ginas
		if ($tsPage > $tsPages) {
			$tsPage = $tsPages;
		}
		// Construir el array de informaci�n de paginaci�n
		$pages = [
			'current' => $tsPage,
			'pages' => $tsPages,
			'section' => $tsPages + 1,
			'prev' => max(1, $tsPage - 1),
			'next' => min($tsPages, $tsPage + 1),
			'max' => $this->setMaximos($tsLimit, $tsTotal)
		];
		// Retornar la informaci�n de paginaci�n
		return $pages;
	}

	/*
		getPagination($total, $per_page)
	*/
	public function getPagination($total, $per_page = 10){
		// PAGINA ACTUAL
		$page = empty($_GET['page']) ? 1 : (int) $_GET['page'];
		// NUMERO DE PAGINAS
		$num_pages = ceil($total / $per_page);
		// ANTERIOR
		$prev = $page - 1;
		$pages['prev'] = ($page > 0) ? $prev : 0;
		// SIGUIENTE 
		$next = $page + 1;
		$pages['next'] = ($next <= $num_pages) ? $next : 0;
		// LIMITE DB
		$pages['limit'] = (($page - 1) * $per_page).','.$per_page; 
		// TOTAL
		$pages['total'] = $total;
		//
		return $pages;
	}

	/**/
	public function pageIndex($base_url, $max_value, $num_per_page, $flexible_start = false) {
		// Remove the 's' parameter from the base URL
		$base_url = $this->settings['url'] . $base_url;
		$base_url = preg_replace('/[?&]s=\d*/', '', $base_url);
		// Ensure $start is a non-negative integer and a multiple of $num_per_page
		$start = max(0, (isset($_GET['s']) ? (int)$_GET['s'] : 0));
		$start -= $start % $num_per_page;
		$morepages = '<div class="page-item off"><span class="page-numbers">...</span></div>';

		// Initialize the page index string
		$pageindex = '';
		$pageindex .= '<nav class="pagination">';
		// Generate the link format based on whether flexible_start is enabled or not
		$flexstart = $base_url . ($flexible_start ? '' : '&s=%d');
		$base_link = "<div class=\"page-item\"><a class=\"page-numbers\" href=\"$flexstart\">%s</a></div> ";
		
		// Calculate the number of contiguous page links to show
		$PageContiguous = 2;
		// Helper function to generate page links
		$generatePageLink = function ($pageNumber) use ($base_link, $num_per_page) {
			return sprintf($base_link, $pageNumber * $num_per_page, $pageNumber + 1);
		};
		// Add the link to the first page if necessary
		if ($start > $num_per_page * $PageContiguous) {
			  $pageindex .= $generatePageLink(0) . ' ';
		}
		// Add '...' before the first page link if necessary
		if ($start > $num_per_page * ($PageContiguous + 1)) {
			  $pageindex .= $morepages;
		}
		// Add page links before the current page
		for ($i = $PageContiguous; $i >= 1; $i--) {
			  $pageNumber = $start / $num_per_page - $i;
			  if ($pageNumber >= 0) {
					$pageindex .= $generatePageLink($pageNumber);
			  }
		}
		// Add the link to the current page
		$pageindex .= '<div class="page-item"><span aria-current="page" class="page-numbers current">' . ($start / $num_per_page + 1) . '</span></div> ';
		// Add page links after the current page
		for ($i = 1; $i <= $PageContiguous; $i++) {
			  $pageNumber = $start / $num_per_page + $i;
			  // Ensure the link is within the valid page range
			  if ($pageNumber * $num_per_page < $max_value) {
					$pageindex .= $generatePageLink($pageNumber);
			  }
		}
		// Add '...' near the end if necessary
		if ($start + $num_per_page * ($PageContiguous + 1) < $max_value - $num_per_page) {
			  $pageindex .= $morepages;
		}
		// Add the link to the last page if necessary
		if ($start + $num_per_page * $PageContiguous < $max_value - $num_per_page) {
			  $pageNumber = (int) (($max_value - 1) / $num_per_page);
			  $pageindex .= $generatePageLink($pageNumber);
		}
		$pageindex .= '</nav>';
		return $pageindex;
	}
	
	/*
		setHace()
	*/
	public function setHace(int $fecha = 0, $show = false){
		# Creamos
		$tiempo = time() - $fecha;
		if($fecha <= 0) return "Nunca";
		// Declaraci�n de unidades de tiempo, aunque es un aproximado
		// Ya que existe a�os bisiestos 366 d�as
		$unidades = [
		  31536000 => ["a&ntilde;o", "a&ntilde;os"],
		  2678400 => ["mes", "meses"],
		  604800 => ["semana", "semanas"],
		  86400 => ["d&iacute;a", "d&iacute;as"],
		  3600 => ["hora", "horas"],
		  60 => ["minuto", "minutos"],
		];
		foreach($unidades as $segundos => $nombre){
			$round = round($tiempo / $segundos);
			$s = ($segundos === 2678400) ? 'es' : 's';
			if($tiempo <= 60) $hace = "instantes";
			else {
				if($round > 0) {
					$hace = "{$round} {$nombre[($round > 1 ? 1 : 0)]}";
					break;
				}
			}
		}
		// Si se ha establecido la opci�n $show, se agrega 'Hace' al resultado
		return ($show ? "Hace " : "") . $hace;
	}

	/*
		getUrlContent($tsUrl) :: Mejorado
	*/
	public function getUrlContent(string $tsUrl): ?string {
		// USAMOS CURL O FILE
		if (function_exists('curl_init')) {
			// Obtener el user agent del cliente
			$useragent = $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.9) Gecko/2008052906 Firefox/3.0';
			// Abrir conexi�n  
			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => $tsUrl,
				CURLOPT_USERAGENT => $useragent,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_RETURNTRANSFER => true,
			]);
			$result = curl_exec($ch);
			curl_close($ch);
		} else $result = @file_get_contents($tsUrl);
		return $result ?: null;
	}

	/**
	 * Funci�n privada para validar la IP del usuario
	*/
	private function isValidIP(string $ip): bool {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
	}

	/**
	 * Funci�n para obtener la IP del usuario
	*/
	public function getIP(): string {
		$ip = 'unknown';
		// List of trusted proxy IP headers
		$trustedHeaders = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
		foreach ($trustedHeaders as $header) {
			if (isset($_SERVER[$header]) && $this->isValidIP($_SERVER[$header])) {
				$ip = $_SERVER[$header];
				break;
			}
		}
		return $this->setSecure($ip);
	}

	/**
	 * Funci�n para validar y obtener la direcci�n IP del cliente que realiza la petici�n.
	 *
	 * @return string|null La direcci�n IP v�lida del cliente o NULL si no se puede validar.
	*/
	public function validarIP() {
		$_SERVER['REMOTE_ADDR'] = $_SERVER['X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Funci�n para validar y obtener la direcci�n IP del cliente que realiza la petici�n.
	 *
	 * @return string|null La direcci�n IP v�lida del cliente o NULL si no se puede validar.
	*/
	public function executeIP() {
		$myIP = $this->validarIP();
		if(!filter_var($myIP, FILTER_VALIDATE_IP)) die('Su ip no se pudo validar.');
		return $myIP ?? $this->getIP();
	}

	/**
	 * Genera una cadena SQL para actualizar valores en la base de datos
	 *
	 * @param array $array Array asociativo con los campos y valores a actualizar
	 * @param string $prefix Prefijo para los campos
	 * @return string Cadena SQL con los campos actualizados
	*/
	public function getIUP(array $array = [], string $prefix = ''): string {
		$sets = [];
		foreach ($array as $field => $value) {
			$sets[] = "$prefix$field = " . (is_numeric($value) ? (int)$value : "'{$this->setSecure($value)}'");
		}
		return implode(', ', $sets);
	}

}