<?php 

namespace app\extensiones;

use Smarty\Extension\Base;
use app\extensiones\{
	GetUrlModifierCompiler,
	HumanModifierCompiler,
	Nl2brModifierCompiler,
	QuotModifierCompiler,
	SeoModifierCompiler,
	TrimModifierCompiler
};

class zCodeExtensiones extends Base {

	public function getModifierCompiler(string $modifier): ?\Smarty\Compile\Modifier\ModifierCompilerInterface {

		return match ($modifier) {
			'getUrl' => new GetUrlModifierCompiler,
			'human' => new HumanModifierCompiler,
			'nl2br' => new Nl2brModifierCompiler,
			'quot' => new QuotModifierCompiler,
			'seo' => new SeoModifierCompiler,
			'trim' => new TrimModifierCompiler,
			default => null
		};

	}

}