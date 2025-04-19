<?php

# Migración para la tabla `fotos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "fotos` (\n"
		."  `foto_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `f_album` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_title` varchar(80) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',\n"
		."  `f_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `f_update` int(10) NOT NULL DEFAULT 0,\n"
		."  `f_description` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `f_url` tinytext NULL,\n"
		."  `f_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_closed` int(1) NOT NULL DEFAULT 0,\n"
		."  `f_visitas` bigint NOT NULL DEFAULT 0,\n"
		."  `f_status` int(1) NOT NULL DEFAULT 0,\n"
		."  `f_last` int(1) NOT NULL DEFAULT 0,\n"
		."  `f_hits` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`foto_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
