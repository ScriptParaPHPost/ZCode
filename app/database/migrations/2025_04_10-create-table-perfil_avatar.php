<?php

# Migración para la tabla `perfil_avatar`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "perfil_avatar` (\n"
		."  `uavatar_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `uavatar_gif` tinytext NULL,\n"
		."  `uavatar_gif_active` int(1) NOT NULL DEFAULT 0,\n"
		."  `uavatar_type` int(1) NOT NULL DEFAULT 0, /* Tipo gif, normal, social */\n"
		."  `uavatar_social` varchar(20) NOT NULL DEFAULT 'web', /* Nombre de red social */\n"
		."  `uavatar_use` varchar(32) NOT NULL DEFAULT '', /* Nombre del avatar actual */\n"
		."  PRIMARY KEY (`uavatar_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
];
