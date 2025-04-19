<section class="lastPosts up-card">
	{include "CardHeader.tpl" iconName="episodes" label="Afiliados"}
	<div class="up-card--body up-card--afiliados overflow-hidden" style="max-height: 258px;">
		{foreach from=$tsAfiliados item=afiliado}
			<div role="button" class="item d-block my-2 rounded shadow" onclick="afiliado.detalles({$afiliado.aid}, 'home'); return false;" title="{$afiliado.a_titulo}">
				<img loading="lazy" src="{$afiliado.a_banner}" alt="{$afiliado.a_titulo}"/>
			</div>
		{foreachelse}
			<div class="empty">Que estas esperando para afiliate a nuestro sitio!</div>
		{/foreach}
	</div>
	<div class="up-card--footer">
		<span class="btn" ole="button" onclick="afiliado.nuevo('home'); return false">Afiliarme ahora...</span>
	</div>
</section>