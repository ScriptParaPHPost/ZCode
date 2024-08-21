{if $tsComentariosFotos}			 
	<div class="comments py-3">
	   {foreach from=$tsComentariosFotos item=c}
			<div class="item border rounded p-2 d-grid column-gap-2" id="comment-{$c.cid}" style="grid-template-columns: 3rem 1fr;">
				<a href="{$tsConfig.url}/perfil/{$c.user_name}" class="avatar avatar-5 rounded overflow-hidden">
					<img loading="lazy" src="{$c.c_avatar}" class="w-100 h-100 object-fit-cover"/>
				</a>
				<div class="position-relative">
					{if $tsFoto.f_user == $tsUser->info.user_id || $tsUser->is_admod || $tsUser->permisos.moecf}
						<span role="button" onclick="fotos.borrar({$c.cid}, 'com'); return false" class="position-absolute" style="top: 0;right: 0;">{uicon name="trash-alt" class="pe-none"}</span>
					{/if}
					{if $tsUser->is_admod}
						<a href="{$tsConfig.url}/moderacion/buscador/1/1/{$c.c_ip}" class="position-absolute fw-semibold text-decoration-none" style="top:0;right:2rem;" target="_blank">{$c.c_ip}</a>
					{/if}
					<div class="info">
						<a href="{$tsConfig.url}/fotos/{$c.user_name}" class="fw-semibold text-decoration-none">{$c.user_name|verificado}</a> - 
						<em>{$c.c_date|hace:true}</em>
					</div>
					{if !$c.user_activo}
						<div>Escondido por pertener a una cuenta desactivada <span role="button" class="text-decoration-underline fw-semibold" onclick="$('#hdn_{$c.cid}').slideDown(); $(this).parent().slideUp(); return false;">Click para verlo</span>.</div>
						<div id="hdn_{$c.cid}" style="display:none">
					{/if} 
					<div class="clearfix">{$c.c_body|nl2br}</div>
					{if !$c.user_activo}</div>{/if}
				</div>
			</div>
		{/foreach}
		<div id="nuevos"></div>
	</div>
{elseif $tsFoto.f_closed == 0 && ($tsUser->is_admod || $tsUser->permisos.gopcf)}
   <div id="no-comments" class="empty">Esta foto no tiene comentarios, Se el primero!.</div>
{/if}