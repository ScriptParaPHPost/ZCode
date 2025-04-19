<?php

# Migración para la tabla `sitemap`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "sitemap` (\n"
		."  `id` int(3) NOT NULL AUTO_INCREMENT,\n"
		."  `url` tinytext NULL,\n"
		."  `frecuencia` varchar(15) NOT NULL DEFAULT '',\n"
		."  `fecha` int(16) NOT NULL DEFAULT 0,\n"
		."  `prioridad` decimal(2,1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
];
