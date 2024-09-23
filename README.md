![Repo peso](https://img.shields.io/github/repo-size/ScriptParaPHPost/ZCode?style=plastic&label=Repo%20peso)
![PHP 8](https://img.shields.io/badge/PHP-8.2.16-red?style=plastic)
![Smarty 4.5.4](https://img.shields.io/badge/Smarty-4.5.4-red?style=plastic)
![GitHub Release](https://img.shields.io/github/v/release/ScriptParaPHPost/ZCode?style=plastic&label=Branch%20main)
![GitHub Pre-Release](https://img.shields.io/github/v/release/ScriptParaPHPost/ZCode?include_prereleases&style=plastic&label=Branch%20develop)

# ZCode

### Actualizaciones
 * PHP 8.2+ 
 * Smarty 4.5.4
 * jQuery 3.7.1
 * Plugins de jQuery

Se han eliminado códigos completamente innecesarios, que no tenían uso alguno para el script, se modificaron funciones para mejorarlo.
Se añadió un pequeño plugin para smarty que la función que tiene es inspeccionar si existe un archivo ya sea .css o .js de la página en la que este el usuario, en caso de que exista la añade al html.

### INSTALL
Se hizo modificaciones en el instalador, tiene un nuevo paso extra en el que informará que si tiene las extensiones necesarias habilitadas o no, por ejemplo la extensión GD, si no esta habilitada no podrás subir imagenes al sitio. Si la extensión cURL no esta habilitada no podrá acceder a las url y poder obtener información de las misma.
Se agrego un archivo para realizar un mayor control sobre las acciones de insertar y/o actualizar los datos en la base de datos.

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