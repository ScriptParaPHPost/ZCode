<?php

# Migración para la tabla `denuncias`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "denuncias` (\n"
		."  `did` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `d_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `d_extra` text NULL,\n"
		."  `d_razon` int(2) NOT NULL DEFAULT 0,\n"
		."  `d_total` int(1) NOT NULL DEFAULT 1,\n"
		."  `d_type` int(1) NOT NULL DEFAULT 0,\n"
		."  `d_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`did`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
