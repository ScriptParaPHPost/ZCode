<?php

# Seed de datos para la tabla `tickets_type`

return [
	"INSERT INTO `" . $prefix . "tickets_type` (`type_id`, `type_title`, `type_icon`) VALUES \n"
		."(null, 'Avatar', 'face_happy'), \n"
		."(null, 'Buscador', 'search'), \n"
		."(null, 'Comentarios', 'thread'), \n"
		."(null, 'Cuenta', 'window_content'), \n"
		."(null, 'Fotos', 'camera_alt'), \n"
		."(null, 'Otro', 'frame'), \n"
		."(null, 'Perfil', 'fingerprint'), \n"
		."(null, 'Portal', 'directions'), \n"
		."(null, 'Posts', 'browser');"
];
