<a 
	title="{$title}" 
	class="up-menu--link text-decoration-none rounded position-relative py-3 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $inPage} active{/if}" 
	rel="internal" 
	href="{$tsConfig.url}{$link}"
	{if $dropopen} data-dropopen="{$dropopen}"{/if}
>
	{uicon name=$icon size="1.5rem"}
	<span class="item--text">{$label}</span>
</a>