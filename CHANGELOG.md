# Todos los cambios en zCode.

- Redefiniciones de funciones y variables
- Actualizaciones de imagenes para usar svg, webp
- Actualizaciones de plugins para jquery
- Actualización de QueryString.php
- Actualización de Smarty 4.5.3
- Actualización del diseño del wysibb
- Archivo .env
- Archivo live.js reescrito
- Creación desde 0 del theme
- Fuentes incluidas
- Independientes: Login, Registro, Administracion/Moderación
- Mejoras en la configuración de Smarty
- Mejoras en las funciones PHP (php 8.2+)
- Mejoras en la función al crear y verificar contraseña
- Nuevas modificaciones a database.php (solo install)
- Nuevas consultas añadidas a database.php
- Función que simplifica la forma de usar avatar
	* Avatar normal
	* Avatar gif
	* Selección de avatares predefinidos
- Separaciones de códigos
- Códigos reescritos (algunas funciones)
- Publicar imágen en muro desde el portapapeles (CTRL + V)
- Simplificación al crear publicación en muro (sin tanto código)
- Plugin lite-youtube actualizado
- Import en los js, evita carga de código sin usar (experimental, solo carga al usar dicha función)
- Filtro en perfil

# Nuevo
- Páginas de error 401, 403, 404 (.html)
- Librería para portadas de los posts (*)
	* Crea una carpeta ID automatizado
	* El id es generado con la ID del post
	* Crea 3 imágenes 120x90, 240x180, 480x270
- Librería OpenGraph para obtener datos de url (*)
	* Titulo
	* Imagenes
	* Descripción
- reCaptcha.php (*)
- Elegir color y/o modo para el theme, guardado automático
- Modal customizable (*)
- Archivo de rutas
- Archivo example.config.php (Solo lo usa al instalar)
- Plugin (meta): function => Para el control de las etiquetas en el `<head>...</head>`
- Plugin (zCode): function => Para el uso en los themes
- Plugin (uicon): function => Para el uso de Iconos SVG
- Plugin (human): function => Para convertir números ej: 1000 => 1K (+)
- Plugin (protected_mail): modifier => Para proteger correo de SPAM
- Consultas con nuevo prefijo para las tablas @nombre_tabla
	* Se quitaron f_, p_, u_, w_, etc.
- Administrar base de datos (Analizar, Optimizar, Reparar, Comprobar, Crear backup)

> (*) Quiere decir que los cree desde cero!
> (+) Similares a otro existente, pero no iguales!

# Posiblemente
- Librería el control de crear avatares (*)
	* Crea una carpeta ID automatizado
	* Guardará todas los avatares subidos por el usuario

# Se eliminarón
- CDN, todo será de forma local