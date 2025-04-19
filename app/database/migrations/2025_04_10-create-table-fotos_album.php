<?php

# Migración para la tabla `fotos_album`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "fotos_album` (\n"
		."  `aid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `a_name` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',\n"
		."  `a_cover` tinytext NULL,\n"
		."  `a_description` tinytext COLLATE utf8mb4_general_ci NULL,\n"
		."  `a_status` int(1) NOT NULL DEFAULT 0,\n"
		."  `a_date` int(11) NOT NULL DEFAULT 0,\n"
		."  `a_update` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`aid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
