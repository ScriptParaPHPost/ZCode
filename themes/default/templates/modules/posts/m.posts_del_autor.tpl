{if $tsPostAutor}
<section class="up-card">
	{include "CardHeader.tpl" iconName="document_stack" label="Más posts del autor"}
	<div class="up-card--body">
	 	<div class="slider d-flex gap-3 p-3 overflow-x-scroll">
			{foreach $tsPostAutor item=p}
				<div class="rounded shadow-sm slider__slides d-flex flex-column flex-shrink-0 position-relative overflow-hidden">
					{include "Picture.tpl" src=$p.post_portada alt=$p.post_title class="slider__slides--cover object-fit-cover w-100"}
		   		<div class="py-3 px-2">
		   			<a href="{$p.post_url}" title="{$p.post_title}" class="h5 text-break m-0 text-truncate truncate-2 text-decoration-none">{$p.post_title}</a>
		   			<div class="d-flex justify-content-between align-items-center lh-1 mt-2">
			   			<small class="d-block">{$p.post_date|hace:true}</small>
			   			{if $p.post_update}<small>Editado</small>{/if}
		   			</div>
		   		</div>
		   		<a href="{$tsConfig.url}/posts/{$p.c_seo}/" class="position-absolute up-categoria" style="top: .5rem;left: 0.5rem;--background:url('{$p.c_img}')"><span>{$p.c_nombre}</span></a>
				</div>
			{/foreach}
		</div>
	</div>
</section>
{/if}