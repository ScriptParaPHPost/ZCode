<?php

namespace app\extensiones;
use Smarty\Compile\Modifier\Base;

/**
 * Smarty quot modifier extension
 * 
 * Type:     modifier
 * Name:     quot
 * Purpose:  Reemplaza entidades HTML específicas con sus respectivos caracteres.
 * Requires: PHP 8.2+
 * @author Miguel92
 * @param string
 * @return string
 */
class QuotModifierCompiler extends Base {

   public function compile($params, \Smarty\Compiler\Template $compiler): string {
      // Generates PHP code for replacing specific HTML entities
		return 'str_replace(\'&amp;\', \'&#039;\'], ['&', "\'"], (string) ' . $params[ 0 ] . ')';
   }

}