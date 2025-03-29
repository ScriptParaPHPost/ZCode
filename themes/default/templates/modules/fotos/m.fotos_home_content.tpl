<section class="up-card">
	{include "CardHeader.tpl" iconName="picture" label="&Uacute;ltimas fotos"}
	<div class="up-card--body p-2">
		<div class="fotos-content">
			{foreach from=$tsLastFotos.data item=f}
				{assign "thisAlbum" false}
				{include "m.fotos_content_album.tpl"}
			{/foreach}
		</div>
	</div>
	<div class="up-card--footer">
		{if $tsLastFotos.data > 10}P&aacute;ginas: {$tsLastFotos.pages}{/if}
	</div>
</section>