<?php

namespace app\extensiones;
use Smarty\Compile\Modifier\Base;

/**
 * Smarty trim modifier extension
 *
 * Type:     modifier
 * Name:     trim
 * Purpose:  Eliminar los espacios de derecha e izquierda
 * Requires: PHP 8.2+
 * @link http://smarty.php.net/manual/en/language.modifier.trim.php trim (PHP online manual)
 * @author Miguel92
 * @param string
 * @return string
 */
class TrimModifierCompiler extends Base {

	public function compile($params, \Smarty\Compiler\Template $compiler) {
		return 'trim((string) ' . $params[ 0 ] . ')';
	}

}