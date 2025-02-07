<picture class="picture overflow-hidden">
	{if isset($md)}<source srcset="{$tsConfig.logos.128}" data-srcset="{$md}" media="(min-width: 800px)">{/if}
	{if isset($lg)}<source srcset="{$tsConfig.logos.128}" data-srcset="{$lg}" media="(min-width: 400px)">{/if}
	<source srcset="{$tsConfig.logos.128}" data-srcset="{$src}">
	<img src="{$tsConfig.logos.128}" data-src="{$src}" loading="lazy"{if $alt} alt="{$alt}"{/if}{if $class} class="{$class}"{/if}>
</picture>