<?php

# Migración para la tabla `bloqueos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "bloqueos` (\n"
		."  `bid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `b_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `b_auser` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`bid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
