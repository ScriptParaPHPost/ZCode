<?php 

function smarty_function_checkbox($params, Smarty_Internal_Template $template) {
   // Genera un ID único en caso de no proveerse
   $uniq = uniqid();

   // Configuración predeterminada
   $default = [
      'name' => $params['name'] ?? $uniq,
      'id' => $params['id'] ?? 'lbl' . $uniq,
      'value' => $params['value'] ? ' value="'.$params['value'].'"' : '',
      'checked' => !empty($params['checked']) ? ' checked' : '',
      'label' => $params['label'] ?? 'Ingresa label',
      'optional' => $params['optional'] ?? ''
   ];

   // Genera el HTML dinámicamente
   $checkboxHTML = sprintf(
      '<div class="upform-check">
         <input type="checkbox" class="inp-cbx" name="%s" id="%s"%s%s />
         <label for="%s" class="cbx">
            <span><svg viewBox="0 0 12 10" height="10px" width="12px"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
            <span>%s%s</span>
         </label>
      </div>',
      $default['name'], // name
      $default['id'],   // id
      $default['value'],// value
      $default['checked'], // checked (si aplica)
      $default['id'],   // for etiqueta
      htmlspecialchars($default['label']), // texto del label
      "<small class=\"d-block\">" . htmlspecialchars_decode($default['optional']) . "</small>" // texto del label
   );

   // Retorna o imprime el bloque
   return $checkboxHTML;
}
