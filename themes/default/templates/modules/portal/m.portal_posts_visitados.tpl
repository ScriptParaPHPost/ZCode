<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="browser"}
		</div>
		<div class="up-header--title">
			<span>&Uacute;ltimos posts visitados</span>
		</div>
	</div>
	<div class="up-card--body">
		{foreach from=$tsLastPostsVisited item=p}
			{if $p.post_title}
				<div class="categoriaPost w-100 p-1 mb-2 d-grid place-center column-gap-1" style="grid-template-columns:3rem 1fr;">
					<div class="portada avatar avatar-5 overflow-hidden rounded">
						<img src="{$p.post_portada.sm}" alt="{$p.post_title}" class="w-100 h-100">
					</div>
					<div class="dato">
						<a class="text-decoration-none fw-semibold text-truncate{if $p.post_private} privado{/if}" alt="{$p.post_title}" title="{$p.post_title}" target="_self" href="{$p.post_url}">{$p.post_title}</a>
						<small class="d-block">
							<a style="width: max-content;" href="{$tsConfig.url}/posts/{$p.c_seo}" class="badge main-bg d-flex justify-content-start align-items-center column-gap-1 text-decoration-none"><img src="{$p.c_img}" class="avatar avatar-1" alt="{$p.c_seo}"> {$p.c_nombre}</a>
						</small>
					</div>
				</div>
			{/if}
		{foreachelse}
			<div class="empty">No has visitado posts recientemente.</div>
		{/foreach}
	</div>
</section>