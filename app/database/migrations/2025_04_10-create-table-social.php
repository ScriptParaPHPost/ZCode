<?php

# Migración para la tabla `social`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "social` (\n"
		."  `social_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `social_name` varchar(22) NOT NULL DEFAULT '',\n"
		."  `social_client_id` tinytext NULL,\n"
		."  `social_client_secret` tinytext NULL,\n"
		."  `social_redirect_uri` tinytext NULL,\n"
		."  PRIMARY KEY (`social_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
