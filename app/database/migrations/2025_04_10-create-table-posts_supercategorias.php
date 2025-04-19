<?php

# Migración para la tabla `posts_supercategorias`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_supercategorias` (\n"
		."  `fid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `super_nombre` varchar(60) NOT NULL DEFAULT '',\n"
		."  `super_descripcion` text NULL,\n"
		."  `super_color` varchar(40) NOT NULL DEFAULT '',\n"
		."  `super_img` varchar(40) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`fid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
