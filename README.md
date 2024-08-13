# zCode
PHPost Risus es un sistema de compartimiento de enlaces que permite crear un sitio web similar a Taringa!
> Esta versión solo funcionará bien con PHP 8.2, se puede llegar a usar PHP 7+, pero tendrá algunos errores!

### Actualizaciones
 * PHP 8.2+ (obligatorio)
 * Smarty 3.5.2
 * jQuery 3.7.1
 * Plugins de jQuery

Se han eliminado códigos completamente innecesarios, que no tenían uso alguno para el script, se modificaron funciones para mejorarlo.
Se añadió un pequeño plugin para smarty que la función que tiene es inspeccionar si existe un archivo ya sea .css o .js de la página en la que este el usuario, en caso de que exista la añade al html.

### INSTALL
Se hizo modificaciones en el instalador, tiene un nuevo paso extra en el que informará que si tiene las extensiones necesarias habilitadas o no, por ejemplo la extensión GD, si no esta habilitada no podrás subir imagenes al sitio. Si la extensión cURL no esta habilitada no podrá acceder a las url y poder obtener información de las misma.
Se agrego un archivo para realizar un mayor control sobre las acciones de insertar y/o actualizar los datos en la base de datos.

### ¿Que es .env.example?
Este archivo hay que renombrarlo a `.env` y allí colocan su [token de github](https://github.com/settings/tokens?type=beta) y así poder acceder a los commits de una mejor manera, ya que sin el token las consultas a los token es limitada.

 * [Generan un nuevo token](https://github.com/settings/personal-access-tokens/new)
 * Asignan un nombre que deseen
 * **Expiration** eligen una fecha
 * y dan a generar token

No deben compartir el archivo .env, ni su token

### Mejoras
 * reCaptcha optimizado
 * Avatares una sola función (genera la url completa)
 * Sistema de generación de portadas
 * Contraseñas más seguras

### Modos
 * Diseño desde cero (con parte de bootstrap)
 * Modo oscuro/claro. 
 * Varios colores a elección.
 * Nuevo modal customizable y mucho más fácil de entender.
 * Registro/Login independientes.
 * Administración/Moderación independientes.
 * Fuentes, Iconos descargados en el script.
 * Funciones mejoradas y optimizadas.
 * Mejoras en las consultas a la base de datos.
 * Nuevo formato de mostrar errores.
 * Creación y verificación de contraseñas con password_hash y password_verify.
 * Cambiar avatar y/o usar gif como avatar mejorado ya que solo requiere de una variable para usarlo.
 * Avatar optimizado a webp.
 * Optimizaciones de las imágenes al subir portada al post [ x ]
 * Función para proteger email contra bots. ex: jhondoe@server.com => [EMAIL_PROTECTED] 
 * Simplificaciones de varias funciones en una o pocas funciones para reutilizar.

_Otras funciones que no he colocado ya que no son aplicadas, y muchas de otras funcionalidades que no se mencionaron pero están realizadas._