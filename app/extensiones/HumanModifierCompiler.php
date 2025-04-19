<?php

namespace app\extensiones;
use Smarty\Compile\Modifier\Base;

/**
 * Smarty huma modifier extension
 *
 * Type:     modifier
 * Name:     huma
 * Requires: PHP 8.2+
 * @author Miguel92
 * @param string
 * @return string
 */
class HumanModifierCompiler extends Base {

	public function compile($params, \Smarty\Compiler\Template $compiler) {
      return "({$params[0]} <= 0 ? '0' : (function(\$num) {
         \$abbrevs = ['', 'K', 'M', 'B', 'T'];
         \$factor = floor((strlen((string) \$num) - 1) / 3);
         \$short = \$factor === 0 ? \$num : \$num / (1000 ** \$factor);
         return \$factor === 0 ? \$num : (\$short == floor(\$short) ? floor(\$short) : sprintf('%.1f', \$short)) . \$abbrevs[\$factor];
      })({$params[0]}))";
   }

}