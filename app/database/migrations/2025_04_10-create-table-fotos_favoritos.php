<?php

# Migración para la tabla `fotos_favoritos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "fotos_favoritos` (\n"
		."  `fid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `f_foto_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `f_date` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`fid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
