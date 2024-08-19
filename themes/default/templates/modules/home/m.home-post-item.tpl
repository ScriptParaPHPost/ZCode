<article itemscope itemtype="https://schema.org/Article" class="position-relative small d-grid align-items-center rounded mb-2 categoriaPost entry-animation{if $p.post_private == 1} private{/if}{if $p.post_sticky == 1} sticky{/if}{if $p.post_sponsored == 1} patrocinado{/if}{if $p.post_status > 0} status_{$p.post_status}{/if}{if $p.user_activo == 0} active_{$p.user_activo}{/if}{if $p.user_baneado == 1} baneado_{$p.user_baneado}{/if}" title="{if $p.post_status == 3}El post est&aacute; en revisi&oacute;n{elseif $p.post_status == 1}El post se encuentra en revisi&oacute;n por acumulaci&oacute;n de denuncias{elseif $p.post_status == 2}El post est&aacute; eliminado{elseif $p.user_activo == 0}La cuenta del usuario est&aacute; desactivada{elseif $p.user_baneado == 1}La cuenta del usuario est&aacute; baneada{/if}">

	<picture class="picture overflow-hidden">
		<source srcset="{$p.post_portada.md}" media="(min-width: 800px)">
		<source srcset="{$p.post_portada.lg}" media="(min-width: 400px)">
		<source srcset="{$p.post_portada.sm}">
		<img src="{$p.post_portada.sm}" loading="lazy" alt="{$p.post_title}" class="placeholder placeholder-wave object-fit-cover w-100 h-100">
	</picture>

	<div class="article-item px-2">
		{if $p.post_sticky != 1}<a style="font-size: 0.875rem;" class="category fw-semibold d-block text-decoration-none" href="{$tsConfig.url}/posts/{$p.c_seo}/">{$p.c_nombre}</a>{/if}
		<a title="{$p.post_title}" href="{$p.post_url}" target="_self" class="text-truncate title fw-semibold h5 m-0 text-decoration-none">{$p.post_title}</a>
		<span class="d-block pt-1" itemprop="dateCreated" datetime="{$p.post_date|date_format:'Y-m-d'}">{$p.post_date|hace:true} &raquo; <a href="{$tsConfig.url}/perfil/{$p.user_name}" class="text-decoration-none" itemprop="creator" itemscope itemtype="https://schema.org/Person"><strong itemprop="name">@{$p.user_name|verificado}</strong></a></span>
	</div>
	{if $p.post_new || $p.post_sticky}
		<span class="isNew position-absolute px-2 d-inline-block main-bg main-color-active fw-bold rounded-1">{if $p.post_sticky}Staff{else}{$p.post_new}{/if}</span>
	{/if}
</article>