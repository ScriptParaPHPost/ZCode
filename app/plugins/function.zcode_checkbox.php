<?php 

function smarty_function_zcode_checkbox($params) {
    // Genera un ID único en caso de no proveerse
    $uniq = uniqid();

    // Configuración predeterminada
    $default = [
        'name' => $uniq,
        'id' => 'lbl' . $uniq,
        'value' => '',
        'checked' => '',
        'label' => 'Ingresa label',
        'optional' => ''
    ];

    // Combina los parámetros proporcionados con los predeterminados
    $params = array_merge($default, $params);

    // Escapa las variables adecuadamente
    $params['name'] = htmlspecialchars($params['name']);
    $params['id'] = htmlspecialchars($params['id']);
    $params['value'] = $params['value'] ? ' value="' . htmlspecialchars($params['value']) . '"' : '';
    $params['checked'] = !empty($params['checked']) ? ' checked' : '';
    $params['label'] = htmlspecialchars($params['label']);
    $params['optional'] = htmlspecialchars_decode($params['optional']);

    // Genera el HTML dinámicamente
    $checkboxHTML = sprintf(
        '<div class="upform-check">
            <input type="checkbox" class="inp-cbx" name="%s" id="%s"%s%s />
            <label for="%s" class="cbx">
                <span><svg viewBox="0 0 12 10" height="10px" width="12px"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
                <span>%s%s</span>
            </label>
        </div>',
        $params['name'], // name
        $params['id'],   // id
        $params['value'],// value
        $params['checked'], // checked (si aplica)
        $params['id'],   // for etiqueta
        $params['label'], // texto del label
        "<small class=\"d-block\">" . $params['optional'] . "</small>" // texto del label
    );

    return $checkboxHTML;
}