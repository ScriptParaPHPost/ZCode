<?php

# Migración para la tabla `mensajes`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "mensajes` (\n"
		."  `mp_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `mp_answer` int(1) NOT NULL DEFAULT 0,\n"
		."  `mp_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `mp_del_from` int(1) NOT NULL DEFAULT 0,\n"
		."  `mp_del_to` int(1) NOT NULL DEFAULT 0,\n"
		."  `mp_from` int(11) NOT NULL DEFAULT 0,\n"
		."  `mp_preview` varchar(75) NOT NULL DEFAULT '',\n"
		."  `mp_read_from` int(1) NOT NULL DEFAULT 1,\n"
		."  `mp_read_mon_from` int(1) NOT NULL DEFAULT 1,\n"
		."  `mp_read_mon_to` int(1) NOT NULL DEFAULT 0,\n"
		."  `mp_read_to` int(1) NOT NULL DEFAULT 0,\n"
		."  `mp_subject` varchar(50) NOT NULL DEFAULT '',\n"
		."  `mp_to` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`mp_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
