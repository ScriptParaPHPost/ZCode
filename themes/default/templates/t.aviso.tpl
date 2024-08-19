{include "main_header.tpl"}
	
	<div class="up-show--error">
		<div class="up-show--header">{uicon name="hand"} {$tsAviso.titulo}</div>
		<div class="up-show--body">
			<p>{$tsAviso.mensaje}</p>
			{if $tsAviso.but || $tsAviso.return}
				<hr>
				<input type="button" onclick="{if $tsAviso.but}location.href='{if $tsAviso.link}{$tsAviso.link}{else}{$tsConfig.url}{/if}'{/if}{if $tsAviso.return}history.go(-{$tsAviso.return}){/if}" value="{if $tsAviso.but}{$tsAviso.but}{else}Volver{/if}" class="btn btn-block">
			{/if}
		</div>
	</div>
					 
{include "main_footer.tpl"}