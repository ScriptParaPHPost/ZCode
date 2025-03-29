<section class="album up-card">
	{include "CardHeader.tpl" iconName="picture" label="{if $tsFUser.0 == $tsUser->uid}Mis fotos{else}Fotos de {$tsFUser.1}{/if}"}
	<div class="up-card--body">
		<div class="fotos-content">
			{foreach from=$tsFotos.data item=f}
				{assign "thisAlbum" true}
				{include "m.fotos_content_album.tpl"}
			{/foreach}
		</div>
	</div>
	<div class="up-card--footer">
		{if $tsFotos.pages.prev}<div style="display:block;margin: 5px 0; width: 110px;text-align:left" class="floatL before"><a href="{$tsConfig.url}/fotos/{$tsFUser.1}/{$tsFotos.pages.prev}">&laquo; Anterior</a></div>{/if}
		{if $tsFotos.pages.next}<div style="display:block;margin: 5px 0; width: 110px;text-align:right" class="floatR next"><a href="{$tsConfig.url}/fotos/{$tsFUser.1}/{$tsFotos.pages.next}">Siguiente &raquo;</a></div>{/if}
	</div>
</section>