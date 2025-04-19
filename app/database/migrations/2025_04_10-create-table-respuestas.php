<?php

# Migración para la tabla `respuestas`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "respuestas` (\n"
		."  `mr_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `mp_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `mr_from` int(11) NOT NULL DEFAULT 0,\n"
		."  `mr_body` text NULL,\n"
		."  `mr_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  `mr_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`mr_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
