<?php

# Migración para la tabla `historial`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "historial` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `pofid` int(11) NOT NULL DEFAULT 0,\n"
		."  `type` int(1) NOT NULL DEFAULT 0,\n"
		."  `action` int(1) NOT NULL DEFAULT 0,\n"
		."  `mod` int(11) NOT NULL DEFAULT 0,\n"
		."  `reason` text NULL,\n"
		."  `date` int(11) NOT NULL DEFAULT 0,\n"
		."  `mod_ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
