<?php

# Migración para la tabla `badwords`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "badwords` (\n"
		."  `wid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `word` varchar(250) NOT NULL DEFAULT '',\n"
		."  `swop` varchar(250) NOT NULL DEFAULT '',\n"
		."  `method` int(1) NOT NULL DEFAULT 0,\n"
		."  `type` int(1) NOT NULL DEFAULT 0,\n"
		."  `author` int(11) NOT NULL DEFAULT 0,\n"
		."  `reason` varchar(255) NOT NULL DEFAULT '',\n"
		."  `date` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`wid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci AUTO_INCREMENT=1;"
];
