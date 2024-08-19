<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--title">
			<span>Fotos de {$tsFoto.user_name}</span>
		</div>
	</div>
	<div class="up-card--body">
		{foreach from=$tsUltimasFotos item=f}
			<a href="{$f.foto_url}" class="d-block m-1 overflow-hidden position-relative">
				<img src="{$f.f_url}" title="{$f.f_title}" class="w-100 rounded object-fit-cover" style="height: 100px;" loading="lazy" />
				<span class="time position-absolute up-badge" style="top: 0.5rem;left: 0.5rem;">{$f.f_date|hace:true}</span>
			</a>
		{foreachelse}
			<div class="empty">Que estas esperando para afiliate a nuestro sitio!</div>
		{/foreach}
	</div>
	<div class="up-card--footer">
		<a href="{$tsConfig.url}/fotos/{$tsFoto.user_name}" class="fw-semibold d-block text-center">Ver todas</a>
	</div>
</section>
{include "m.global_ads_160.tpl"}