{include "main_header.tpl"}
	
	<!-- <div class="empty">Sección en construcción</div> -->
	{if $tsAction == 'inicio' || $tsAction == 'filtro'}
		
		<div class="row">
			<div class="col-12 col-lg-9">
				{include "m.ticket_mis-tickets.tpl"}
			</div>
			<div class="col-12 col-lg-3">
				{include "m.ticket_search.tpl"}
				{include "m.ticket_filtro.tpl"}
			</div>
		</div>

	{else}
		{include "m.ticket_$tsAction.tpl"}
	{/if}
					 
{include "main_footer.tpl"}