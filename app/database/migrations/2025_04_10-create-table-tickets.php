<?php

# Migración para la tabla `tickets`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "tickets` (\n"
		."  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,\n"
		."  `ticket_user` int(11) NOT NULL DEFAULT 0,\n"
		."  `ticket_title` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',\n"
		."  `ticket_body` text COLLATE utf8mb4_general_ci NULL,\n"
		."  `ticket_type` int(11) NOT NULL DEFAULT 0,\n"
		."  `ticket_status` int(1) NOT NULL DEFAULT 0,\n"
		."  `ticket_date` int(15) NOT NULL DEFAULT 0,\n"
		."  `ticket_updated` int(15) NOT NULL DEFAULT 0,\n"
		."  PRIMARY KEY (`ticket_id`),\n"
		."  FULLTEXT (`ticket_title`, `ticket_body`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;"
];
