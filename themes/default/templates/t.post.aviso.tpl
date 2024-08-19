{include "main_header.tpl"}
				
<div class="post-{$tsAviso.0} my-3 py-3 mx-auto w-75">
	<div class="position-relative">
		<div class="avatar avatar-6 float-start me-3">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
   			<title>collision</title>
   			<path fill="#ef4e16" d="M44.7 35.4L64 24.7l-21.7 1 6.5-13.3L37 20.7l1.5-18.3L30 17.5 17.1 0l5.5 21.8-19.2-6.4 15.3 14L0 33.1l18 4.1L7.9 55.1l16.5-9.9L25 64l6.8-17.2 9.1 13.6-.5-16.3L56 50z"/>
   			<path fill="#ffce31" d="M39.1 33.8l11.3-5.3-12.5-.3 3.3-7.5-6.6 3.8.2-10.1-4.4 8.4-6.7-8.6 2.9 10.9-11.2-2.8 9.1 7.8-11.3 2.7 10.4 2.4-7.3 11.2 11.2-7.6.3 9.9 4-9 4.6 6.7-.1-8.1 8.2 3.6z"/>
   			<path fill="#fff" d="M34.9 32.4l4.8-2.2-5.3-.1 1.4-3.2-2.8 1.6.1-4.3-1.9 3.6-2.8-3.6 1.2 4.6-4.7-1.2 3.8 3.3-4.8 1.1 4.4 1-3.1 4.8 4.8-3.2.1 4.2 1.7-3.9 1.9 2.9v-3.5l3.5 1.6z"/>
			</svg>
		</div>
		<div>
			<h3 class="h4 fw-bold">{$tsAviso.1}</h3>
			<p>Pero no pierdas las esperanzas, no todo est&aacute; perdido, la soluci&oacute;n est&aacute; en:</p>
		</div>
	</div>
	<div class="post-relacionados">
		<h4 class="py-2 border-bottom">Post Relacionados</h4>	

		{foreach from=$tsRelated item=p}
			<div class="{$p.c_seo}">
			 	<a class="mb-2 rounded p-3 hover:main-bg hover:main-color active:main-bg active:main-color fw-semibold text-decoration-none d-block{if $p.post_private} privado{/if}"title="{$p.post_title}" href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html" rel="dc:relation">{$p.post_title}</a>
			</div>
		{foreachelse}
			<div class="empty">No se encontraron posts relacionados.</div>
		{/foreach}
	
	</div>
</div>
					 
{include "main_footer.tpl"}