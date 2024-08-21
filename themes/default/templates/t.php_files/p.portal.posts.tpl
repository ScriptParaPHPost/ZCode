<div class="body">
	<div>
		{if $tsPosts}
			{foreach from=$tsPosts item=p}
				<div class="categoriaPost d-grid column-gap-2 position-relative p-2 mb-2" style="grid-template-columns: 2rem 1fr;place-items:center start;">
					<div class="img-category">
						<img src="{$p.c_img}" class="avatar avatar-3" alt="{$p.c_nombre}">
					</div>
					<div class="post-data">
						<a class="fw-semibold d-block text-decoration-none{if $p.post_private} privado{/if}" title="{$p.post_title}" target="_self" href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html">{$p.post_title}</a>
						<span>{$p.post_date|hace:true} &raquo; <a href="{$tsConfig.url}/perfil/{$p.user_name}" class="text-decoration-none fw-semibold">{$p.user_name|verificado}</a> &middot; Puntos <strong>{$p.post_puntos}</strong> &middot; Comentarios <strong>{$p.post_comments}</strong></span>
						<span class="position-absolute" style="bottom:.5rem; right:1rem;">
							<a href="{$tsConfig.url}/posts/{$p.c_seo}/" class="up-badge">{$p.c_nombre}</a>
						</span>
					</div>
				</div>
			{/foreach}
		{else}
			<div class="empty">
				No hay posts aqu&iacute;,{if $tsType == 'posts'} <span role="button" class="fw-bold" onclick="$('#config_posts').slideDown();">configura</span> tus categor&iacute;as preferidas.{elseif $tsType == 'favs'} puedes agregar un post a tus favoritos para visitarlo m&aacute;s tarde.{elseif $tsType == 'shared'} los usuarios que sigues podr&aacute;n recomentarte posts.{/if}
			</div>
		{/if}
	</div>
</div>
<div class="footer size13">
	{if $tsPages.prev != 0}<div style="text-align:left" class="floatL"><a onclick="portal.posts_page('{$tsType}', {$tsPages.prev}, true); return false">&laquo; Anterior</a></div>{/if}
	{if $tsPages.next != 0}<div style="text-align:right" class="floatR"><a onclick="portal.posts_page('{$tsType}', {$tsPages.next}, true); return false">Siguiente &raquo;</a></div>{/if}
</div>