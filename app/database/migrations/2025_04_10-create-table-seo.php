<?php

# Migración para la tabla `seo`

return [
	"CREATE TABLE IF NOT EXISTS `" . $prefix . "seo` (\n"
		."  `seo_id` int(11) NOT NULL  DEFAULT 0,\n"
		."  `seo_titulo` varchar(60) NOT NULL DEFAULT '',\n"
		."  `seo_descripcion` varchar(160) NOT NULL DEFAULT '',\n"
		."  `seo_portada` tinytext NULL,\n"
		."  `seo_keywords` text NULL,\n"
		."  `seo_robots` int(1) NULL DEFAULT 0,\n"
		."  `seo_robots_data` varchar(200) NOT NULL DEFAULT '',\n"
		."  `seo_sitemap` int(1) NULL DEFAULT 0,\n"
		."  `seo_google_verification` varchar(60) NULL DEFAULT '',\n"
		."  `seo_google_verification_active` int(1) NULL DEFAULT 0,\n"
		."  `seo_google_analytics` varchar(20) NOT NULL DEFAULT '',\n"
		."  PRIMARY KEY (`seo_id`)\n"
		.") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
];
