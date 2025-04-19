<?php

# Migración para la tabla `fotos_comentarios`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "fotos_comentarios` (\n"
		."  `cid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `c_foto_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `c_update` int(10) NOT NULL DEFAULT 0,\n"
		."  `c_body` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `c_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`cid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
