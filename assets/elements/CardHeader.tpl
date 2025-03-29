<div class="up-card--header" icon="{if isset($iconName)}true{else}false{/if}">
	<{if $link}a href="{$tsConfig.url}/{$link}" title="{$title}" {else}div {/if}{if $onclick} onclick="{$onclick}" {/if}class="up-header--icon">
		{uicon name=$iconName}
	</{if $link}a{else}div{/if}>
	<div class="up-header--title">
		<span>{$label}</span>
	</div>
	{if $total}<div class="up-header--icon">{$total}</div>{/if}
</div>