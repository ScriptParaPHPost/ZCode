{if $tsFoto.f_status != 0 || $tsFoto.user_activo == 0}
	<div class="empty">Esta foto no es visible{if $tsFoto.f_status == 1} por acumulaci&oacute;n de denuncias u orden administrativa{elseif $tsFoto.f_status == 2} porque est&aacute; eliminada{elseif $tsFoto.user_activo != 1} porque la cuenta del due&ntilde;o se encuentra desactivada{/if}, pero puedes verla porque eres {if $tsUser->is_admod == 1}administrador{elseif $tsUser->is_admod == 2}moderador{else}autorizado{/if}.</div><br />
{/if}
<div class="foto-content">

	<div class="foto-header d-flex justify-content-between align-items-start py-3 mb-3">
		<span class="fs-4 fw-semibold d-block m-md-0">{$tsFoto.f_title}</span>
		<span class="small d-block text-end fst-italic" style="min-width:120px;">{$tsFoto.f_date|hace:true}</span>
	</div>
	<div class="foto-preview position-relative">
		{if $tsFoto.f_user == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos.moef || $tsUser->permisos.moedfo}
			<div class="tools position-absolute">
				{if $tsFoto.f_status != 2 && ($tsUser->is_admod || $tsUser->permisos.moef || $tsFoto.f_user == $tsUser->uid)}
					<span class="btn btn-sm" role="button" onclick="{if $tsUser->uid == $tsFoto.f_user}fotos.borrar({$tsFoto.foto_id}, 'foto'); {else}mod.fotos.borrar({$tsFoto.foto_id}, 'foto');{/if}return false;">Borrar</span>
				{/if}
				{if $tsUser->is_admod || $tsUser->permisos.moedfo || $tsFoto.f_user == $tsUser->uid}
					<a class="btn btn-sm" href="{$tsConfig.url}/fotos/editar.php?id={$tsFoto.foto_id}">Editar</a>
				{/if}
			</div>
		{/if}
		<img loading="lazy" src="{$tsFoto.f_url}" style="min-height:300px" class=" mx-auto w-100 h-auto d-block rounded shadow" />
	</div>
	<div class="d-flex justify-content-start align-items-center column-gap-3 border-top border-bottom my-2 py-2">
		<div class="fw-semibold d-flex justify-content-start align-items-center column-gap-2" title="Visitas">
			{uicon name="eye" class="pe-none" size="1.5rem"}
			<span>{$tsFoto.f_hits|human}</span>
		</div>
		<div class="fw-semibold d-flex justify-content-start align-items-center column-gap-2" onclick="fotos.votar('pos', {$tsFoto.foto_id})" title="Votar positivo" role="button">
			{uicon name="thumbs-up" class="pe-none" size="1.5rem"}
			<span class="text-success" id="votos_total_pos">{$tsFoto.votos.positivos|human}</span>
		</div>
		<div class="fw-semibold d-flex justify-content-start align-items-center column-gap-2" onclick="fotos.votar('neg', {$tsFoto.foto_id})" title="Votar negativo" role="button">
			{uicon name="thumbs-down" class="pe-none" size="1.5rem"}
			<span class="text-danger" id="votos_total_neg">{$tsFoto.votos.negativos|human}</span>
		</div>
		{if $tsUser->is_admod}
			<a href="{$tsConfig.url}/moderacion/buscador/1/1/{$tsFoto.f_ip}" class="text-decoration-none fw-semibold d-flex justify-content-start align-items-center column-gap-2">
				{uicon name="gps" class="pe-none" size="1.5rem"}
				<span>{$tsFoto.f_ip}</span>
			</a>					
		{/if}	
	</div>
	<div class="p-2 text-center">
		<span class="text-break d-block">{$tsFoto.f_description|nl2br}</span>
	</div>
</div>

<div id="post-comentarios">
	<div class="comentarios-title position-relative">
		<h4 class="titulorespuestas"><span id="ncomments">{$tsFoto.f_comments}</span> Comentarios</h4>
		<div id="load_comments" class="py-3 text-center" style="display: none;">
			<span class="d-block">Cargando comentarios</span>
			{uicon name="3-dots-bounce" folder="spinner" alt="Cargando comentarios"}
		</div>
	</div>
	{include "m.fotos_comments.tpl"}
	{if $tsUser->is_admod == 0 && $tsUser->permisos.gopcf == false}
		<div class="noComments" class="empty">No tienes permiso para comentar.</div>
	{elseif $tsFoto.f_closed == 1}
		<div class="noComments" class="empty">La foto se encuentra cerrada y no se permiten comentarios.</div>
	{elseif $tsUser->is_member}
		<div class="miComentario">{include "m.fotos_comments_form.tpl"}</div>
	{else}
		<div class="empty">Para poder comentar necesitas estar <a href="{$tsConfig.url}/registro/?r={$tsConfig.canonical}" class="fw-semibold text-decoration-none">Registrado.</a> O.. ya tienes usuario? <a href="{$tsConfig.url}/login/" class="fw-semibold text-decoration-none">Logueate!</a></div>
	{/if}
</div>