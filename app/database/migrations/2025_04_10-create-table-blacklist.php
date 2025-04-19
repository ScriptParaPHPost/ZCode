<?php

# Migración para la tabla `blacklist`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "blacklist` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `type` int(1) NOT NULL DEFAULT 0,\n"
		."  `value` varchar(50) NOT NULL DEFAULT '',\n"
		."  `reason` varchar(120) NOT NULL DEFAULT '',\n"
		."  `author` int(11) NOT NULL DEFAULT 0,\n"
		."  `date` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1;"
];
