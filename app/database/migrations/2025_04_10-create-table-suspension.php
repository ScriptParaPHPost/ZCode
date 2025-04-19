<?php

# Migración para la tabla `suspension`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "suspension` (\n"
		."  `susp_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `susp_causa` text NULL,\n"
		."  `susp_date` int(10) NOT NULL DEFAULT 0,\n"
		."  `susp_termina` int(10) NOT NULL DEFAULT 0,\n"
		."  `susp_mod` int(11) NOT NULL DEFAULT 0,\n"
		."  `susp_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`susp_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
