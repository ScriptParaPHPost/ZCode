<?php

# Migración para la tabla `posts_comentarios`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_comentarios` (\n"
		."  `cid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `c_post_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `c_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `c_update` int(10) NOT NULL DEFAULT 0,\n"
		."  `c_body` text NULL,\n"
		."  `c_reaccion` enum('','like','love','haha','wow','sad','angry') NOT NULL,\n"
		."  `c_status` int(1) NOT NULL DEFAULT 0,\n"
		."  `c_answer` int(1) NOT NULL DEFAULT 0,\n"
		."  `c_answer_cid` int(1) NOT NULL DEFAULT 0,\n"
		."  `c_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`cid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
