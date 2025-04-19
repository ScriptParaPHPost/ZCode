<?php

# Migración para la tabla `contacts`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "contacts` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `user_email` varchar(80) NOT NULL DEFAULT '',\n"
		."  `time` int(15) NOT NULL DEFAULT 0,\n"
		."  `type` int(1) NOT NULL DEFAULT 0,\n"
		."  `hash` varchar(66) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
