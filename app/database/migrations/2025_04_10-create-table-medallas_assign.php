<?php

# Migración para la tabla `medallas_assign`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "medallas_assign` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `medal_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `medal_for` int(11) NOT NULL DEFAULT 0,\n"
		."  `medal_date` int(11) NOT NULL DEFAULT 0,\n"
		."  `medal_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
