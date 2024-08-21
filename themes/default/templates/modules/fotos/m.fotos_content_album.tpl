<div class="foto-item{if $thisAlbum == true} album{/if} mb-3 rounded overflow-hidden shadow position-relative"{if $f.f_status != 0 || $f.user_activo == 0 || $f.user_baneado == 1} title="{if $f.f_status == 2}Imagen eliminada{elseif $f.f_status == 1}Imagen oculta por acumulaci&oacute;n de denuncias{elseif $f.user_activo == 0}La cuenta del usuario est&aacute; desactivada{elseif $f.user_baneado == 1}La cuenta del usuario est&aacute; suspendida{/if}" style="border: 1px solid {if $f.f_status == 2}rosyBrown{elseif $f.f_status == 1}coral{elseif $f.user_activo == 0}brown{elseif $f.user_baneado == 1}orange{/if};opacity: 0.5;filter: alpha(opacity=50);"{/if}>
	<img role="button" onclick="location.href='{$f.foto_url}'" class="foto-item_img rounded placeholder placeholder-wave" loading="lazy" src="{$f.f_url}" alt="{$f.f_title}"/>
	<div class="foto-item_data">
		<a class="up-badge position-absolute p-0 overflow-hidden column-gap-2 pe-2 d-flex justify-content-start align-items-center" style="top:.875rem;left:.875rem;height:1.5rem" href="{$tsConfig.url}/perfil/{$f.user_name}">
			<img src="{$f.avatar}" class="avatar avatar-2" alt="{$f.user_name}">
			{$f.user_name|verificado}
		</a>
		<small class="up-badge position-absolute" style="top:2.875rem;left:.875rem;">{$f.f_date|hace:true}</small>
		<a href="{$f.foto_url}" class="d-block text-decoration-none fs-6 fw-semibold main-bg-color">{$f.f_title}</a>
		<span class="text-truncate truncate-2">{$f.f_description}</span>
	</div>
</div>