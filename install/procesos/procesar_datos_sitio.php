<?php 

$site = [
	"titulo" => 		$FN->setInputPost('site_titulo'),
	"slogan" => 		$FN->setInputPost('site_slogan'),
	"url" => 			$FN->setInputPost('site_url'),
	"email" => 			$FN->setInputPost('site_email'),
	"pkey" => 			$FN->setInputPost('site_public'),
	"skey" => 			$FN->setInputPost('site_secret'),
	"version" => 		$FN->generate_name_version(),
	"version_code" => $FN->generate_name_version('version_code')
];
$siteurl = $FN->secure_url() . $site['url'];
$alt = "Script para ZCode";
$github = "https://scriptparaphpost.github.io/grupos/";
$tamanos = [
	'160x600',
	'300x250',
	'468x60',
	'728x90'
];
$images = $siteurl . '/assets/images';

# Actualizando categoría
$titulo = $mysqli->real_escape_string($site['titulo']);
$categoria = $FN->buildUpdateSQL([
	'c_nombre' => $titulo,
	'c_seo' => $FN->slugify($titulo)
]);
$mysqli->query("UPDATE `{$prefix}posts_categorias` SET $categoria WHERE cid = 33 LIMIT 1");

# Actualizando SEO 
$seo = $FN->buildUpdateSQL([
	'seo_titulo' => "{$site['titulo']} - {$site['slogan']}",
	'seo_descripcion' => $faster['description'] ?? 'Únete a nuestra comunidad para compartir experiencias y conocer gente nueva. ¡Conéctate hoy mismo!',
	'seo_portada' => '/portadas/logo-512.webp',
	'seo_keywords' => $faster['keywords'] ?? 'comunidad, conocer, red, ampliar, interaccion, compartir, amigos, conectar, relaciones, intereses, encuentros, virtual'
]);
$mysqli->query("UPDATE `{$prefix}seo` SET $seo WHERE seo_id = 1");

# AÑADIMOS PUBLICIDADES
foreach($tamanos as $tamano) {
	$size = explode('x', $tamano);
	$html = "<a href=\"$github\" target=\"_blank\" style=\"display:block;\"><img loading=\"lazy\" alt=\"Publicidad de $tamano\" title=\"$alt\" width=\"{$size[0]}\" height=\"{$size[1]}\" src=\"$images/ad$tamano.webp\"></a>";
	$insert[] = "ads_".substr($tamano, 0, 3)." = '".html_entity_decode($html)."'";
}
$publicidades = join(',', $insert);
$site['tema'] = 'default';

$continue = true;

if (!$mysqli->query("UPDATE {$prefix}configuracion SET {$FN->buildUpdateSQL($site)}, $publicidades WHERE tscript_id = 1")) {
	$message = $mysqli->error;
	$continue = false;
}