<?php

# Migración para la tabla `posts_votos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "posts_votos` (\n"
		."  `voto_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `cant` int(11) NOT NULL DEFAULT 0,\n"
		."  `date` int(11) NOT NULL DEFAULT 0,\n"
		."  `tid` int(11) NOT NULL DEFAULT 0,\n"
		."  `tuser` int(11) NOT NULL DEFAULT 0,\n"
		."  `type` int(1) NOT NULL DEFAULT 1,\n"
		."  PRIMARY KEY (`voto_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
