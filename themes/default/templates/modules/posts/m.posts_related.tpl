<section class="up-card positio-sticky mt-0 top-0">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="document_list"}
		</div>
		<div class="up-header--title">
			<span>Posts relacionado</span>
		</div>
	</div>
	<div class="up-card--body">
		{foreach from=$tsRelated item=p}
			<div class="categoriaPost{if $p.post_private} private{/if} d-grid gap-2 shadow rounded my-2 rounded overflow-hidden" style="grid-template-columns:75px 1fr;">
				<div class="cover ratio ratio-1x1">
			      <img src="{$p.post_portada.sm}" data-src="{$p.post_portada.lg}" alt="{$p.post_title}" class="w-100 h-100 object-fit-cover">
			   </div>
				<div class="post text-truncate d-flex justify-content-center align-items-start flex-column p-2">
					<a class="d-block text-truncate w-100 text-decoration-none fw-semibold" title="{$p.post_title}" href="{$p.post_url}">{$p.post_title}</a>
					<a class="small text-decoration-none d-flex justify-content-start align-items-center gap-2"><img src="{$p.c_img}" alt="{$p.c_nombre}" class="avatar avatar-1">{$p.c_nombre}</a>
				</div>
			</div>
		{foreachelse}
			<div class="empty">No se encontraron posts relacionados.</div>
		{/foreach}
	</div>
</section>