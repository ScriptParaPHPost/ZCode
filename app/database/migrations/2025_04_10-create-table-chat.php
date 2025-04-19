<?php

# Migración para la tabla `chat`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "chat` (\n"
		."  `cid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `chat_lobby` int(11) NOT NULL DEFAULT 0,\n"
		."  `chat_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `chat_message` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `chat_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `chat_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`cid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
