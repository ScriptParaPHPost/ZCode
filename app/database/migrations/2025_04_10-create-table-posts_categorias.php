<?php

# Migración para la tabla `posts_categorias`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_categorias` (\n"
		."  `cid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `c_orden` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_foro` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_nombre` varchar(40) NOT NULL DEFAULT '',\n"
		."  `c_seo` varchar(40) NOT NULL DEFAULT '',\n"
		."  `c_img` varchar(40) NOT NULL DEFAULT '',\n"
		."  `c_color` varchar(40) NOT NULL DEFAULT '',\n"
		."  `c_descripcion` text NULL,\n"
		."  PRIMARY KEY (`cid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
