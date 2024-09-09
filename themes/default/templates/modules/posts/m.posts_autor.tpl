<div class="post-autor rounded position-relative mb-3" itemscope itemtype="https://schema.org/Article">
	<div class="color position-relative d-flex justify-content-end align-items-center gap-2 px-2" style="background:#{$tsAutor.rango.r_color}">
		{if $tsAutor.pais}
			{uicon name="{$tsAutor.pais.icon}" folder="flags" alt="{$tsAutor.pais.name}" class="rounded-circle avatar avatar-2"}
		{/if}
		<img src="{$tsAutor.rango_image}" title="{$tsAutor.rango.r_name}" />
	</div>

	<div class="post-autor--datos d-grid align-items-start p-3 gap-3 body-color">
		<a href="{$tsConfig.url}/perfil/{$tsAutor.user_name}" class="post-autor--cover position-relative z-3 avatar avatar-12">
	      <img src="{$tsAutor.user_avatar}" alt="{$tsAutor.user_name}" class="w-100 h-100 object-fit-cover shadow body-bg">
	   </a>
	   <div class="position-relative z-3 lh-2 pt-4">
	 		<a href="{$tsConfig.url}/perfil/{$tsAutor.user_name}" class="given-name d-block fw-semibold text-decoration-none" itemprop="name">{$tsAutor.user_name|verificado}</a>
	 		<small>{$tsAutor.rango.r_name} - {$tsAutor.status.t}</small>
	 		<div class="data d-flex justify-content-between align-items-center">

				{if !$tsUser->is_member}
					<a class="small d-flex justify-content-end align-items-center gap-2 text-decoration-none fw-semibold" href="{$tsConfig.url}/registro/?r={$tsConfig.canonical}">{uicon name="user_add"} Seguir Usuario</a>
				{elseif $tsAutor.user_id != $tsUser->uid}
					<a role="button" data-action="unfollow_user" class="small text-decoration-none fw-semibold" onclick="notifica.followed('unfollow', 'user', {$tsAutor.user_id}, notifica.userInPostHandle, $(this).children('span'))"{if !$tsAutor.follow} style="display: none!important;"{/if}><span>Dejar de seguir</span></a>
					<a role="button" data-action="follow_user" class="small text-decoration-none fw-semibold" onclick="notifica.followed('follow', 'user', {$tsAutor.user_id}, notifica.userInPostHandle, $(this).children('span'))"{if $tsAutor.follow > 0} style="display: none!important;"{/if}><span>Seguir Usuario</span></a>
				{/if}

				{if $tsAutor.user_id != $tsUser->uid}
					<span role="button" onclick="mensaje.nuevo('{$tsAutor.user_name}');return false" title="Enviar mensaje privado">{uicon name="mail_add"}</span>
				{/if}
			</div>
	 	</div>
	</div>
	<h4>Estadísticas del usuario</h4>
	<div class="mb-3 d-grid gap-3" style="grid-template-columns: repeat(2, 1fr);">
		<div class="stat--item text-uppercase fw-semibold rounded shadow py-3 text-center">
			<span class="d-block h3 m-0 nData user_follow_count">{$tsAutor.user_seguidores|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">Seguidores</span>
		</div>
		<div class="stat--item text-uppercase fw-semibold rounded shadow py-3 text-center">
			<span class="d-block h3 m-0 nData" style="color: #0196ff">{$tsAutor.user_puntos|human}</span>
			<span class="txtData" id="puntos_post" style="font-size: 0.875rem;">Puntos</span>
		</div>
		<div class="stat--item text-uppercase fw-semibold rounded shadow py-3 text-center">
			<span class="d-block h3 m-0 nData">{$tsAutor.user_posts|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">Posts</span>
		</div>
		<div class="stat--item text-uppercase fw-semibold rounded shadow py-3 text-center">
			<span class="d-block h3 m-0 nData" style="color: #456c00">{$tsAutor.user_comentarios|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">Comentarios</span>
		</div>			
	</div>

	<div class="d-flex justify-content-center align-items-center gap-3">
		<div class="d-flex justify-content-start align-items-center gap-1" data-count="true" onclick="favorito.agregar({$tsPost.post_id})" title="Agregar a Favoritos">
			{uicon name="bookmark" size="2rem" class="pe-none"}
			<span class="count favoritos_total fw-semibold d-block text-center pe-none" data-total="{$tsPost.post_favoritos}">{$tsPost.post_favoritos|human}</span>
		</div>
		<div class="d-block avatar avatar-3 lh-base" title="Denunciar" onclick="denuncia.nueva('post', {$tsPost.post_id}, '{$tsPost.post_title}', '{$tsAutor.user_name}'); return false;">
			{uicon name="flag" size="2rem" class="pe-none"}
		</div>
	</div>
			
</div>
{include "m.posts_stats_in.tpl"}
{if $tsUser->is_admod || $tsUser->permisos.modu || $tsUser->permisos.mosu}
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">
				{uicon name="nut"}
			</div>
			<div class="up-header--title">
				<span>Herramientas</span>
			</div>
		</div>
		<div class="up-card--body tools p-3">
			<a class="geoip d-flex justify-content-start align-items-center gap-3 text-decoration-none fw-semibold mb-3 py-2 px-3 rounded shadow tool--item" href="{$tsConfig.url}/moderacion/buscador/1/1/{$tsPost.post_ip}" target="_blank">{uicon name="location"} IP: {$tsPost.post_ip}</a>
			{if $tsUser->is_admod == 1}
				<a class="edituser d-flex justify-content-start align-items-center gap-3 text-decoration-none fw-semibold mb-3 py-2 px-3 rounded shadow tool--item" href="{$tsConfig.url}/admin/users?act=show&amp;uid={$tsAutor.user_id}">{uicon name="write"} Editar Usuario</a>
			{/if}
			{if $tsAutor.user_id != $tsUser->uid} 
				<span class="alert d-flex justify-content-start align-items-center gap-3 fw-semibold mb-3 py-2 px-3 rounded shadow tool--item" role="button" onclick="mod.users.action({$tsAutor.user_id}, 'aviso', false); return false;">{uicon name="warning_hex"} Enviar Aviso</span>
			{/if}
			{if $tsAutor.user_id != $tsUser->uid && $tsUser->is_admod || $tsUser->permisos.modu || $tsUser->permisos.mosu}
				{if $tsAutor.user_baneado}
					{if $tsUser->is_admod || $tsUser->permisos.modu}
						<span class="unban d-flex justify-content-start align-items-center gap-3 fw-semibold mb-3 py-2 px-3 rounded shadow tool--item" role="button" onclick="mod.reboot({$tsAutor.user_id}, 'users', 'unban', false); $(this).remove(); return false;">{uicon name="face_happy"} Desuspender Usuario</span>
					{/if}
				{else}
					{if $tsUser->is_admod || $tsUser->permisos.mosu}
						<span class="ban d-flex justify-content-start align-items-center gap-3 fw-semibold mb-3 py-2 px-3 rounded shadow tool--item" role="button" onclick="mod.users.action({$tsAutor.user_id}, 'ban', false); $(this).remove(); return false;">{uicon name="face_sad"} Suspender Usuario</span>
					{/if}
				{/if}
			{/if}
		</div>
	</section>
{/if}