<?php

# Migración para la tabla `sitemap_control`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "sitemap_control` (\n"
		."  `sid` int(1) NOT NULL DEFAULT 0,\n"
		."  `register_post` int(1) NOT NULL DEFAULT 0,\n"
		."  `register_foto` int(1) NOT NULL DEFAULT 0,\n"
		."  `update_post` int(1) NOT NULL DEFAULT 0,\n"
		."  `update_foto` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`sid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
];
