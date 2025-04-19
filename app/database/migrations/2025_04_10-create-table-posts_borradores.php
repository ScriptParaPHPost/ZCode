<?php

# Migración para la tabla `posts_borradores`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_borradores` (\n"
		."  `bid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `b_block_comments` int(1) NOT NULL DEFAULT 0,\n"
		."  `b_body` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `b_category` int(4) NOT NULL DEFAULT 0,\n"
		."  `b_causa` varchar(128) NOT NULL DEFAULT '',\n"
		."  `b_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `b_portada` tinytext NULL,\n"
		."  `b_post_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `b_private` int(1) NOT NULL DEFAULT 0,\n"
		."  `b_smileys` int(1) NOT NULL DEFAULT 0,\n"
		."  `b_sponsored` int(1) NOT NULL DEFAULT 0,\n"
		."  `b_status` int(1) NOT NULL DEFAULT 1,\n"
		."  `b_sticky` int(1) NOT NULL DEFAULT 0,\n"
		."  `b_tags` varchar(128) DEFAULT NULL,\n"
		."  `b_fuentes` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `b_title` varchar(120) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',\n"
		."  `b_update` int(10) NOT NULL DEFAULT 0,\n"
		."  `b_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `b_visitantes` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`bid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1 ;"
];
