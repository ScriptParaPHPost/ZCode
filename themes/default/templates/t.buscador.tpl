{include "main_header.tpl"}
	
	{include "m.form_search.tpl"}

	{if $tsQuery || $tsAutor || $tsCategory}
		{if $tsEngine === 'google'}
			<div class="google">
				{if !empty($tsConfig.ads_search)}
					<!-- https://programmablesearchengine.google.com/cse/all -->
					<script async src="https://cse.google.com/cse.js?cx={$tsConfig.ads_search}"></script>
					<div class="gcse-search"></div>
				{else}
					{if $tsUser->is_member and $tsUser->is_admod}
						<div class="empty">No configuraste el buscardor desde google, tienes que agregar el ID del buscador, para ello accede a <a href="https://programmablesearchengine.google.com/cse/all">https://programmablesearchengine.google.com/cse/all</a></div>
					{else}
						<div class="empty">El buscador de google no esta configurado aún!</div>
					{/if}
				{/if}
			</div>
		{elseif $tsEngine === 'web' || $tsEngine === 'tags'}
			{include "m.buscador_resultados.tpl"}
		{elseif empty($tsResults.data)}
	      <div class="welcome sinresultados">
	         <h4>Lo siento, no se encontraron resultados...</h4>
	      </div>
	   {/if}
   {else}
      <div class="welcome sinbuscar">
         <h4>¿Que tipo de busqueda quieres realizar?</h4>
      </div>
   {/if}

{include "main_footer.tpl"}