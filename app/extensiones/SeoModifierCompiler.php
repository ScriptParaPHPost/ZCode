<?php

namespace app\extensiones;
use Smarty\Compile\Modifier\Base;

/**
 * Smarty seo modifier extension
 *
 * Type:     modifier
 * Name:     seo
 * Requires: PHP 8.2+
 * @author Miguel92
 * @param string
 * @return string
 */
class SeoModifierCompiler extends Base {

	public function compile($params, \Smarty\Compiler\Template $compiler) {
		return "trim(preg_replace('~[^\p{L}\p{N}]+~u', '-', mb_strtolower(html_entity_decode(mb_convert_encoding({$params[0]} ?? '', 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8'))), '-')";
   }

}