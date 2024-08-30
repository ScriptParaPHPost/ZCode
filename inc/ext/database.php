<?php

/**
 * @name database.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.12
 * @description Actualizada y optimizada!
**/

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}fotos_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_foto_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_update` int(10) NOT NULL DEFAULT 0,
  `c_body` text COLLATE utf8mb4_general_ci NULL,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}fotos_favoritos` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `f_foto_id` int(11) NOT NULL DEFAULT 0,
  `f_user` int(11) NOT NULL DEFAULT 0,
  `f_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}fotos` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_album` int(11) NOT NULL DEFAULT 0,
  `f_title` varchar(40) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `f_date` int(10) NOT NULL DEFAULT 0,
  `f_update` int(10) NOT NULL DEFAULT 0,
  `f_description` text COLLATE utf8mb4_general_ci NULL,
  `f_url` tinytext NULL,
  `f_user` int(11) NOT NULL DEFAULT 0,
  `f_closed` int(1) NOT NULL DEFAULT 0,
  `f_visitas` bigint NOT NULL DEFAULT 0,
  `f_status` int(1) NOT NULL DEFAULT 0,
  `f_last` int(1) NOT NULL DEFAULT 0,
  `f_hits` int(11) NOT NULL DEFAULT 0,
  `f_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`foto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}fotos_votos` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `v_foto_id` int(11) NOT NULL DEFAULT 0,
  `v_user` int(11) NOT NULL DEFAULT 0,
  `v_pos` bigint NOT NULL DEFAULT 0,
  `v_neg` bigint NOT NULL DEFAULT 0,
  `v_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}fotos_album` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `a_cover` tinytext NULL,
  `a_description` tinytext COLLATE utf8mb4_general_ci NULL,
  `a_status` int(1) NOT NULL DEFAULT 0,
  `a_date` int(11) NOT NULL DEFAULT 0,
  `a_update` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_borradores` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `b_block_comments` int(1) NOT NULL DEFAULT 0,
  `b_body` text COLLATE utf8mb4_general_ci NULL,
  `b_category` int(4) NOT NULL DEFAULT 0,
  `b_causa` varchar(128) NOT NULL DEFAULT '',
  `b_date` int(10) NOT NULL DEFAULT 0,
  `b_portada` tinytext NULL,
  `b_post_id` int(11) NOT NULL DEFAULT 0,
  `b_private` int(1) NOT NULL DEFAULT 0,
  `b_smileys` int(1) NOT NULL DEFAULT 0,
  `b_sponsored` int(1) NOT NULL DEFAULT 0,
  `b_status` int(1) NOT NULL DEFAULT 1,
  `b_sticky` int(1) NOT NULL DEFAULT 0,
  `b_tags` varchar(128) DEFAULT NULL,
  `b_fuentes` text COLLATE utf8mb4_general_ci NULL,
  `b_title` varchar(120) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `b_update` int(10) NOT NULL DEFAULT 0,
  `b_user` int(11) NOT NULL DEFAULT 0,
  `b_visitantes` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_categorias` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_orden` int(11) NOT NULL DEFAULT 0,
  `c_nombre` varchar(40) NOT NULL DEFAULT '',
  `c_seo` varchar(40) NOT NULL DEFAULT '',
  `c_img` varchar(40) NOT NULL DEFAULT '',
  `c_color` varchar(40) NOT NULL DEFAULT '',
  `c_descripcion` text NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}posts_categorias` (`cid`, `c_orden`, `c_nombre`, `c_seo`, `c_img`, `c_descripcion`) VALUES
(1, 1, 'Animaciones', 'animaciones', '1f4fd.svg', 'Discusión y contenido relacionado con películas animadas, series animadas y técnicas de animación.'),
(2, 2, 'Apuntes y Monografías', 'apuntesymonografias', '1f4d1.svg', 'Recursos y discusiones sobre la elaboración y el uso de apuntes y monografías en estudios académicos.'),
(3, 3, 'Arte', 'arte', '1f3a8.svg', 'Conversaciones sobre diferentes formas de expresión artística, como pintura, escultura, y arte digital.'),
(4, 4, 'Autos y Motos', 'autosymotos', '1f698.svg', 'Noticias, discusiones y consejos relacionados con automóviles y motocicletas, incluyendo reparaciones y mantenimiento.'),
(5, 5, 'Celulares', 'celulares', '1f4f1.svg', 'Información sobre teléfonos móviles, comparaciones de modelos, y consejos de uso.'),
(6, 6, 'Ciencia y Educación', 'cienciayeducacion', '2697.svg', 'Temas relacionados con avances científicos, métodos educativos, y recursos educativos.'),
(7, 7, 'Comics', 'comics', '1f4a5.svg', 'Discusiones sobre cómics, incluyendo series populares, eventos importantes, y cultura pop relacionada.'),
(8, 8, 'Deportes', 'deportes', '26bd.svg', 'Conversaciones sobre eventos deportivos, equipos, jugadores destacados y discusiones generales sobre deportes.'),
(9, 9, 'Downloads', 'downloads', '1f4bd.svg', 'Información y recursos relacionados con descargas de software, juegos u otros archivos digitales.'),
(10, 10, 'E-books y Tutoriales', 'ebooksytutoriales', '1f4da.svg', 'Recomendaciones y discusiones sobre libros electrónicos y tutoriales en diversas áreas de interés.'),
(11, 11, 'Ecología', 'ecologia', '267c.svg', 'Temas relacionados con la conservación del medio ambiente, sostenibilidad y problemas ambientales.'),
(12, 12, 'Economía y Negocios', 'economiaynegocios', '1f4b5.svg', 'Análisis económico, noticias financieras, consejos empresariales y discusiones sobre estrategias de negocio.'),
(13, 13, 'Femme', 'femme', '1f3f5.svg', 'Temas relacionados con mujeres, feminismo, derechos de la mujer y cultura femenina.'),
(14, 14, 'Hazlo tu mismo', 'hazlotumismo', '1f6e0.svg', 'Proyectos, consejos y discusiones sobre bricolaje, reparaciones caseras y manualidades.'),
(15, 15, 'Humor', 'humor', '1f3ad.svg', 'Contenido humorístico, chistes, memes y discusiones sobre comedia.'),
(16, 16, 'Imágenes', 'imagenes', '1f5bc.svg', 'Compartir y discutir fotografías, ilustraciones y gráficos visuales.'),
(17, 17, 'Info', 'info', '1f4d5.svg', 'Información general, noticias rápidas y actualizaciones sobre diversos temas.'),
(18, 18, 'Juegos', 'juegos', '1f3ae.svg', 'Conversaciones sobre videojuegos, desde nuevos lanzamientos hasta clásicos y estrategias de juego.'),
(19, 19, 'Links', 'links', '2693.svg', 'Compartir enlaces de interés, recursos útiles o contenido relevante en la web.'),
(20, 20, 'Linux', 'linux', '1f4bf.svg', 'Discusiones y soporte técnico relacionado con el sistema operativo Linux y software de código abierto.'),
(21, 21, 'Mac', 'mac', '1f4c0.svg', 'Temas específicos relacionados con el sistema operativo macOS, hardware de Apple y aplicaciones.'),
(22, 22, 'Manga y Anime', 'mangayanime', '1f4ad.svg', 'Discusiones sobre cómics y animación japonesa, incluyendo series populares, noticias y cultura otaku.'),
(23, 23, 'Mascotas', 'mascotas', '1f43e.svg', 'Cuidado de mascotas, consejos de adopción, comportamiento animal y salud de las mascotas.'),
(24, 24, 'Música', 'musica', '1f3a7.svg', 'Discusiones sobre géneros musicales, bandas, artistas, conciertos y recomendaciones musicales.'),
(25, 25, 'Noticias', 'noticias', '1f4f0.svg', 'Información actualizada y discusiones sobre eventos actuales y noticias relevantes.'),
(26, 26, 'Off Topic', 'offtopic', '1f4ac.svg', 'Temas que no encajan en otras categorías principales, conversaciones variadas y aleatorias.'),
(27, 27, 'Recetas y Cocina', 'recetasycocina', '1f36a.svg', 'Compartir recetas, técnicas de cocina, consejos gastronómicos y discusiones sobre alimentos.'),
(28, 28, 'Salud y Bienestar', 'saludybienestar', '2624.svg', 'Consejos de salud, bienestar emocional, fitness y estilo de vida saludable.'),
(29, 29, 'Solidaridad', 'solidaridad', '1f397.svg', 'Iniciativas, proyectos y discusiones sobre solidaridad, ayuda social y caridad.'),
(30, 30, 'xxxxxxxxxx', 'xxxxxxxxxx', '1f4e2.svg', 'Noticias: Actualizaciones y novedades sobre nuestro sitio.'),
(31, 31, 'Turismo', 'turismo', '1f3c2.svg', 'Destinos de viaje, consejos para viajeros, experiencias y recomendaciones sobre turismo.'),
(32, 32, 'Streaming', 'streaming', '1f3ac.svg', 'Plataformas de streaming, series, películas y contenido digital en línea.'),
(33, 33, 'Videos On-line', 'videosonline', '1f3a5.svg', 'Compartir y discutir videos en línea, desde cortometrajes hasta contenido viral y tutoriales.');";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_post_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_update` int(10) NOT NULL DEFAULT 0,
  `c_body` text NULL,
  `c_reaccion` enum('','like','love','haha','wow','sad','angry') NOT NULL,
  `c_status` int(1) NOT NULL DEFAULT 0,
  `c_answer` int(1) NOT NULL DEFAULT 0,
  `c_answer_cid` int(1) NOT NULL DEFAULT 0,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}comentarios_reaccion` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `r_comment_id` int(11) NOT NULL DEFAULT 0, 
  `r_user_id` int(11) NOT NULL DEFAULT 0,
  `r_reaction` varchar(10) NOT NULL DEFAULT '',
  `r_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_favoritos` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `fav_user` int(11) NOT NULL DEFAULT 0,
  `fav_post_id` int(38) NOT NULL DEFAULT 0,
  `fav_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_category` int(4) NOT NULL DEFAULT 0,
  `post_title` varchar(120) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `post_body` text COLLATE utf8mb4_general_ci NULL,
  `post_user` int(11) NOT NULL DEFAULT 0,
  `post_cache` int(10) NOT NULL DEFAULT 0,
  `post_comments` bigint NOT NULL DEFAULT 0,
  `post_favoritos` int(11) NOT NULL DEFAULT 0,
  `post_hits` int(11) NOT NULL DEFAULT 0,
  `post_portada` tinytext NULL,
  `post_private` int(1) NOT NULL DEFAULT 0,
  `post_puntos` bigint unsigned NOT NULL DEFAULT 0,
  `post_seguidores` bigint NOT NULL DEFAULT 0,
  `post_shared` bigint NOT NULL DEFAULT 0,
  `post_smileys` int(1) NOT NULL DEFAULT 0,
  `post_sponsored` int(1) NOT NULL DEFAULT 0,
  `post_status` int(1) NOT NULL DEFAULT 0,
  `post_sticky` int(1) NOT NULL DEFAULT 0,
  `post_tags` varchar(128) NOT NULL DEFAULT '',
  `post_fuentes` text COLLATE utf8mb4_general_ci NULL,
  `post_date` int(10) NOT NULL DEFAULT 0,
  `post_update` int(10) NOT NULL DEFAULT 0,
  `post_block_comments` int(1) NOT NULL DEFAULT 0,
  `post_visitantes` int(1) NOT NULL DEFAULT 0,
  `post_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}posts` (`post_id`, `post_user`, `post_category`, `post_title`, `post_body`, `post_date`, `post_tags`) VALUES (1, 1, 30, 'Bienvenido a {$script['version_code']}', '[align=center][size=18]Este es el primer post de los miles que tendrá tu web[/size][/align]\n\r[hr]\n\rCon la versión de [b]ZCode 1.0[/b] actualizada:\n[ol][li]PHP 8.2.12[/li][li]Smarty 4.5.2[/li][li]jQuery 3.7.1[/li][li]Plugins para jQuery actualizado y mejorado[/li][li]Modal modificado y con una nueva función[/li][li]Actualización al crear/editar post[/li][/ol]\r\n[hr]\r\nGracias por elegir a [url=https://www.phpost.es/]PHPost[/url] como tu Link Sharing System.', 0, 'ZCode, Risus, actualizado, smarty, php');";

$zcode_sql[] = "ALTER TABLE `{$db['prefix']}posts` ADD FULLTEXT(`post_tags`);";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_votos` (
  `voto_id` int(11) NOT NULL AUTO_INCREMENT,
  `cant` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `tid` int(11) NOT NULL DEFAULT 0,
  `tuser` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`voto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}posts_stats` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `stats_in` varchar(12) NOT NULL DEFAULT '', 
  `stats_user` int(11) NOT NULL DEFAULT 0,
  `stats_post_id` int(38) NOT NULL DEFAULT 0,
  `stats_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}actividad` (
  `ac_id` int(11) NOT NULL AUTO_INCREMENT,
  `ac_date` int(10) NOT NULL DEFAULT 0,
  `ac_type` int(2) NOT NULL DEFAULT 0,
  `obj_dos` int(11) NOT NULL DEFAULT 0,
  `obj_uno` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ac_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}avisos` (
  `av_id` int(11) NOT NULL AUTO_INCREMENT,
  `av_body` text NULL,
  `av_date` int(10) NOT NULL DEFAULT 0,
  `av_read` int(1) NOT NULL DEFAULT 0,
  `av_subject` varchar(24) NOT NULL DEFAULT '',
  `av_type` int(1) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`av_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}bloqueos` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `b_user` int(11) NOT NULL DEFAULT 0,
  `b_auser` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}follows` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_date` int(10) NOT NULL DEFAULT 0,
  `f_id` int(11) NOT NULL DEFAULT 0,
  `f_type` int(1) NOT NULL DEFAULT 0,
  `f_user` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`follow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}mensajes` (
  `mp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_answer` int(1) NOT NULL DEFAULT 0,
  `mp_date` int(10) NOT NULL DEFAULT 0,
  `mp_del_from` int(1) NOT NULL DEFAULT 0,
  `mp_del_to` int(1) NOT NULL DEFAULT 0,
  `mp_from` int(11) NOT NULL DEFAULT 0,
  `mp_preview` varchar(75) NOT NULL DEFAULT '',
  `mp_read_from` int(1) NOT NULL DEFAULT 1,
  `mp_read_mon_from` int(1) NOT NULL DEFAULT 1,
  `mp_read_mon_to` int(1) NOT NULL DEFAULT 0,
  `mp_read_to` int(1) NOT NULL DEFAULT 0,
  `mp_subject` varchar(50) NOT NULL DEFAULT '',
  `mp_to` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`mp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}miembros` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_activo` int(1) NOT NULL DEFAULT 0,
  `user_amigos` int(11) NOT NULL DEFAULT 0,
  `user_bad_hits` int(2) unsigned NOT NULL DEFAULT 0,
  `user_baneado` int(1) NOT NULL DEFAULT 0,
  `user_cache` int(10) NOT NULL DEFAULT 0,
  `user_comentarios` int(11) NOT NULL DEFAULT 0,
  `user_email` varchar(80) NOT NULL DEFAULT '',
  `user_verificado` int(1) NOT NULL DEFAULT 0,
  `user_chat` int(12) NOT NULL DEFAULT 0,
  `user_secret_2fa` text NULL,
  `user_recovery` text NULL,
  `user_last_ip` varchar(38) NOT NULL DEFAULT 0,
  `user_lastactive` int(10) NOT NULL DEFAULT 0,
  `user_lastlogin` int(10) NOT NULL DEFAULT 0,
  `user_lastpost` int(10) NOT NULL DEFAULT 0,
  `user_name_changes` int(11) NOT NULL DEFAULT 3,
  `user_name` varchar(24) NOT NULL DEFAULT '',
  `user_nextpuntos` int(10) NOT NULL DEFAULT 0,
  `user_password` varchar(66) NOT NULL DEFAULT '',
  `user_posts` int(11) NOT NULL DEFAULT 0,
  `user_puntos` int(6) unsigned NOT NULL DEFAULT 0,
  `user_puntosxdar` int(2) unsigned NOT NULL DEFAULT 0,
  `user_rango` int(3) NOT NULL DEFAULT 3,
  `user_registro` int(10) NOT NULL DEFAULT 0,
  `user_outtime_type` int(1) NOT NULL DEFAULT 0,
  `user_outtime_start` int(10) NOT NULL DEFAULT 0,
  `user_outtime` int(10) NOT NULL DEFAULT 0,
  `user_seguidores` bigint NOT NULL DEFAULT 0,
  `user_seguidos` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}miembros_social` (
  `social_id` int(11) NOT NULL AUTO_INCREMENT,
  `social_user_id` int(11) NOT NULL DEFAULT 0,
  `social_name` varchar(20) NOT NULL DEFAULT '',
  `social_nick` varchar(24) NOT NULL DEFAULT '',
  `social_email` varchar(80) NOT NULL DEFAULT '',
  `social_avatar` tinytext NOT NULL DEFAULT '',
  PRIMARY KEY (`social_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}nicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` int(1) NOT NULL DEFAULT 0,
  `hash` varchar(66) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `name_1` varchar(15) NOT NULL DEFAULT '',
  `name_2` varchar(15) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT 0,
  `user_email` varchar(80) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}monitor` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `not_date` int(10) NOT NULL DEFAULT 0,
  `not_menubar` int(1) NOT NULL DEFAULT 2,
  `not_monitor` int(1) NOT NULL DEFAULT 1,
  `not_total` int(2) NOT NULL DEFAULT 1,
  `not_type` varchar(26) NOT NULL DEFAULT '',
  `obj_dos` int(11) NOT NULL DEFAULT 0,
  `obj_tres` int(11) NOT NULL DEFAULT 0,
  `obj_uno` int(11) NOT NULL DEFAULT 0,
  `obj_user` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}muro` (
  `pub_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_body` text COLLATE utf8mb4_general_ci NULL,
  `p_comments` int(4) NOT NULL DEFAULT 0,
  `p_date` int(10) NOT NULL DEFAULT 0,
  `p_ip` varchar(50) NOT NULL DEFAULT '',
  `p_likes` int(4) NOT NULL DEFAULT 0,
  `p_nick` varchar(24) NOT NULL DEFAULT '',
  `p_type` int(1) NOT NULL DEFAULT 0,
  `p_update` int(10) NOT NULL DEFAULT 0,
  `p_user_pub` int(11) NOT NULL DEFAULT 0,
  `p_user` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}muro_adjuntos` (
  `adj_id` int(11) NOT NULL AUTO_INCREMENT,
  `adj_description` text NULL, # a_desc
  `adj_image` text NULL, # a_img
  `adj_title` varchar(100) NOT NULL DEFAULT '',
  `adj_url` text NULL,
  `adj_date` text NULL,
  `pub_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`adj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}muro_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `pub_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_body` text NULL,
  `c_likes` int(4) NOT NULL DEFAULT 0,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}muro_likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `obj_id` int(11) NOT NULL DEFAULT 0,
  `obj_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`like_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}perfil` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_dia` int(2) NOT NULL DEFAULT 0,
  `user_mes` int(2) NOT NULL DEFAULT 0,
  `user_ano` int(4) NOT NULL DEFAULT 0,
  `user_pais` varchar(2) NOT NULL DEFAULT '',
  `user_estado` int(2) NOT NULL DEFAULT 1,
  `user_sexo` varchar(9) NOT NULL DEFAULT 'none',
  `user_firma` text NULL,
  `user_gif` tinytext NULL,
  `user_gif_active` int(1) NOT NULL DEFAULT 0,
  `user_avatar_type` int(1) NOT NULL DEFAULT 0, /* Tipo gif, normal, social */
  `user_avatar_social` varchar(20) NOT NULL DEFAULT 'web', /* Nombre de red social */
  `user_portada` tinytext NULL,
  `user_scheme` int(1) NOT NULL DEFAULT 0,
  `user_color` int(2) NOT NULL DEFAULT 1,
  `user_customize` varchar(20) NOT NULL DEFAULT '#212121;#F4F4F4',
  `p_nombre` varchar(50) NOT NULL DEFAULT '',
  `p_avatar` int(1) NOT NULL DEFAULT 0,
  `p_mensaje` varchar(60) NOT NULL DEFAULT '',
  `p_sitio` varchar(60) NOT NULL DEFAULT '',
  `p_socials` text NULL,
  `p_configs` varchar(100) NOT NULL DEFAULT 'a:3:{s:1:\"m\";s:1:\"5\";s:2:\"mf\";i:5;s:3:\"rmp\";s:1:\"5\";}',
  `p_total` varchar(54) NOT NULL DEFAULT 'a:6:{i:0;i:5;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;}',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}portal` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `last_posts_visited` text NULL,
  `last_posts_shared` text NULL,
  `last_posts_cats` text NULL,
  `c_monitor` text NOT NULL DEFAULT 'f1,f2,f3,f8,f9,f4,f5,f10,f6,f7,f11,f12,f13,f14,f18',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}rangos` (
  `rango_id` int(3) NOT NULL AUTO_INCREMENT,
  `r_allows` varchar(1000) NOT NULL DEFAULT '',
  `r_cant` int(5) NOT NULL DEFAULT 0,
  `r_color` varchar(6) NOT NULL DEFAULT '171717',
  `r_image` varchar(32) NOT NULL DEFAULT 'new.png',
  `r_name` varchar(32) NOT NULL DEFAULT '',
  `r_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rango_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}rangos` (`rango_id`, `r_name`, `r_color`, `r_image`, `r_cant`, `r_allows`, `r_type`) VALUES
(1, 'Sigma', '405877', 'sigma.png', 0, 'a:4:{s:4:\"suad\";s:2:\"on\";s:4:\"goaf\";s:1:\"5\";s:5:\"gopfp\";s:2:\"20\";s:5:\"gopfd\";s:2:\"50\";}', 0),
(2, 'Alpha', '6A8F3F', 'alpha.png', 0, 'a:4:{s:4:\"sumo\";s:2:\"on\";s:4:\"goaf\";s:2:\"15\";s:5:\"gopfp\";s:2:\"18\";s:5:\"gopfd\";s:2:\"30\";}', 0),
(3, 'Delta', 'D18F00', 'delta.png', 0, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:1:\"5\";s:5:\"gopfd\";s:1:\"5\";}', 0),
(4, 'Beta', 'E2925A', 'beta.png', 50, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"10\";s:5:\"gopfd\";s:2:\"10\";}', 1),
(5, 'Gamma', '8F6B95', 'gamma.png', 70, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"12\";s:5:\"gopfd\";s:2:\"20\";}', 1),
(6, 'Omega', 'A54545', 'omega.png', 0, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"11\";s:5:\"gopfd\";s:2:\"15\";}', 0),
(7, 'Zeta', 'E1C29D', 'zeta.png', 120, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"12\";s:5:\"gopfd\";s:2:\"25\";}', 1);";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}respuestas` (
  `mr_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) NOT NULL DEFAULT 0,
  `mr_from` int(11) NOT NULL DEFAULT 0,
  `mr_body` text NULL,
  `mr_ip` varchar(50) NOT NULL DEFAULT '',
  `mr_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`mr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}sessions` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `session_user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `session_ip` varchar(50) NOT NULL DEFAULT '',
  `session_time` int(10) unsigned NOT NULL DEFAULT 0,
  `session_autologin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`session_id`),
  KEY `session_user_id` (`session_user_id`),
  KEY `session_time` (`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}suspension` (
  `susp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `susp_causa` text NULL,
  `susp_date` int(10) NOT NULL DEFAULT 0,
  `susp_termina` int(10) NOT NULL DEFAULT 0,
  `susp_mod` int(11) NOT NULL DEFAULT 0,
  `susp_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`susp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}afiliados` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `a_titulo` varchar(35) NOT NULL DEFAULT '',
  `a_url` tinytext NULL,
  `a_banner` tinytext NULL,
  `a_descripcion` varchar(200) NOT NULL DEFAULT '',
  `a_sid` int(11) NOT NULL DEFAULT 0,
  `a_hits_in` int(11) NOT NULL DEFAULT 0,
  `a_hits_out` int(11) NOT NULL DEFAULT 0,
  `a_date` int(10) NOT NULL DEFAULT 0,
  `a_active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}configuracion` (
  `tscript_id` int(11) NOT NULL DEFAULT 0,
  `titulo` varchar(30) NOT NULL DEFAULT '',
  `slogan` varchar(32) NOT NULL DEFAULT '',
  `url` tinytext NULL,
  `email` varchar(80) NOT NULL DEFAULT '',
  `banner` varchar(100) NOT NULL DEFAULT '',
  `tema_id` int(11) NOT NULL DEFAULT 1,
  `update_id` varchar(20) NOT NULL DEFAULT '',
  `c_allow_fuentes` int(1) NOT NULL DEFAULT 0,
  `c_avatar` int(1) NOT NULL DEFAULT 0,
  `leaving` int(1) NOT NULL DEFAULT 0,
  `ads_300` text NULL,
  `ads_468` text NULL,
  `ads_160` text NULL,
  `ads_728` text NULL,
  `ads_search` varchar(50) NOT NULL DEFAULT '',
  `c_last_active` int(2) NOT NULL DEFAULT 15,
  `c_allow_sess_ip` int(1) NOT NULL DEFAULT 1,
  `c_count_guests` int(1) NOT NULL DEFAULT 0,
  `c_reg_active` int(1) NOT NULL DEFAULT 1,
  `c_reg_activate` int(1) NOT NULL DEFAULT 1,
  `c_reg_rango` int(5) NOT NULL DEFAULT 3,
  `c_met_welcome` int(1) NOT NULL DEFAULT 0,
  `c_message_welcome` varchar(500) NOT NULL DEFAULT 'Hola [usuario], [welcome] a [b][web][/b].',
  `c_fotos_private` int(11) NOT NULL DEFAULT 0,
  `c_hits_guest` int(1) NOT NULL DEFAULT 0,
  `c_keep_points` int(1) NOT NULL DEFAULT 0,
  `c_allow_points` int(11) NOT NULL DEFAULT 0,
  `c_allow_edad` int(11) NOT NULL DEFAULT 16,
  `c_max_posts` int(2) NOT NULL DEFAULT 50,
  `c_max_com` int(3) NOT NULL DEFAULT 50,
  `c_max_nots` int(3) NOT NULL DEFAULT 99,
  `c_max_acts` int(3) NOT NULL DEFAULT 99,
  `c_newr_type` int(11) NOT NULL DEFAULT 0,
  `c_allow_sump` int(11) NOT NULL DEFAULT 0,
  `c_allow_firma` int(1) NOT NULL DEFAULT 1,
  `c_allow_upload` int(1) NOT NULL DEFAULT 0,
  `c_allow_portal` int(1) NOT NULL DEFAULT 1,
  `c_allow_live` int(1) NOT NULL DEFAULT 1,
  `c_see_mod` int(1) NOT NULL DEFAULT 0,
  `c_stats_cache` int(7) NOT NULL DEFAULT 15,
  `c_desapprove_post` int(1) NOT NULL DEFAULT 0,
  `offline` int(1) NOT NULL DEFAULT 0,
  `offline_message` varchar(255) NOT NULL DEFAULT 'Estamos en mantenimiento',
  `pkey` varchar(55) NOT NULL DEFAULT '',
  `skey` varchar(55) NOT NULL DEFAULT '',
  `version` varchar(30) NOT NULL DEFAULT '',
  `version_code` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`tscript_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}configuracion` (`tscript_id`) VALUES (1);";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}denuncias` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `d_date` int(10) NOT NULL DEFAULT 0,
  `d_extra` text NULL,
  `d_razon` int(2) NOT NULL DEFAULT 0,
  `d_total` int(1) NOT NULL DEFAULT 1,
  `d_type` int(1) NOT NULL DEFAULT 0,
  `d_user` int(11) NOT NULL DEFAULT 0,
  `obj_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_email` varchar(80) NOT NULL DEFAULT '',
  `time` int(15) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `hash` varchar(66) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}medallas` (
  `medal_id` int(11) NOT NULL AUTO_INCREMENT,
  `m_autor` int(11) NOT NULL DEFAULT 0,
  `m_cant` int(11) NOT NULL DEFAULT 0,
  `m_cond_foto` int(11) NOT NULL DEFAULT 0,
  `m_cond_post` int(11) NOT NULL DEFAULT 0,
  `m_cond_user_rango` int(11) NOT NULL DEFAULT 0,
  `m_cond_user` int(11) NOT NULL DEFAULT 0,
  `m_date` int(11) NOT NULL DEFAULT 0,
  `m_description` varchar(120) NOT NULL DEFAULT '',
  `m_image` varchar(120) NOT NULL DEFAULT '',
  `m_title` varchar(25) NOT NULL DEFAULT '',
  `m_total` int(11) NOT NULL DEFAULT 0,
  `m_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`medal_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}medallas_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medal_id` int(11) NOT NULL DEFAULT 0,
  `medal_for` int(11) NOT NULL DEFAULT 0,
  `medal_date` int(11) NOT NULL DEFAULT 0,
  `medal_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pofid` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `action` int(1) NOT NULL DEFAULT 0,
  `mod` int(11) NOT NULL DEFAULT 0,
  `reason` text NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `mod_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}noticias` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `not_body` text NULL,
  `not_autor` int(11) NOT NULL DEFAULT 0,
  `not_date` int(10) NOT NULL DEFAULT 0,
  `not_type` int(1) NOT NULL DEFAULT 0, # 0 Normal | 1 Importante | 2 Cambios
  `not_active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT 0,
  `value` varchar(50) NOT NULL DEFAULT '',
  `reason` varchar(120) NOT NULL DEFAULT '',
  `author` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}badwords` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(250) NOT NULL DEFAULT '',
  `swop` varchar(250) NOT NULL DEFAULT '',
  `method` int(1) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `author` int(11) NOT NULL DEFAULT 0,
  `reason` varchar(255) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}stats` (
  `stats_no` int(1) NOT NULL DEFAULT 0,
  `stats_max_online` int(11) NOT NULL DEFAULT 0,
  `stats_max_time` int(10) NOT NULL DEFAULT 0,
  `stats_time` int(10) NOT NULL DEFAULT 0,
  `stats_time_cache` int(10) NOT NULL DEFAULT 0,
  `stats_time_foundation` int(11) NOT NULL DEFAULT 0,
  `stats_time_upgrade` int(11) NOT NULL DEFAULT 0,
  `stats_miembros` int(11) NOT NULL DEFAULT 0,
  `stats_posts` int(11) NOT NULL DEFAULT 0,
  `stats_fotos` int(11) NOT NULL DEFAULT 0,
  `stats_comments` int(11) NOT NULL DEFAULT 0,
  `stats_foto_comments` int(11) NOT NULL DEFAULT 0,
  `stats_comunidades` int(11) NOT NULL DEFAULT 0,
  `stats_temas` int(11) NOT NULL DEFAULT 0,
  `stats_respuestas` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stats_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}stats` (`stats_no`, `stats_max_online`) VALUES (1, 0);";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}temas` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` tinytext NULL,
  `t_url` tinytext NULL,
  `t_path` tinytext NULL,
  `t_copy` tinytext NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT 0,
  `for` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX (`for`, `type`, `user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}social` (
  `social_id` int(11) NOT NULL AUTO_INCREMENT,
  `social_name` varchar(22) NOT NULL DEFAULT '',
  `social_client_id` tinytext NULL,
  `social_client_secret` tinytext NULL,
  `social_redirect_uri` tinytext NULL,
  `social_scope` tinytext NULL,
  `social_state` tinytext NULL,
  PRIMARY KEY (`social_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}seo` (
  `seo_id` int(11) NOT NULL  DEFAULT 0,
  `seo_titulo` varchar(60) NOT NULL DEFAULT '',
  `seo_descripcion` varchar(160) NOT NULL DEFAULT '',
  `seo_portada` tinytext NULL,
  `seo_favicon` tinytext NULL,
  `seo_keywords` text NULL,
  `seo_images` text NULL DEFAULT '',
  `seo_robots_data` text NULL DEFAULT '',
  `seo_robots` int(1) NULL DEFAULT 0,
  `seo_sitemap` int(1) NULL DEFAULT 0,
  PRIMARY KEY (`seo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}seo` (`seo_id`) VALUES (1);";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}sitemap` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `url` tinytext NOT NULL DEFAULT '',
  `frecuencia` varchar(15) NOT NULL DEFAULT '',
  `fecha` int(16) NOT NULL DEFAULT 0,
  `prioridad` decimal(2,1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}tickets` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_user` int(11) NOT NULL DEFAULT 0,
  `ticket_title` varchar(50) NOT NULL DEFAULT '',
  `ticket_body` text NULL,
  `ticket_type` int(11) NOT NULL DEFAULT 0,
  `ticket_status` int(1) NOT NULL DEFAULT 0,
  `ticket_date` int(15) NOT NULL DEFAULT 0,
  `ticket_updated` int(15) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}tickets_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_title` varchar(30) NOT NULL DEFAULT '',
  `type_icon` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}tickets_type` (`type_id`, `type_title`) VALUES (null, 'Avatar'), (null, 'Buscador'), (null, 'Comentarios'), (null, 'Cuenta'), (null, 'Fotos'), (null, 'Otro'), (null, 'Perfil'), (null, 'Portal'), (null, 'Posts');";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}tickets_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_title` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "INSERT INTO `{$db['prefix']}tickets_status` (`status_id`, `status_title`) VALUES (null, 'En espera'), (null, 'En curso'), (null, 'Realizado'), (null, 'Abandonado'), (null, 'Pausado'), (null, 'Cancelado');";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}chat` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `chat_lobby` int(11) NOT NULL DEFAULT 0,
  `chat_user` int(11) NOT NULL DEFAULT 0,
  `chat_message` text COLLATE utf8mb4_general_ci NULL,
  `chat_date` int(10) NOT NULL DEFAULT 0,
  `chat_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}chat_lobby` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `lobby_title` int(11) NOT NULL DEFAULT 0,
  `lobby_author` int(11) NOT NULL DEFAULT 0,
  `lobby_description` varchar(50) NOT NULL DEFAULT '',
  `lobby_private` int(1) NOT NULL DEFAULT 0,
  `lobby_guests` text NOT NULL DEFAULT '',
  `lobby_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$zcode_sql[] = "CREATE TABLE IF NOT EXISTS `{$db['prefix']}chat_blacklist` (
  `chat_ban_id` int(12) NOT NULL AUTO_INCREMENT,
  `chat_ban_user` int(12) NOT NULL DEFAULT 0,
  `chat_ban_expire` int(12) NOT NULL DEFAULT 0,
  `chat_ban_date` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`chat_ban_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";