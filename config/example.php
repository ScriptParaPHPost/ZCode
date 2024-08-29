<?php 

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * @name config.inc.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version __version__
 * @description Archivo de datos de conexión
**/

# ['hostname'] El nombre del host de tu servidor de base de datos.
$db['hostname'] = 'dbhost';

# ['username'] El nombre de usuario utilizado para conectarse a la base de datos.
$db['username'] = 'dbuser';

# ['password'] La contraseña utilizada para conectarse a la base de datos.
$db['password'] = 'dbpass';

# ['database'] El nombre de la base de datos a la que quieres conectarte.
$db['database'] = 'dbname';

# Puerto de conexión es opcional
$db['port'] = 'dbport';

# Por el momento no tiene funcionalidad
$db['installed'] = 'dbinstalled';

# Si cambia el prefijo tendrá problemas
$db['prefix'] = 'dbprefix';

/*
|	['msgs'] = false <No mostrara la pagina estatica>
|	['msgs'] = 1 <Mostrara la pagina estatica con descripcion breve para visitantes/usuarios y detalles para moderadores/administradores>
|	['msgs'] = 2 <Mostrara la pagina estatica con detalles para todos>
*/
$display['msgs'] = 1;