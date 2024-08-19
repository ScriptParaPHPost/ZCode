{include "main_header.tpl"}

<div class="post-deleted post-privado clearbeta">
	<div class="content-splash d-block d-lg-grid gap-3">
		<div class="title-top d-flex justify-content-center align-items-center flex-column text-center py-3">
			<h3 class="m-0">{if $tsType == 'post'}Este post es privado, s&oacute;lo los usuarios registrados de {$tsConfig.titulo} pueden acceder.{else}Registrate en {$tsConfig.titulo}{/if}</h3>
			{if $tsType == 'post'}<span class="d-block mt-3">Pero no te preocupes, tambi&eacute;n puedes formar parte de nuestra gran familia. <a title="Reg&iacute;strate!" href="{$tsConfig.url}/registro/" class="text-decoration-none fw-bold">Reg&iacute;strate</a>!</span>{/if}
		</div>
		<script>
			let reload = ['reload=true'];
		</script>
		{include "access/t.login.tpl"}
	</div>
</div>
{include "main_footer.tpl"}