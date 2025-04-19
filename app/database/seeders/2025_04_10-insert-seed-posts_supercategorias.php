<?php

# Seed de datos para la tabla `posts_supercategorias`

return [
	"INSERT INTO `" . $prefix . "posts_supercategorias` (`fid`, `super_nombre`, `super_descripcion`, `super_img`) VALUES\n"
		."(1, 'Oficial', 'Sección reservada para anuncios, novedades y reglas del foro. Aquí se publican actualizaciones importantes, normas de conducta y cualquier comunicación oficial de los administradores y moderadores. Un espacio clave para mantenerse al tanto de la vida del foro.', '1f4e2.svg'),\n"
		."(2, 'Tecnología', 'Espacio dedicado a las últimas innovaciones tecnológicas, gadgets, software y plataformas, donde los usuarios pueden compartir noticias, recursos y tutoriales.', '1f4bb.svg'),\n"
		."(3, 'Entretenimiento', 'Un lugar para disfrutar de todo lo relacionado con el ocio, desde animaciones y videojuegos hasta música, cómics y humor. Comparte y discute tus intereses favoritos.', '1f3aa.svg'),\n"
		."(4, 'Educación y Cultura', 'Sección orientada a la difusión del conocimiento, donde encontrarás materiales educativos, apuntes, arte y literatura para fomentar el aprendizaje y la creatividad.', '1f393.svg'),\n"
		."(5, 'Estilo de Vida', 'Aquí los usuarios pueden hablar sobre temas cotidianos que mejoran la calidad de vida, desde recetas y salud hasta viajes y hobbies como los autos y las mascotas.', '1f6c4.svg'),\n"
		."(6, 'Comunidad y Sociedad', 'Foro dedicado a temas que impactan a la comunidad, desde noticias y ecología hasta solidaridad y temas generales. Un espacio para debatir y compartir puntos de vista sobre el mundo que nos rodea.', '1f4ad.svg');"
];
