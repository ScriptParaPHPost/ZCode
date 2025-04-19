<?php

# Migración para la tabla `posts_favoritos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_favoritos` (\n"
		."  `fav_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `fav_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `fav_post_id` int(38) NOT NULL DEFAULT 0,\n"
		."  `fav_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`fav_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
