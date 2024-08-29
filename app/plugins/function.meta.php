<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {meta facebook=true twitter=false} 
 * Enlace: #
 * Fecha: Dic 31, 2023  
 * Nombre: meta
 * Proposito: Añadir las etiquetas meta para facebook y twitter(X) 
 * Tipo: function 
 * Version: 1.0 
*/

include_once TS_MODELS . "c.seo.php";

function smarty_function_meta($params, &$smarty) {
	global $tsCore, $tsPost, $tsFoto;
	// 
	$protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
	$dominio = $_SERVER['HTTP_HOST'];
	$ruta = $_SERVER['REQUEST_URI'];

	$url = $protocolo . "://" . $dominio . $ruta;

	$tsSeo = new tsSeo;
	$data = $tsSeo->getSEO();

	if(empty($data)) return '';

	// Titulo
	$title = (is_numeric($tsPost['post_id'])) ? $tsPost['post_title'] : ($tsFoto['foto_id'] ? $tsFoto['f_title'] : (empty($data['seo_titulo']) ? $tsCore->settings['titulo'] : $data['seo_titulo']));

	// Descripcion
	$description = (is_numeric($tsPost['post_id'])) ? $tsPost['post_body_descripcion'] : ($tsFoto['foto_id'] ? $tsFoto['foto_descripcion'] : (empty($data['seo_descripcion']) ? "{$tsCore->settings['titulo']} - {$tsCore->settings['slogan']}" : $data['seo_descripcion']));

	// Etiquetas
	$keywords = (is_numeric($tsPost['post_id'])) ? join(',', $tsPost['post_tags']) : $data['seo_keywords'];
	$keywords = empty($keywords) ? '' : strtolower($keywords);

	// Portada
	if(isset($tsPost['post_portada']) AND empty($tsPost['post_portada'])) {
		$tsPost['post_portada'] = "{$tsCore->settings['public']}/images/sin_portada.png";
	} 
	$images = (is_numeric($tsPost['post_id'])) ? $tsPost['post_portada'] : ($tsFoto['foto_id'] ? $tsFoto['foto_url'] : $data['seo_portada']);

	// Tipo
	$type = is_numeric($tsPost['post_id']) ? 'article' : 'website';

	$nameRobots = [0 => 'robots', 1 => 'googlebot', 2 => 'googlebot-news'];
	$contentRobots = [0 => 'index', 1 => 'follow', 2 => 'noindex', 3 => 'nofollow', 4 => 'nosnippet', 5 => 'index, follow', 6 => 'index, nofollow', 7 => 'noindex, follow', 8 => 'noindex, nofollow'];

	$meta = "<!-- Meta Tags Generado por {$tsCore->settings['url']} -->\n";
	$tags = ['title', 'description', 'keywords'];
	// Etiquetas por defecto
	foreach ($tags as $tag) $meta .= "<meta name=\"$tag\" content=\"".$$tag."\" />\n";

	if((int)$data['seo_robots']) {
		$robots_data = json_decode($data['seo_robots_data'], true);
		$meta .= "<meta name=\"{$nameRobots[$robots_data['name']]}\" content=\"{$contentRobots[$robots_data['content']]}\" />\n";
	}

	$meta .= $tsSeo->addRobotsTXT();
	if((int)$data['seo_sitemap']) {
		$meta .= "<link rel=\"sitemap\" type=\"application/xml\" title=\"Mapa del sitio\" href=\"{$tsCore->settings['url']}/sitemap.xml\">\n";
	}
	$card = 'summary_large_image';
	$redes = [
		'facebook' => [
			'attr' => 'name',
			'prop' => 'og',
			'data' => ['type', 'url', 'title', 'description', 'image'],
		],
		'twitter' => [
			'attr' => 'property',
			'prop' => 'twitter',
			'data' => ['card', 'url', 'title', 'description', 'image'],
		]
	];
	foreach ($redes as $i => $social) {
		if($params[$i]) {
			$meta .= "<!-- ".ucfirst($i)." -->\n";
			foreach ($social['data'] as $d => $info) {
				# Añadiendo imagen
				if($info === 'image') {
					$$info = is_array($images) ? $images['lg'] : $images;
				}
				$meta .= "<meta {$social['attr']}=\"{$social['prop']}:$info\" content=\"".$$info."\" />\n";
			}
		}
	}
	
	if(isset($data['seo_favicon']) AND !empty($data['seo_favicon'])) {
		$type = pathinfo($data['seo_favicon'], PATHINFO_EXTENSION);
		$data['seo_favicon'] .= '?t=' . uniqid();
		foreach($data['seo_images'] as $im => $img) {
			if(!empty($img)) {
				$img .= '?t=' . uniqid();
				$meta .= "<link href=\"$img\" rel=\"shortcut icon\" type=\"image/$type\" sizes=\"{$im}x{$im}\" />\n";
			}
		}
	}
	// Retornamos
	return trim($meta);
}