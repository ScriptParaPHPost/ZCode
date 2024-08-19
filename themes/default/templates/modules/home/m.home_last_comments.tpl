<section class="lastPosts up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon" role="button" onclick="actualizar_comentarios('-1','0'); return false;" title="Actualizar comentarios">
			{uicon name="thread"}
		</div>
		<div class="up-header--title">
			<span>&Uacute;ltimos comentarios</span>
		</div>
	</div>
	<div class="up-card--body" id="ultimos_comentarios">
		{include "p.posts.last-comentarios.tpl"}
	</div>
</section>