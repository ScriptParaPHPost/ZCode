<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" {$tsThemeSettings}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta 
	facebook=false 
	twitter=false 
	analytics=false 
	robots=[
		'active' => false, 
		'data' => [
			'name' => 'robots', 
			'content' => 'index, follow'
		]
	]
}
{zCode css=["base.css","dashboard.css"] js=["acciones.js","dropdown.js"] global=true}
</head>
<body>

	<div class="UIBeeper" id="BeeperBox"></div>

	<main id="brandday" class="">
		{include "navbar.tpl"}
		<section class="pt-5">
		  	<aside class="up-sidebar body-bg">
		  		{include "m.{$tsPage}_sidemenu.tpl"}
		  	</aside>
		  	<div class="boxy">
		  		{include "m.{$tsPage}_$tsAction.tpl"}
			</div>
		</section>
		<footer class="py-3">
			<div class="links p-2">
				{assign "listsFooter" [
					'left' => [
						'ayuda' => 'Ayuda',
						'chat' => 'Chat',
						'contacto' => 'Contacto',
						'protocolo' => 'Protocolo'
					],
					'right' => [
						'terminos-y-condiciones' => 'T&eacute;rminos y condiciones',
						'privacidad' => 'Privacidad de datos',
						'dmca' => 'Report Abuse - DMCA'
					]
				]}
				{foreach $listsFooter key=cl item=list}
					<div class="links-{$cl} d-flex justify-content-center align-items-center gap-2">
						{foreach $list key=page item=title}
							{include "LinkFooter.tpl" url="pages/$page/" title=$title}
						{/foreach}
					</div>
				{/foreach}
			</div>
			<div class="footer-copyright text-center">
				<a href="{$tsConfig.url}" rel="internal" title="{$tsConfig.titulo} - {$tsConfig.slogan}">{$tsConfig.titulo}</a> &copy; {$smarty.now|date_format:"Y"}
			</div>
			<template id="verification-install">
				<p>Esto es solamente para verificar tú versión con la versión actual.</p>
				<p>Si remueves esto, no recibirás información sobre actualizaciones y cambios!</p>
				<input type="hidden" name="verification-code" value="{$tsVerification}">
			</template>
		</footer>
	</main>


{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsNovemods.total}
	<div id="stickymsg" class="position-fixed py-1 px-3 small toast-box toast-box--danger fw-semibold" style="cursor:default;">Hay <span class="fw-bold">{$tsNovemods.total} contenido{if $tsNovemods.total != 1}s{/if}</span> esperando revisi&oacute;n</div>
{/if}
</body>
</html>