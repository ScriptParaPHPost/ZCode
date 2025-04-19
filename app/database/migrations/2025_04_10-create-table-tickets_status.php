<?php

# Migración para la tabla `tickets_status`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "tickets_status` (\n"
		."  `status_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `status_title` varchar(30) NOT NULL DEFAULT '',\n"
		."  `status_slug` varchar(30) NOT NULL DEFAULT '',\n"
		."  `status_icon` varchar(20) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`status_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
