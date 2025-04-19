<?php

# Migración para la tabla `stats`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "stats` (\n"
		."  `stats_no` int(1) NOT NULL DEFAULT 0,\n"
		."  `stats_max_online` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_max_time` int(10) NOT NULL DEFAULT 0,\n"
		."  `stats_time` int(10) NOT NULL DEFAULT 0,\n"
		."  `stats_time_cache` int(10) NOT NULL DEFAULT 0,\n"
		."  `stats_time_foundation` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_time_upgrade` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_miembros` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_posts` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_fotos` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_comments` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_foto_comments` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_comunidades` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_temas` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_respuestas` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`stats_no`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
];
