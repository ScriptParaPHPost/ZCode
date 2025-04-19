<?php

# Migración para la tabla `comentarios_reaccion`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "comentarios_reaccion` (\n"
		."  `rid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `r_comment_id` int(11) NOT NULL DEFAULT 0, \n"
		."  `r_user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `r_reaction` varchar(10) NOT NULL DEFAULT '',\n"
		."  `r_date` int(10) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`rid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
