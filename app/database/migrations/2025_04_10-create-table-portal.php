<?php

# Migración para la tabla `portal`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "portal` (\n"
		."  `user_id` int(11) NOT NULL DEFAULT 0,\n"
		."  `last_posts_visited` text NULL,\n"
		."  `last_posts_shared` text NULL,\n"
		."  `last_posts_cats` text NULL,\n"
		."  `c_monitor` varchar(255) NOT NULL DEFAULT 'f1,f2,f3,f8,f9,f4,f5,f10,f6,f7,f11,f12,f13,f14,f18,f19,20,f21',\n"
		."  PRIMARY KEY (`user_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
];
