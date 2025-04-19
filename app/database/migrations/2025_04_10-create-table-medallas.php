<?php

# Migración para la tabla `medallas`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "medallas` (\n"
		."  `medal_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `m_autor` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_cant` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_cond_foto` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_cond_post` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_cond_user_rango` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_cond_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_date` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_description` varchar(120) NOT NULL DEFAULT '',\n"
		."  `m_image` varchar(120) NOT NULL DEFAULT '',\n"
		."  `m_title` varchar(25) NOT NULL DEFAULT '',\n"
		."  `m_total` int(11) NOT NULL DEFAULT 0,\n"
		."  `m_type` int(1) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`medal_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
