<?php

# Migración para la tabla `avisos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "avisos` (\n"
		."  `av_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `av_body` text NULL,\n"
		."  `av_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `av_read` int(1) NOT NULL DEFAULT 0,\n"
		."  `av_subject` varchar(24) NOT NULL DEFAULT '',\n"
		."  `av_type` int(1) NOT NULL DEFAULT 0,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`av_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
