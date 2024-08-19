<div class="resultados">
	{foreach from=$tsResults.data item=p}
		<div class="resultado rounded shadow p-2 mb-3 d-grid gap-3">
			<picture class="user_avatar rounded shadow overflow-hidden">
				<source srcset="{$p.portada.md}" media="(min-width: 800px)">
				<source srcset="{$p.portada.lg}" media="(min-width: 400px)">
				<source srcset="{$p.portada.sm}">
				<img src="{$p.portada.sm}" loading="lazy" alt="{$p.post_title}" class="object-fit-cover w-100 h-100">
			</picture>
			<div class="resultado--info position-relative small">
	         <a class="h5 text-decoration-none text-truncate d-block" href="{$p.post_url}">{$p.post_title}</a>
		      <span class="d-block"><strong>Autor</strong>: <a href="{$tsConfig.url}/perfil/{$p.user_name}" class="text-decoration-none fw-semibold main-bg-color">{$p.user_name}</a></span>
		      <span><strong>Categoría</strong>: <a href="{$tsConfig.url}/posts/{$p.c_seo}" class="text-decoration-none fw-semibold main-bg-color">{$p.c_nombre}</a></span> &bull; 
		      <span>{$p.post_date|hace:true}</span> &bull; 
		      <span><a href="{$tsConfig.url}/buscador/?query={$p.post_title}&tesla={$tsEngine}&category={$tsCategory}&autor={$tsAutor}" class="text-decoration-none fw-semibold main-bg-color">Post Relacionados</a></span>
				<div class="tags position-absolute">
					{foreach $p.post_tags as $tag}
				      <span class="tag--item rounded px-2 main-bg main-color fw-semibold d-inline-block small">{$tag}</span>
				   {/foreach}
				   {if $p.remaining_tags}
        				<span class="tag--item rounded px-2 main-bg main-color fw-semibold d-inline-block small total">{$p.remaining_tags}</span>
        			{/if}
				</div>
			</div>
		</div>
	{foreachelse}
		<div class="welcome sinresultados">
	      <h4>Lo siento, no se encontraron resultados para <strong>{if $tsEngine == 'tags'}#{/if}{$tsQuery}</strong>...</h4>
	   </div>
	{/foreach}
</div>
<div class="paginadorCom d-flex justify-content-around align-items-center">
   {if $tsResults.pages.prev != 0}
      <a class="btn btn-success" href="{$tsConfig.url}/buscador/?page={$tsResults.pages.prev}{if $tsQuery}&query={$tsQuery}{/if}{if $tsEngine}&engine={$tsEngine}{/if}{if $tsCategory}&category={$tsCategory}{/if}{if $tsAutor}&autor={$tsAutor}{/if}">&laquo; Anterior</a>
   {/if}{if $tsResults.pages.next != 0}
      <a class="btn btn-success" href="{$tsConfig.url}/buscador/?page={$tsResults.pages.next}{if $tsQuery}&query={$tsQuery}{/if}{if $tsEngine}&engine={$tsEngine}{/if}{if $tsCategory}&category={$tsCategory}{/if}{if $tsAutor}&autor={$tsAutor}{/if}">Siguiente &raquo;</a>
   {/if}
</div>