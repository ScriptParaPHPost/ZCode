<?php

# Migración para la tabla `posts_stats`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_stats` (\n"
		."  `sid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `stats_in` varchar(12) NOT NULL DEFAULT '', \n"
		."  `stats_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `stats_post_id` int(38) NOT NULL DEFAULT 0,\n"
		."  `stats_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`sid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
