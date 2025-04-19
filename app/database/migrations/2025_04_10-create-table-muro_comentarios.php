<?php

# Migración para la tabla `muro_comentarios`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "muro_comentarios` (\n"
		."  `cid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `pub_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `c_body` text NULL,\n"
		."  `c_likes` int(4) NOT NULL DEFAULT 0,\n"
		."  `c_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`cid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
