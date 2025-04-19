<?php

# Migración para la tabla `miembros_social`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "miembros_social` (\n"
		."  `social_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `social_user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `social_name` varchar(20) NOT NULL DEFAULT '',\n"
		."  `social_nick` varchar(24) NOT NULL DEFAULT '',\n"
		."  `social_email` varchar(80) NOT NULL DEFAULT '',\n"
		."  `social_avatar` tinytext,\n"
		."  PRIMARY KEY (`social_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
