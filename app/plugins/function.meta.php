<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {meta facebook=true twitter=false} 
 * Enlace: #
 * Fecha: Dic 31, 2023  
 * Actualizado: Dic 13, 2024
 * Nombre: meta
 * Proposito: Añadir las etiquetas meta para facebook y twitter(X) 
 * Tipo: function 
 * Version: 2.0 
*/

include TS_MODELS . 'c.seo.php';
function smarty_function_meta($params, &$smarty) {
	// Opciones por defecto
	$default = [
		'facebook' => false,
		'twitter' => false,
		'analytics' => false
	];

	$infoSeo = new tsSeo();
	$tsCore = $smarty->tpl_vars["tsConfig"]->value;
   $tsPost = $smarty->tpl_vars["tsPost"]->value ?? [];
   $tsFoto = $smarty->tpl_vars["tsFoto"]->value ?? [];
	$tsSeo = $infoSeo->getSeo();

	if(empty($tsSeo)) return '';

	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

	$keywords = $tsPost['post_id'] ? implode(', ', $tsPost['post_tags']) : $tsSeoData['seo_keywords'];
	$images = $tsPost['post_portada']['lg'] ?? $tsFoto['foto_url'] ?? "{$tsCore['assets']}/images/sin_portada.png";

	$metatags = [
		'type' => isset($tsPost['post_id']) ? 'article' : 'website',
		'title' => $tsPost['post_title'] ?? $tsFoto['f_title'] ?? $tsSeoData['seo_titulo'] ?? $tsCore['titulo'],
		'description' => $tsPost['post_body_descripcion'] ?? $tsFoto['foto_descripcion'] ?? $tsSeoData['seo_descripcion'] ?? "{$tsCore['titulo']} - {$tsCore['slogan']}",
		'keywords' => strtolower($keywords ?? ''),
		'image' => $images,
		'card' => 'summary_large_image'
	];

	$meta = "";

	# AÑADIMOS META DEFAULT
	foreach ($metatags as $nametag => $tag) {
		if($nametag === 'card') continue;
		$meta .= "<meta name=\"$nametag\" content=\"$tag\" />\n";
	}

	# AÑADIMOS META PARA FACEBOOK | TWITTER
	$redes = [
		'facebook' => [
			'attr' => 'name',
			'prop' => 'og',
			'data' => ['type', 'title', 'description', 'image'],
		],
		'twitter' => [
			'attr' => 'property',
			'prop' => 'twitter',
			'data' => ['card', 'title', 'description', 'image'],
		]
	];
	foreach ($redes as $i => $social) {
		if($params[$i] AND $params[$i]) {
			$meta .= "<meta {$social['attr']}=\"{$social['prop']}:url\" content=\"$url\" />\n";
			foreach ($social['data'] as $d => $info) {
				$meta .= "<meta {$social['attr']}=\"{$social['prop']}:$info\" content=\"$metatags[$info]\" />\n";
			}
		}
	}

	# AÑADIMOS ROBOTS META
	if((int)$tsSeo['seo_robots']) {
		$optionsRobots = [
			'name' => ['robots', 'googlebot', 'googlebot-news'],
			'content' => ['index', 'follow', 'noindex', 'nofollow', 'nosnippet', 'index, follow', 'index, nofollow', 'noindex, follow', 'noindex, nofollow']
		];
		$__name = $optionsRobots['name'][$tsSeo['seo_robots_data']['name'] ?? 0] ?? 'robots';
		$__content = $optionsRobots['content'][$tsSeo['seo_robots_data']['content'] ?? 0] ?? 'index, follow';
		$meta .= "<meta name=\"$__name\" content=\"$__content\" />\n";
	}

	# AÑADIMOS SITEMAP, SOLO SI ESTA ACTIVO O EXISTE
	if((int)$tsSeo['seo_sitemap'] OR file_exists(TS_ROOT . 'sitemap.xml')) {
		$meta .= "<link rel=\"sitemap\" type=\"application/xml\" title=\"Mapa del sitio\" href=\"{$tsCore['url']}/sitemap.xml\">\n";
	}

	# AÑADIMOS EL FAVICON
	if(isset($tsSeo['seo_favicon']) AND !empty($tsSeo['seo_favicon'])) {
		$meta .= "<!-- Favicon del sitio -->\n";
		$type = pathinfo((string) $tsSeo['seo_favicon'], PATHINFO_EXTENSION);
		foreach($tsSeo['seo_images'] as $im => $img) {
			if(!empty($img)) {
				$meta .= "<link href=\"{$tsCore['assets']}$img\" rel=\"shortcut icon\" type=\"image/$type\" sizes=\"{$im}x{$im}\" />\n";
			}
		}
	}

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

   $idGoogle = htmlspecialchars(trim($tsSeo['seo_google_analytics']), ENT_QUOTES, 'UTF-8');

	# AÑADIMOS VERIFICACIÓN GOOGLE 
	if(!empty($tsSeo['seo_google_verification']) AND (int)$tsSeo['seo_google_verification_active'] === 1) {
		$meta .= "<meta name=\"google-site-verification\" content=\"{$tsSeo['seo_google_verification']}\" />\n";
	}
   // Validar el formato del ID
   if (validarIDGoogleAnalytics($idGoogle) AND $params['analytics']) {
	   // Generar el código de Google Analytics
	   $meta .= trim("<!-- Google tag (gtag.js) -->\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=$idGoogle\"></script>\n<script>window.dataLayer=window.dataLayer||[];const gtag=()=>dataLayer.push(arguments);gtag('js',new Date());gtag('config','$idGoogle');</script>");
	}

	// Retornamos
	return trim($meta);
}