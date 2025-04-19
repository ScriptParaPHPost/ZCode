<?php

# Migración para la tabla `fotos_votos`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "fotos_votos` (\n"
		."  `vid` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `v_foto_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `v_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `v_pos` bigint NOT NULL DEFAULT 0,\n"
		."  `v_neg` bigint NOT NULL DEFAULT 0,\n"
		."  `v_date` int(11) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`vid`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
