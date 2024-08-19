<div id="post-comentarios">
	<div class="comentarios-title position-relative">
		<h4 class="titulorespuestas"><span id="ncomments">{$tsPost.post_comments}</span> Comentarios</h4>
		<div id="load_comments" class="py-3 text-center" style="display: none;">
			<span class="d-block">Cargando comentarios</span>
			<img src="{$tsConfig.assets}/icons/spinner/3-dots-bounce.svg" alt="Cargando comentarios">
		</div>
	</div>
	{if $tsPost.post_comments > $tsConfig.c_max_com}
		<div class="comentarios-title"><div class="paginadorCom"></div></div>
	{/if}
	{include "t.php_files/p.comentario.ajax.tpl"}
	<!-- <div id="comentarios">
		<script>
			const loadComments = {
				post_id: {$tsPages.post_id},
				autor: {$tsPages.autor}
			}
		</script>
		<div id="no-comments" class="empty">Cargando comentarios espera un momento...</div>
	</div> -->
	{if $tsPost.post_comments > $tsConfig.c_max_com}
		<div class="comentarios-title"><div class="paginadorCom"></div></div>
	{/if}
	
	{if $tsPost.post_block_comments == 1 && ($tsUser->is_admod == 0 && $tsUser->permisos.mocepc == false)}
		<div id="no-comments" class="empty empty-warning">El post se encuentra cerrado y no se permiten comentarios.</div>
	
	{elseif $tsUser->is_admod == 0 && $tsUser->permisos.gopcp == false}
		<div id="no-comments" class="empty empty-danger">No tienes permisos para comentar.</div>
	
	{elseif $tsUser->is_member && ($tsPost.post_block_comments != 1 || $tsPost.post_user == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos.gopcp) && $tsPost.block == 0}
		<div class="miComentario">{include "m.posts_comments_form.tpl"}</div>
	{/if}
</div>