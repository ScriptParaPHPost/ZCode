{include "main_header.tpl"}

<div class="row">
	<div class="col-12 col-lg-3">
		{include "m.borradores_mostrar.tpl"}
		{include "m.borradores_ordenar.tpl"}
		{include "m.borradores_categorias.tpl"}		
	</div>
	<div class="col-12 col-lg-9">
		{if $tsBID > 0}
			{include "m.borradores_show.tpl"}
		{else}
			{include "m.borradores_home.tpl"}
		{/if}
	</div>
</div>

{include "main_footer.tpl"}