<div class="position-relative translucent-bg shadow rounded mb-3">
	<div class="d-flex justify-content-start align-items-center column-gap-3 p-2">
		<a href="{$tsConfig.url}/perfil/{$tsFoto.user_name}" class="avatar avatar-8">
			<img src="{$tsFoto.avatar}" loading="lazy"/>
		</a>
		<div class="foto-content-autor">
			<a href="{$tsConfig.url}/perfil/{$tsFoto.user_name}" class="fw-semibold d-block fs-6 text-decoration-none">{$tsFoto.user_name|verificado}</a>
			<div class="d-flex justify-content-start align-items-center column-gap-3 py-2">
				{uicon name="{$tsFoto.user_pais.icon}" folder="flags" alt="{$tsFoto.user_pais.name}" class="rounded avatar avatar-2"}
				<span class="autor-rango translucent-bg rounded py-0 pe-2 ps-4 border fw-semibold" style="background-image:url({$tsConfig.assets}/images/rangos/{$tsFoto.r_image});--border-color:#{$tsFoto.r_color}">{$tsFoto.r_name}</span>
			</div>
		</div>
	</div>
	<div class="px-2 pb-2 text-center">
		{if $tsUser->is_member && $tsUser->uid != $tsFoto.f_user}
			<span role="button" class="btn btn-outline btn-sm d-block mb-2" onclick="mensaje.nuevo('{$tsFoto.user_name}'); return false;">Enviar Mensaje</span>
		{/if}
		{if $tsUser->uid != $tsFoto.f_user && $tsUser->is_member}
			<div class="v_follow">
				<span role="button" data-action="unfollow_user" class="small me-2 text-decoration-none fw-semibold" onclick="notifica.followed('unfollow', 'user', {$tsFoto.f_user}, notifica.userInPostHandle, $(this).children('span'))"{if !$tsFoto.follow} style="display: none!important;"{/if}><span>Dejar de seguir</span></span>
				<span role="button" data-action="follow_user" class="small me-2 text-decoration-none fw-semibold" onclick="notifica.followed('follow', 'user', {$tsFoto.f_user}, notifica.userInPostHandle, $(this).children('span'))"{if $tsFoto.follow > 0} style="display: none!important;"{/if}><span>Seguir Usuario</span></span>
				<span role="button" onclick="denuncia.nueva('foto',{$tsFoto.foto_id}, '{$tsFoto.f_title}', '{$tsFoto.user_name}'); return false;" class="small me-2 text-decoration-none fw-semibold"><span>Denunciar</span></span>
			</div>
		{/if}
	</div>
</div>