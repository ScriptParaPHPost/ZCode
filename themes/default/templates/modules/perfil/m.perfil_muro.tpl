<div id="perfil_wall" status="activo">
	{if $tsGeneral.fotos_total > 0}
		<div id="perfil-foto-bar">{include "m.perfil_muro_fotos.tpl"}</div>
	{/if}
	<div id="perfil-form" class="widget">
		{if $tsPrivacidad.mf.v == true}
			{include "m.perfil_muro_form.tpl"}
			<div class="filtros">
				<div class="d-flex justify-content-start align-items-center column-gap-2 mb-3">
					<span role="button" onclick="loadFilter(1)" class="filter-item d-block rounded py-1 px-3 active">Todos</span>
					<span role="button" onclick="loadFilter(2)" class="filter-item d-block rounded py-1 px-3">Imagenes</span>
					<span role="button" onclick="loadFilter(3)" class="filter-item d-block rounded py-1 px-3">Enlaces</span>
					<span role="button" onclick="loadFilter(4)" class="filter-item d-block rounded py-1 px-3">Videos</span>
				</div>
			</div>
		{else}
			<div class="empty">{$tsPrivacidad.mf.m}</div>
		{/if}
	</div>
	<div class="widget clearfix" id="perfil-wall">
		<div id="wall-content">
			{include "m.perfil_muro_story.tpl"}
		</div>
		{if $tsMuro.total >= 10}
			<div class="more-pubs">
			  	<div class="content empty d-flex justify-content-center align-items-center">
			  		<span role="button" onclick="muro.stream.loadMore('wall'); return false;">Publicaciones m&aacute;s antiguas</span>
			  		<span class="svg" style="display: none;">{uicon name="bars-scale" folder="spinner"}</span>
			  	</div>
			</div>
		{elseif $tsMuro.total == 0 && $tsUser->is_member}
			<div class="empty">Este usuario no tiene comentarios, se el primero.</div>
		{/if}
	</div>
</div>