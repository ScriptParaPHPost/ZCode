<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {uicon ...} 
 * Enlace: #
 * Fecha: Jul 1, 2023 4
 * Nombre: uicon
 * Proposito:
 * Tipo: function 
 * Version: 1.0 
*/

function smarty_function_uicon($params, &$smarty) {

   $icons_folder = TS_ASSETS . "icons" . TS_PATH;
   $folder = $params['folder'] ?? 'system-uicons';
   $classes = 'uicon-svg ' . ($params['class'] ?? '');
   $stroke = $params['stroke'] ?? "currentColor";
   $var = $params['var'] ?? '';
   $size = $params['size'] ?? '';
   
   $name = ($folder === 'spinner') ? $params['name'] : str_replace('-', '_', $params['name']);

	$icon_path = $icons_folder . $folder . TS_PATH . $name . '.svg';
   if(!file_exists($icon_path)) {
      $icon_path = $icons_folder . 'others' . TS_PATH . $name . '.svg';
   }
   $icon_content = file_get_contents($icon_path);
  
   // Buscamos el primer tag '<svg' para agregar las clases después de este
   $pos = strpos($icon_content, '<svg');
   if ($pos !== false) {
      $insert_pos = $pos + 4;
      $class_attr = ' class="' . trim(htmlspecialchars($classes)) . '"';
      $icon_content = substr_replace($icon_content, $class_attr, $insert_pos, 0);
      
      $attributes = ['role', 'style', 'title', 'fill'];
      foreach($attributes as $attr) {
         if(isset($params[$attr])) {
            $newAttr = trim(htmlspecialchars($params[$attr]));
            $addAttr = " $attr=\"$newAttr\"";
            $icon_content = substr_replace($icon_content, $addAttr, $insert_pos, 0);
         }
      }

      // Buscamos los atributos 'width' y 'height' y los actualizamos
      if (isset($params['size'])) {
         $size = trim(htmlspecialchars($params['size']));
         $size_attr = " width=\"$size\" height=\"$size\"";
         $icon_content = substr_replace($icon_content, $size_attr, $insert_pos, 0);
      }

      if(isset($params['attrs'])) {
         foreach($params['attrs'] as $attr => $newAttr) {
            $addAttr = " $attr=\"$newAttr\"";
            $icon_content = substr_replace($icon_content, $addAttr, $insert_pos, 0);
         }
      }
   }
   $icon_content = preg_replace('/(stroke)="[^"]*"/', '$1="' . $stroke . '"', $icon_content);

	return $icon_content;
}