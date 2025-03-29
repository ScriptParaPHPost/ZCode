<picture class="picture overflow-hidden">
	{if is_array($src)}
		{if isset($src.lg)}<source srcset="{$tsRoutes.logos.128}" data-srcset="{$src.lg}" media="(min-width: 1200px)">{/if}
		{if isset($src.md)}<source srcset="{$tsRoutes.logos.128}" data-srcset="{$src.md}" media="(min-width: 800px)">{/if}
		{if isset($src.sm)}<source srcset="{$tsRoutes.logos.128}" data-srcset="{$src.sm}" media="(min-width: 400px)">{/if}
	{/if}
	{include "Avatar.tpl" src=$src alt=$alt class=$class}
</picture>