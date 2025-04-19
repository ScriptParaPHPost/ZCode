<?php

# Migración para la tabla `monitor`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "monitor` (\n"
		."  `not_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `not_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `not_menubar` int(1) NOT NULL DEFAULT 2,\n"
		."  `not_monitor` int(1) NOT NULL DEFAULT 1,\n"
		."  `not_total` int(2) NOT NULL DEFAULT 1,\n"
		."  `not_type` varchar(26) NOT NULL DEFAULT '',\n"
		."  `obj_dos` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_tres` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_uno` int(11) NOT NULL DEFAULT 0,\n"
		."  `obj_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`not_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
