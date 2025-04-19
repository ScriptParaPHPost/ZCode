<?php

namespace BBCode\validators;

use JBBCode\InputValidator;

/**
 * Un InputValidator para valores de tamaño de texto válidos
 *
 * @author Kmario19
 * @since Jul 2015
 */
class SizeValidator implements InputValidator {

    /**
     * Retorna true si $input es un número
     *
     * @param $input numero para validar
     */
    public function validate($input) {
        return is_numeric($input) && $input > 0;
    }

}
