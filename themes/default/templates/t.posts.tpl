{include "main_header.tpl"}

{if $tsPost.post_status > 0 || $tsAutor.user_activo != 1}
	<div class="empty">Este post se encuentra {if $tsPost.post_status == 2}eliminado{elseif $tsPost.post_status == 1} inactivo por acomulaci&oacute;n de denuncias{elseif $tsPost.post_status == 3} en revisi&oacute;n{elseif $tsPost.post_status == 3} en revisi&oacute;n{elseif $tsAutor.user_activo != 1} oculto porque pertenece a una cuenta desactivada{/if}, t&uacute; puedes verlo porque {if $tsUser->is_admod == 1}eres Administrador{elseif $tsUser->is_admod == 2}eres Moderador{else}tienes permiso{/if}.</div>
{/if}

<div class="row">
	<div class="col-lg-8 col-12">
		{if ($tsUser->is_admod && $tsPost.post_status == 0) || $tsUser->permisos.most || $tsUser->permisos.moayca || $tsUser->permisos.moop || $tsUser->permisos.moep || $tsUser->permisos.moedpo}
		   <div id="desapprove" style="display:none;">
		   	<div class="upform-group">
					<div class="upform-group-input upform-icon">
						<div class="upform-input-icon">{uicon name="hash"}</div>
						<input class="upform-input" type="text" name="d_razon" id="d_razon" placeholder="Raz&oacute;n de la revisi&oacute;n" maxlength="150" required>
						<div role="button" class="upform-input-icon px-3" style="width: max-content;" onclick="mod.posts.ocultar('{$tsPost.post_id}');">
							Continuar
						</div>
					</div>
					<small class="upform-status help"></small>
				</div>
		   </div>
		{/if}
		{include "m.posts_content.tpl"}
		{include "m.posts_del_autor.tpl"}
		<div class="comments">
			<a name="comentarios"></a>
			{include "m.posts_comments.tpl"}
			<a name="comentarios-abajo"></a>
		</div>
	</div>
	<div class="col-lg-4 col-12">
		{include "m.posts_autor.tpl"}
		{include "m.posts_related.tpl"}
	</div>
</div>
{include "main_footer.tpl"}