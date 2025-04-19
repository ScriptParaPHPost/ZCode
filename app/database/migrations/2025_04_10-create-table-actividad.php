<?php

# Migración para la tabla `actividad`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "actividad` (\n"
		."  `ac_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `ac_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `ac_type` int(2) NOT NULL DEFAULT 0,\n"
		."  `obj_dos` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_uno` int(11) NOT NULL DEFAULT 0,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`ac_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
