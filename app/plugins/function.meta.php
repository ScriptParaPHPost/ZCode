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


function smarty_function_meta($params) {
	// Opciones por defecto
	$default = [
		'facebook' => $params['facebook'] ?? false,
		'twitter' => $params['twitter'] ?? false,
		'analytics' => $params['analytics'] ?? false,
		'robots' => [
			'active' => $params['robots'] ?? false,
			'data' => [
				'name' => $params['name'] ?? 'robots',
				'content' => $params['content'] ?? 'index, follow'
			]
		]
	];
	include_once TS_MODELS . 'c.seo.php';
	$infoSeo = new tsSeo();
	$tsCore = $GLOBALS['smarty']->tpl_vars["tsConfig"]->value;
	$tsRoutes = $GLOBALS['smarty']->tpl_vars["tsRoutes"]->value;
   $tsPost = $GLOBALS['smarty']->tpl_vars["tsPost"]->value ?? [];
   $tsFoto = $GLOBALS['smarty']->tpl_vars["tsFoto"]->value ?? [];
	$seoclass = $infoSeo->getSeo();

	if(empty($seoclass)) return '';

	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

	$keywords = $tsPost['post_id'] ? implode(', ', $tsPost['post_tags']) : $seoclass['seo_keywords'];
	$images = $tsPost['post_portada']['lg'] ?? $tsFoto['foto_url'] ?? "{$tsRoutes['assets']['base']}/images/sin_portada.png";

	$metatags = [
		'type' => isset($tsPost['post_id']) ? 'article' : 'website',
		'title' => $tsPost['post_title'] ?? $tsFoto['f_title'] ?? $seoclass['seo_titulo'] ?? $tsCore['titulo'],
		'description' => $tsPost['post_body_descripcion'] ?? $tsFoto['foto_descripcion'] ?? $seoclass['seo_descripcion'] ?? "{$tsCore['titulo']} - {$tsCore['slogan']}",
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
		if($default[$i] AND $default[$i]) {
			$meta .= "<meta {$social['attr']}=\"{$social['prop']}:url\" content=\"$url\" />\n";
			foreach ($social['data'] as $d => $info) {
				$meta .= "<meta {$social['attr']}=\"{$social['prop']}:$info\" content=\"$metatags[$info]\" />\n";
			}
		}
	}

	# AÑADIMOS ROBOTS META
	if($default['robots']['active']) {
		$optionsRobots = [
			'name' => ['robots', 'googlebot', 'googlebot-news'],
			'content' => ['index', 'follow', 'noindex', 'nofollow', 'nosnippet', 'index, follow', 'index, nofollow', 'noindex, follow', 'noindex, nofollow']
		];
		$robotData = $default['robots']['data'];
		$meta .= "<meta name=\"{$robotData['name']}\" content=\"{$robotData['content']}\" />\n";
	}

	# AÑADIMOS SITEMAP, SOLO SI ESTA ACTIVO O EXISTE
	if((int)$seoclass['seo_sitemap'] OR file_exists(TS_ROOT . 'sitemap.xml')) {
		$meta .= "<link rel=\"sitemap\" type=\"application/xml\" title=\"Mapa del sitio\" href=\"{$tsCore['url']}/sitemap.xml\">\n";
	}

	# AÑADIMOS EL FAVICON
	$meta .= "<!-- Favicon del sitio -->\n";
	foreach([16,32,64,128,256] as $im => $img) {
		if(!empty($img)) {
			$meta .= "<link href=\"{$tsRoutes['assets']['favicon']}/logo-$img.webp\" rel=\"shortcut icon\" type=\"image/webp\" sizes=\"{$img}x{$img}\" />\n";
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

   $idGoogle = htmlspecialchars(trim($seoclass['seo_google_analytics']), ENT_QUOTES, 'UTF-8');

	# AÑADIMOS VERIFICACIÓN GOOGLE 
	if(!empty($seoclass['seo_google_verification']) AND (int)$seoclass['seo_google_verification_active'] === 1) {
		$meta .= "<meta name=\"google-site-verification\" content=\"{$seoclass['seo_google_verification']}\" />\n";
	}
   // Validar el formato del ID
   if (validarIDGoogleAnalytics($idGoogle) AND !empty($seoclass['seo_google_analytics']) AND $default['analytics']) {
	   // Generar el código de Google Analytics
	   $meta .= trim("<!-- Google tag (gtag.js) -->\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=$idGoogle\"></script>\n<script>\nwindow.dataLayer = window.dataLayer || [];\nconst gtag() {dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', '$idGoogle');\n</script>");
	}

	// Retornamos
	return trim($meta);
}