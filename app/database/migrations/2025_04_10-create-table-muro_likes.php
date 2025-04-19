<?php

# Migración para la tabla `muro_likes`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "muro_likes` (\n"
		."  `like_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_type` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`like_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
