<?php

# Migración para la tabla `sessions`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "sessions` (\n"
		."  `session_id` varchar(32) NOT NULL DEFAULT '',\n"
		."  `session_user_id` int(11) unsigned NOT NULL DEFAULT 0,\n"
		."  `session_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  `session_token` varchar(100) NOT NULL DEFAULT '',\n"
		."  `session_time` int(10) unsigned NOT NULL DEFAULT 0,\n"
		."  `session_autologin` tinyint(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`session_id`),\n"
		."  KEY `session_user_id` (`session_user_id`),\n"
		."  KEY `session_time` (`session_time`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
];
