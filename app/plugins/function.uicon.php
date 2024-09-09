<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {uicon ...} 
 * Enlace: #
 * Fecha: Jul 1, 2023 
 * Nombre: uicon
 * Proposito: Genera el código HTML para un ícono SVG a partir de un archivo JSON de iconos.
 * Tipo: function 
 * Version: 1.1 
 *
 * @param array $params Parámetros para configurar el ícono:
 *   - 'folder': Nombre de la carpeta del ícono dentro de la carpeta de iconos. Default es 'system-uicons'.
 *   - 'name': Nombre del ícono.
 *   - 'class': Clases CSS adicionales para el ícono.
 *   - 'stroke': Color del trazo. Default es "currentColor".
 *   - 'var': Atributo adicional para el ícono (no utilizado en la función, puede ser removido si no se usa).
 *   - 'size': Tamaño del ícono (se aplica a los atributos width y height).
 *   - 'role': Rol del ícono en HTML.
 *   - 'style': Estilo CSS adicional.
 *   - 'title': Título del ícono.
 *   - 'fill': Color de relleno.
 * @param object $smarty Instancia del objeto Smarty.
 * @return string Código HTML del ícono SVG con los atributos y clases configurados.
 */
function smarty_function_uicon(array $params, &$smarty): string {

   $icons_folder = TS_ASSETS . 'icons';
   $folder = $params['folder'] ?? 'system-uicons';
   $name = (in_array($folder, ['spinner', 'remix'])) ? $params['name'] : str_replace('-', '_', $params['name']);

   // Leer y decodificar los archivos JSON de íconos
   $extraer = json_decode(file_get_contents("$icons_folder/$folder.json"), true);
   $icon_path = $extraer[$name] ?? '';

   if (empty($icon_path)) {
      $others = json_decode(file_get_contents("$icons_folder/others.json"), true);
      $icon_path = $others[$name] ?? '';
   }

   // Atributos del ícono
   $classes = 'uicon-svg ' . ($params['class'] ?? '');
   $stroke = htmlspecialchars($params['stroke'] ?? 'currentColor');
   $size = htmlspecialchars($params['size'] ?? '');
   $attributes = [
      'role' => htmlspecialchars($params['role'] ?? ''),
      'style' => htmlspecialchars($params['style'] ?? ''),
      'title' => htmlspecialchars($params['title'] ?? ''),
      'fill' => htmlspecialchars($params['fill'] ?? '')
   ];

   // Insertar las clases y atributos en el código SVG
   if (str_contains((string) $icon_path, '<svg')) {
      $insert_pos = strpos((string) $icon_path, '<svg') + 4;
      $icon_path = substr_replace($icon_path, ' class="' . $classes . '"', $insert_pos, 0);
      
      foreach ($attributes as $attr => $value) {
         if (!empty($value)) {
            $icon_path = substr_replace($icon_path, " $attr=\"$value\"", $insert_pos, 0);
         }
      }

      if (!empty($size)) {
         $icon_path = preg_replace('/(width|height)="[^"]*"/', "$1=\"$size\"", $icon_path);
         if (!str_contains($icon_path, 'width')) {
            $icon_path = substr_replace($icon_path, " width=\"$size\" height=\"$size\"", $insert_pos, 0);
         }
      }
   }

   // Reemplazar el color del trazo
   $icon_path = preg_replace('/stroke="[^"]*"/', "stroke=\"$stroke\"", $icon_path);

   return $icon_path;
}
