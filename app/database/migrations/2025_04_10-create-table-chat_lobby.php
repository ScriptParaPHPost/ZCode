<?php

# Migración para la tabla `chat_lobby`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "chat_lobby` (\n"
		."  `lid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `lobby_title` int(11) NOT NULL DEFAULT 0,\n"
		."  `lobby_author` int(11) NOT NULL DEFAULT 0,\n"
		."  `lobby_description` varchar(50) NOT NULL DEFAULT '',\n"
		."  `lobby_private` int(1) NOT NULL DEFAULT 0,\n"
		."  `lobby_guests` text NULL,\n"
		."  `lobby_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`lid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
