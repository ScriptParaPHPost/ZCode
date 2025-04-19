<?php

# Seed de datos para la tabla `tickets_status`

return [
	"INSERT INTO `" . $prefix . "tickets_status` (`status_id`, `status_title`, `status_slug`, `status_icon`) VALUES \n"
		."(null, 'En espera', 'en-espera', 'clock'), \n"
		."(null, 'En proceso', 'en-proceso', 'loader'), \n"
		."(null, 'Finalizado', 'finalizado', 'check'), \n"
		."(null, 'Abandonado', 'abandonado', 'no_sign'), \n"
		."(null, 'Pausado', 'pausado', 'refresh'), \n"
		."(null, 'Cancelado', 'cancelado', 'close');"
];
