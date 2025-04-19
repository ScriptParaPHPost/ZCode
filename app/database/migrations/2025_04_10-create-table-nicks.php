<?php

# Migración para la tabla `nicks`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "nicks` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `estado` int(1) NOT NULL DEFAULT 0,\n"
		."  `hash` varchar(66) NOT NULL DEFAULT '',\n"
		."  `ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  `name_1` varchar(15) NOT NULL DEFAULT '',\n"
		."  `name_2` varchar(15) NOT NULL DEFAULT '',\n"
		."  `time` int(11) NOT NULL DEFAULT 0,\n"
		."  `user_email` varchar(80) NOT NULL DEFAULT '',\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
