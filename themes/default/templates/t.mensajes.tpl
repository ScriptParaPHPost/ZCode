{include "main_header.tpl"}

	<div class="row">
		<div class="col-12 col-lg-3">
			{include "m.mensajes_menu.tpl"}
		</div>
		<div class="col-12 col-lg-9">
			<div class="position-relative">
				<div style="display: none;" id="m-mensaje"></div>
				<div class="boxy">
					<div class="d-flex justify-content-between align-items-center translucent-bg p-2 rounded mb-2">
						<h3 class="m-0 p-0 h5">Mensajes</h3>
						<form method="get" action="{$tsConfig.url}/mensajes/search/">
							<input type="text" name="qm" placeholder="Buscar en Mensajes" title="Buscar en Mensajes" value="{$tsMensajes.texto}" class="search_mp border rounded py-1 px-2 body-bg"/>
						</form>
					</div>
					<div class="boxy-content" id="mensajes">
						{if $tsAction == '' || $tsAction == 'enviados' || $tsAction == 'respondidos' || $tsAction == 'search'}
							{include "m.mensajes_list.tpl"}
						{else}
							{include "m.mensajes_$tsAction.tpl"}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
		
{include "main_footer.tpl"}