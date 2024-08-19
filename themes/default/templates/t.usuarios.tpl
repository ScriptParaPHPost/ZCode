{include "main_header.tpl"}

	<div id="resultados" class="resultadosFull"> 
		<div class="row">
			<div class="col-12 col-lg-3">
				{include "m.usuarios_sidebar.tpl"}
			</div>
			<div class="col-12 col-lg-9">
				<div id="showResult" class="resultFull">
					{include "m.usuarios_list.tpl"}
					
					<div class="paginador d-flex justify-content-between align-items-center p-3">
						{if $tsPages.prev != 0}<div style="text-align:left" class="btn"><a href="{$tsConfig.url}/usuarios/?page={$tsPages.prev}{if $tsFiltro.online == 'true'}&online=true{/if}{if $tsFiltro.avatar == 'true'}&avatar=true{/if}{if $tsFiltro.sex}&sex={$tsFiltro.sex}{/if}{if $tsFiltro.pais}&pais={$tsFiltro.pais}{/if}{if $tsFiltro.rango}&rango={$tsFiltro.rango}{/if}">&laquo; Anterior</a></div>{/if}
						{if $tsPages.next != 0}<div style="text-align:right" class="btn"><a href="{$tsConfig.url}/usuarios/?page={$tsPages.next}{if $tsFiltro.online == 'true'}&online=true{/if}{if $tsFiltro.avatar == 'true'}&avatar=true{/if}{if $tsFiltro.sex}&sex={$tsFiltro.sex}{/if}{if $tsFiltro.pais}&pais={$tsFiltro.pais}{/if}{if $tsFiltro.rango}&rango={$tsFiltro.rango}{/if}">Siguiente &raquo;</a></div>{/if}
					</div>
				</div>
			</div>
		</div>
	</div>

{include "main_footer.tpl"}