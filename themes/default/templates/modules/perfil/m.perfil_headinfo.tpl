<div class="perfil-header d-block d-lg-flex justify-content-start justify-content-center align-items-center gap-3 py-3">
	<a href="{$tsConfig.url}/perfil/{$tsInfo.nick}" class="mx-auto d-block avatar avatar-16 avatar-status status-{$tsInfo.status.css} overflow-hidden rounded shadow">
		<img loading="lazy" src="{$tsInfo.avatar}" alt="{$tsInfo.nick}" class="w-100 h-100">
	</a>
	<div class="d-block d-md-flex justify-content-md-between align-items-md-center py-3 px-md-0 d-lg-grid gap-3 info-data flex-grow-1">
		<div class="left w-100 text-center text-md-start">
			<h3 class="m-0 fw-bold">{$tsInfo.nick|verificado}</h3>
			<div class="d-block">
				<span>{uicon name="{$tsInfo.pais.icon}" folder="flags" alt="{$tsInfo.pais.name}" class="flag me-2"}{$tsInfo.stats.r_name} - Puntos {$tsInfo.stats.user_puntos|human}</span>
				{if $tsInfo.p_mensaje}<span class="fst-italic d-block">{$tsInfo.p_mensaje}</span>{/if}
			</div>
			{if $tsUser->uid != $tsInfo.uid && $tsUser->is_member}
				<div class="acciones d-flex justify-content-start align-items-center gap-3 mt-3">
					<span role="button" onclick="mensaje.nuevo('{$tsInfo.nick}');" title="Enviar mensaje privado">{uicon name="thread"}</span>
					<span role="button" onclick="denuncia.nueva('usuario',{$tsInfo.uid}, '', '{$tsInfo.nick}'); return false">Denunciar</span>
					<span role="button" onclick="bloquear({$tsInfo.uid}, {if $tsInfo.block.bid}false{else}true{/if}, 'perfil')" id="bloquear_cambiar">{if $tsInfo.block.bid}Desbloquear{else}Bloquear{/if}</span>
					{if ($tsUser->is_admod || $tsUser->permisos.mosu) && !$tsInfo.user_baneado}
						<span role="button" onclick="mod.users.action({$tsInfo.uid}, 'ban', true);" style="color:#CE152E;">Suspender</span>
					{/if}
					{if !$tsInfo.user_activo || $tsInfo.user_baneado}
						<span style="color:#CE152E;">Cuenta {if !$tsInfo.user_activo}desactivada{else}baneada{/if}</span>
					{/if}
					<span role="button" user-follow class="d-flex justify-content-start align-items-center" onclick="notifica.followed('{if $tsInfo.follow == 1}un{/if}follow', 'user', {$tsInfo.uid}, notifica.userInPostHandle, $(this).children('span'), 'perfil')"><span></span>{if $tsInfo.follow == 0}Seguir Usuario{else}Dejar de seguir{/if}</span>
				</div>
			{/if}
		</div>
		<div class="right d-flex justify-content-center justify-content-md-end align-items-center">
			<div class="d-block py-2 px-3 stats-item text-center text-md-end">
				<strong class="d-block h2 fw-bold m-0">{$tsInfo.stats.user_amigos|human}</strong>
				<span class="d-block text-uppercase small fw-semibold">Amigos</span>
			</div>
			<div class="d-block py-2 px-3 stats-item text-center text-md-end">
				<strong class="d-block h2 fw-bold m-0">{$tsInfo.stats.user_posts|human}</strong>
				<span class="d-block text-uppercase small fw-semibold">Posts</span>
			</div>
			<div class="d-block py-2 px-3 stats-item text-center text-md-end">
				<strong class="d-block h2 fw-bold m-0 user_follow_count">{$tsInfo.stats.user_seguidores|human}</strong>
				<span class="d-block text-uppercase small fw-semibold">Seguidores</span>
			</div>
		</div>
	</div>
</div>
<div class="menu-userPerfil d-flex justify-content-start align-items-center gap-2 p-2">
	{if $tsType == 'news' || $tsType == 'story'}
		<div class="userPerfil--item rounded text-center py-1 px-3 selected" role="button" tab="news">{if $tsType == 'story'}Publicaci&oacute;n{else}Noticias{/if}</div>
	{/if}
	<div class="userPerfil--item rounded text-center py-1 px-3{if $tsType == 'wall'} selected{/if}" role="button" tab="wall">Muro</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="actividad">Actividad</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="info">Informaci&oacute;n</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="posts">Posts</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="seguidores">Seguidores</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="siguiendo">Siguiendo</div>
	<div class="userPerfil--item rounded text-center py-1 px-3" role="button" tab="medallas">Medallas</div>
	{if $tsUser->is_admod == 1 && $tsUser->uid != $tsInfo.uid}
		<a class="userPerfil--item rounded text-center py-1 px-3 text-decoration-none" href="{$tsConfig.url}/admin/users?act=show&amp;uid={$tsInfo.uid}">Editar a {$tsInfo.nick}</a>
	{/if}
</div>