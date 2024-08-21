{include "main_header.tpl"}

	<div id="resultados" class="resultadosFull"> 
		<div class="row">
			<div class="col-12 col-lg-3">
				{include "m.usuarios_sidebar.tpl"}
			</div>
			<div class="col-12 col-lg-9">
				<div id="showResult" class="resultFull">
					{include "m.usuarios_list.tpl"}
					
					<div class="paginador d-flex justify-content-center align-items-center column-gap-3 p-3">
						{if $tsPages.prev != 0}<a class="btn" href="{$tsConfig.url}/usuarios/?page={$tsPages.prev}{if $tsFiltro.online == 'true'}&online=true{/if}{if $tsFiltro.avatar == 'true'}&avatar=true{/if}{if $tsFiltro.sex}&sex={$tsFiltro.sex}{/if}{if $tsFiltro.pais}&pais={$tsFiltro.pais}{/if}{if $tsFiltro.rango}&rango={$tsFiltro.rango}{/if}">&laquo; Anterior</a>{/if}
						{if $tsPages.next != 0}<a class="btn" href="{$tsConfig.url}/usuarios/?page={$tsPages.next}{if $tsFiltro.online == 'true'}&online=true{/if}{if $tsFiltro.avatar == 'true'}&avatar=true{/if}{if $tsFiltro.sex}&sex={$tsFiltro.sex}{/if}{if $tsFiltro.pais}&pais={$tsFiltro.pais}{/if}{if $tsFiltro.rango}&rango={$tsFiltro.rango}{/if}">Siguiente &raquo;</a>{/if}
					</div>
				</div>
			</div>
		</div>
	</div>

{include "main_footer.tpl"}