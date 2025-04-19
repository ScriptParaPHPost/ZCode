<?php

# Migración para la tabla `rangos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "rangos` (\n"
		."  `rango_id` int(3) NOT NULL AUTO_INCREMENT,\n"
		."  `r_allows` varchar(1000) NOT NULL DEFAULT '',\n"
		."  `r_cant` int(5) NOT NULL DEFAULT 0,\n"
		."  `r_color` varchar(12) NOT NULL DEFAULT '171717',\n"
		."  `r_image` varchar(32) NOT NULL DEFAULT 'new.png',\n"
		."  `r_name` varchar(32) NOT NULL DEFAULT '',\n"
		."  `r_type` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`rango_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
