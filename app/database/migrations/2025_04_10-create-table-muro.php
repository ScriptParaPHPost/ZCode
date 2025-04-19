<?php

# Migración para la tabla `muro`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "muro` (\n"
		."  `pub_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `p_body` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `p_comments` int(4) NOT NULL DEFAULT 0,\n"
		."  `p_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `p_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  `p_likes` int(4) NOT NULL DEFAULT 0,\n"
		."  `p_nick` varchar(24) NOT NULL DEFAULT '',\n"
		."  `p_type` int(1) NOT NULL DEFAULT 0,\n"
		."  `p_update` int(10) NOT NULL DEFAULT 0,\n"
		."  `p_user_pub` int(11) NOT NULL DEFAULT 0,\n"
		."  `p_user` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`pub_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
