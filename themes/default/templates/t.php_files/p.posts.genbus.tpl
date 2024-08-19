{if $tsDo == 'search' && $tsPosts}
	<div class="empty">Parece que existen posts similares al que quieres agregar, te recomendamos leerlos antes para evitar un repost.</div>
	{foreach from=$tsPosts item=p}
		<small class="fw-semibold">
			<a class="text-decoration-none" href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html" target="_blank">{$p.post_title}</a>
		</small> &bull;
	{/foreach}
{else}
	{$tsTags}
{/if}