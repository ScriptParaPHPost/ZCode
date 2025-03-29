<?php

# Configuración de la base de datos
$faster = [
   "db" => [
      # Host de la base de datos
      "host" => "localhost",
      # Nombre de usuario de la base de datos
      "name" => "root",
      # Contraseña de la base de datos
      "pass" => "",
      # Nombre de la base de datos
      "base" => "zcode",
      # Prefijo de las tablas
      "prefix" => "zc_"
   ],
   # Configuración del sitio
   "site" => [
      # Título del sitio
      "titulo" => "ZCode",
      # Eslogan del sitio
      "slogan" => "Actualizando tu mundo",
      # Correo electrónico del sitio
      "email" => "example@noreply.com",
      # Descripción del sitio
      "description" => "Explora nuestra red social para compartir intereses y hacer nuevos amigos. ¡Únete ahora!",
      # Palabras clave del sitio
      "keywords" => "comunidad, conocer, ampliar, interaccion, compartir, intereses, encuentros, virtual",
      # Recaptcha v3 (google)
      "recaptcha" => [
         "public_key" => "6LfFFiMdAAAAAAQjDafWXZ0FeyesKYjVm4DSUoao",
         "secret_key" => "6LfFFiMdAAAAAFIP4oNFLQx5Fo1FyorTzNps8ChE"
      ]
   ],
   # Configuración del usuario administrador
   "admin" => [
      # Nombre de usuario del administrador
      "username" => "Anonymous",
      # Contraseña del administrador
      "password" => uniqid(),
      # Correo electrónico del administrador
      "email" => "example@host.com"
   ]
];