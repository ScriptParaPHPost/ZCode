<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="picture"}</div>
		<div class="up-header--title">
			<span>&Uacute;ltimas fotos</span>
		</div>
	</div>
	<div class="up-card--body">
		<div class="fotos-content d-flex flex-wrap column-gap-3 p-2">
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