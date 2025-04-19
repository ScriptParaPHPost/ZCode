<?php

# Migración para la tabla `afiliados`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "afiliados` (\n"
		."  `aid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `a_titulo` varchar(35) NOT NULL DEFAULT '',\n"
		."  `a_url` tinytext NULL,\n"
		."  `a_banner` tinytext NULL,\n"
		."  `a_descripcion` varchar(200) NOT NULL DEFAULT '',\n"
		."  `a_sid` int(11) NOT NULL DEFAULT 0,\n"
		."  `a_hits_in` int(11) NOT NULL DEFAULT 0,\n"
		."  `a_hits_out` int(11) NOT NULL DEFAULT 0,\n"
		."  `a_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `a_active` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`aid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
