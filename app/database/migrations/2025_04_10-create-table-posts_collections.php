<?php

# Migración para la tabla `posts_collections`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_collections` (\n"
		."  `col_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `col_title` varchar(30) NOT NULL DEFAULT '',\n"
		."  `col_cover` tinytext NULL,\n"
		."  `col_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `col_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`col_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
