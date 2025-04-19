<?php

# Migración para la tabla `conexion_actual`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "conexion_actual` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  `session_id` varchar(100) NOT NULL DEFAULT '',\n"
		."  `last_activity` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`id`),\n"
		."  UNIQUE KEY (ip, session_id)\n"
		.") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;"
];
