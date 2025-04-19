<?php

# Migración para la tabla `noticias`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "noticias` (\n"
		."  `not_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `not_body` text NULL,\n"
		."  `not_autor` int(11) NOT NULL DEFAULT 0,\n"
		."  `not_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `not_type` int(1) NOT NULL DEFAULT 0, # 0 Normal | 1 Importante | 2 Cambios\n"
		."  `not_active` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`not_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
