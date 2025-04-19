<?php

# Migración para la tabla `muro_adjuntos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "muro_adjuntos` (\n"
		."  `adj_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `adj_description` text NULL, # a_desc\n"
		."  `adj_image` text NULL, # a_img\n"
		."  `adj_title` varchar(100) NOT NULL DEFAULT '',\n"
		."  `adj_url` text NULL,\n"
		."  `adj_date` text NULL,\n"
		."  `pub_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`adj_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
