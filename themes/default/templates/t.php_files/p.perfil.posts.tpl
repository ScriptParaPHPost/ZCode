1:
<div id="perfil_posts" status="activo">
	<div class="widget big-info clearfix">
		<div class="title-w border p-2 rounded d-flex justify-content-between align-items-center">
			<h3 class="m-0 fs-5">&Uacute;ltimos Posts creados por {$tsUsername}</h3>
		</div>
		{if $tsGeneral.posts}
		  	<ul class="ultimos">
				{foreach from=$tsGeneral.posts item=p}
					<li class="d-flex justify-content-between align-items-center border-bottom p-2">
						<span class="d-block flex-grow-1">
							<a title="{$p.post_title}" target="_self" href="{$p.post_url}" class="d-block text-decoration-none fw-semibold">{$p.post_title}</a>
							<small class="d-flex justify-content-start align-items-center column-gap-2"><img src="{$p.c_img}" alt="{$p.c_seo}" class="avatar avatar-1"> {$p.c_nombre}</small>
						</span>					 	
					 	<span class="badge main-bg">{$p.post_puntos} Puntos</span>
					</li>
				{/foreach}
				{if $tsGeneral.total >= 18}
					<li class="see-more"><a href="{$tsConfig.url}/buscador/?autor={$tsGeneral.username}">Ver m&aacute;s &raquo;</a></li>
				{/if}
		  </ul>
		{else}
			<div class="empty">No hay posts</div>
		{/if}
	</div>
</div>