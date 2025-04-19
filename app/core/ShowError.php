<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

if ( ! defined('ZCODEV3')) exit('No direct script access allowed');

/**
 * Mostrar error con diseño comprimido y agradable en pantalla
 */
function ShowError($error = 'Indefinido', $type = 'db', $info = []) {
   global $mysqli, $tsUser, $display, $e;

   $table = '';
   if($type === 'db') {
      $extra = [];

      if ($tsUser->is_admod || (int)$display['msgs'] === 2) {
         $err = $e->getMessage() ?? $mysqli->error;
         $extra[] = "<tr><td colspan=\"2\"><p class=\"warning\">$err</p></td></tr>";
      }
      if (isset($info['file'])) {
         $extra[] = "<tr><td>Archivo</td><td>{$info['file']}</td></tr>";
      }
      if (isset($info['line'])) {
         $extra[] = "<tr class=\"alt\"><td>Línea</td><td>{$info['line']}</td></tr>";
      }
      if (isset($info['query']) && ($tsUser->is_admod || (int)$display['msgs'] === 2)) {
         $extra[] = "<tr><td colspan=\"2\"><pre><code>{$info['query']}</code></pre></td></tr>";
      }
      $table = '<table border="0"><tbody>' . implode('', $extra) . '</tbody></table>';
   }

  	require_once __DIR__ . '/ErrorTemplate.php';
   die;
}