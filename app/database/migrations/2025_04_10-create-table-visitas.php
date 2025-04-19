<?php

# Migración para la tabla `visitas`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "visitas` (\n"
		."  `id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `user` int(11) NOT NULL DEFAULT 0,\n"
		."  `for` int(11) NOT NULL DEFAULT 0,\n"
		."  `type` int(1) NOT NULL DEFAULT 0,\n"
		."  `date` int(11) NOT NULL DEFAULT 0,\n"
		."  `ip` varchar(50) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`id`),\n"
		."  INDEX (`for`, `type`, `user`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
