{include "m.foto_ver_author.tpl"}
<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="users"}
		</div>
		<div class="up-header--title">
			<span>Seguidores</span>
		</div>
	</div>
	<div class="up-card--body">
		{foreach from=$tsAmigosFotos item=f}
			<a href="{$tsConfig.url}/fotos/{$f.user_name}/{$f.foto_id}/{$f.f_title|seo}.html" class="d-block m-1 overflow-hidden position-relative">
				<img src="{$f.f_url}" title="{$f.f_title}" class="w-100 rounded object-fit-cover" style="height: 100px;" loading="lazy" />
				<a href="{$tsConfig.url}/perfil/{$f.user_name}" class="position-absolute up-badge" style="top: 0.5rem;left: 0.5rem;">{$f.user_name}</a>
			</a>
		{foreachelse}
			<div class="empty"><u>{$tsFoto.user_name}</u> no sigue usuarios o no han subido fotos.</div>
		{/foreach}
	</div>
	{if $tsAmigosFotos}
		<div class="up-card--footer">
			<a href="{$tsConfig.url}/fotos/{$tsFoto.user_name}" class="fw-semibold d-block text-center">Ver todas</a>
		</div>
	{/if}
</section>

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="graph-box"}
		</div>
		<div class="up-header--title">
			<span>Estad&iacute;sticas</span>
		</div>
	</div>
	<div class="up-card--body up-card--stats">
		<div class="d-grid gap-2" style="grid-template-columns: repeat(2, 1fr);">
			<a href="{$tsConfig.url}/fotos/{$tsFoto.user_name}" class="text-center text-decoration-none text-uppercase small py-3 position-relative">
				{uicon name="graph-box" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
				<span class="z-2 fw-bold position-relative body-color pe-none">
					<span class="h3 d-block m-0 up-effect up-effect--decrypt" data-count="{$tsFoto.user_fotos}">0</span> Fotos subidas
				</a>
			</a>
			<div class="text-center text-uppercase small py-3 position-relative">
				{uicon name="thread" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
				<span class="z-2 fw-bold position-relative body-color pe-none">
					<span class="h3 d-block m-0 up-effect up-effect--decrypt" data-count="{$tsFoto.user_foto_comments}">0</span> Comentarios
				</span>
			</div>
		</div>
	</div>
</section>

{*if $tsVisitasFotos}
	<div class="categoriaList">
		<h6 style="text-align:center;">Visitas recientes</h6>
		<ul id="v_album" style="margin-left:11px;">
			{foreach from=$tsVisitasFotos item=v}
		 		<a href="{$tsConfig.url}/perfil/{$v.user_name}" class="hovercard" uid="{$v.user_id}" style="display:inline-block;"><img loading="lazy" src="{$tsConfig.url}/files/avatar/{$v.user_id}_50.jpg" class="vctip" title="{$v.date|hace:true}" width="32"height="32"/></a>
			 {/foreach}
		</ul>
	</div>
{/if*}

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="box"}
		</div>
		<div class="up-header--title">
			<span>Medallas</span>
		</div>
	</div>
	<div class="up-card--body">
		{foreach from=$tsMedallasFotos item=m}
			<span class="d-block m-1 overflow-hidden position-relative">
				<img src="{$tsConfig.assets}/images/medallas/{$m.m_image}_32.png" title="{$m.m_title} - {$m.m_description}" class="w-100 rounded object-fit-cover avatar avatar-3" style="height: 100px;" loading="lazy" />
			</span>
		{foreachelse}
			<div class="empty">Esta foto no tiene medallas</div>
		{/foreach}
	</div>
</section>