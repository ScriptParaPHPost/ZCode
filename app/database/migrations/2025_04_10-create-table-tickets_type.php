<?php

# Migración para la tabla `tickets_type`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "tickets_type` (\n"
		."  `type_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `type_title` varchar(30) NOT NULL DEFAULT '',\n"
		."  `type_icon` varchar(20) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`type_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
