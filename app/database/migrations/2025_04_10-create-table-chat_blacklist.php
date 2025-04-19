<?php

# Migración para la tabla `chat_blacklist`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "chat_blacklist` (\n"
		."  `chat_ban_id` int(12) NOT NULL AUTO_INCREMENT,\n"
		."  `chat_ban_user` int(12) NOT NULL DEFAULT 0,\n"
		."  `chat_ban_expire` int(12) NOT NULL DEFAULT 0,\n"
		."  `chat_ban_date` int(12) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`chat_ban_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;"
];
