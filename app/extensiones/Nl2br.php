<?php

use Smarty\Compile\Modifier\Base;

/**
 * Smarty nl2br modifier extension
 * 
 * Type:     modifier
 * Name:     nl2br
 * Purpose:  Añade los saltos de línea
 * Requires: PHP 8.2+
 * @author Miguel92
 * @param string
 * @return string
 */
class Nl2brModifierCompiler extends Base {

   public function compile($params, \Smarty\Compiler\Template $compiler): string {
      // Generates PHP code for replacing specific HTML entities
		return "nl2br((string) {$params[ 0 ]})";
   }

}