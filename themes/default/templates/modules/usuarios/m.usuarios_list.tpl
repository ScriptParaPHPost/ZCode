{if $tsUsers}
	<div class="row">
		{foreach from=$tsUsers item=u}
			<div class="col-12 col-lg-6">
				<div class="usuario border rounded shadow mb-3 d-grid column-gap-3 p-2 position-relative" style="grid-template-columns: 3.5rem 1fr;">
					<a href="{$tsConfig.url}/perfil/{$u.user_name}" class="avatar avatar-7 avatar-status status-{$u.status.css} mx-auto d-block overflow-hidden rounded">
						<img src="{$tsConfig.logos.64}" data-src="{$u.avatar}" alt="Usuario {$u.status.t}" loading="lazy" class="w-100 h-100"/>
					</a>
					<div class="usuario-data">
						<a href="{$tsConfig.url}/perfil/{$u.user_name}" class="text-decoration-none fw-semibold mb-2 fs-5 d-block">{$u.user_name|verificado}{if $tsUser->is_admod} #{$u.user_id}{/if}</a>
						<span class="d-flex justify-content-start align-items-center column-gap-2">
							<small class="d-flex justify-content-start align-items-center column-gap-1"><img src="{$u.rango.image}" alt="{$u.rango.title}" class="avatar"> {$u.rango.title}</small>-
							<small>{if $u.user_sexo == 'none'}Sin definir{elseif $u.user_sexo == 'female'}Mujer{else}Hombre{/if}</small>
							{if $u.user_posts > 0}-<small>{$u.user_posts} Posts</small>{/if}
						</span>
						{if $u.user_id != $tsUser->uid}
							<div class="position-absolute fw-semibold" style="top: .5rem;right: 0.5rem;">
								<span role="button" title="Enviar Mensaje" onclick="{if !$tsUser->is_member}location.href=ZCodeApp.url+'/registro/'{else}mensaje.nuevo('{$u.user_name}');return false{/if}">Enviar MP</span>
							</div>
						{/if}
						<div class="avatar avatar-2 rounded-circle position-absolute" style="top:.125rem;left:.125rem;">{$u.pais_image}</div>
					</div>
				</div>
			</div>
		{/foreach}
	</div>
{else}
	<div class="empty">No se encontraron usuarios con los filtros seleccionados.</div>
{/if}