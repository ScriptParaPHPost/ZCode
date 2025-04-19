<?php

# Migración para la tabla `follows`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "follows` (\n"
		."  `follow_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `f_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `f_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_type` int(1) NOT NULL DEFAULT 0,\n"
		."  `f_user` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`follow_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
