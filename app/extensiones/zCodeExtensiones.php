<?php 

use Smarty\Extension\Base;

require __DIR__ . DIRECTORY_SEPARATOR . 'GetUrl.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Human.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Nl2br.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Quot.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Seo.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Trim.php';

class zCodeExtensiones extends Base {

	public function getModifierCompiler(string $modifier): ?\Smarty\Compile\Modifier\ModifierCompilerInterface {

		return match ($modifier) {
			'getUrl' => new getUrlModifierCompiler(),
			'human' => new HumanModifierCompiler(),
			'nl2br' => new Nl2brModifierCompiler(),
			'quot' => new QuotModifierCompiler(),
			'seo' => new SeoModifierCompiler(),
			'trim' => new TrimModifierCompiler(),
			default => null
		};

	}

}