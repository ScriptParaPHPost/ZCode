<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" data-theme-color="default">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
<link href="{$tsConfig.assets}/images/favicon/logo-16.webp?t={$smart.now}" rel="shortcut icon" type="image/webp" sizes="16x16" />
<link href="{$tsConfig.assets}/images/favicon/logo-32.webp?t={$smart.now}" rel="shortcut icon" type="image/webp" sizes="32x32" />
<link href="{$tsConfig.assets}/images/favicon/logo-64.webp?t={$smart.now}" rel="shortcut icon" type="image/webp" sizes="64x64" />
{zCode css=["base.css","dashboard.css"]}
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
				<div class="links-left d-flex justify-content-center align-items-center gap-2">
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/ayuda/" title="Ayuda">Ayuda</a>
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/chat/" title="Chat">Chat</a>
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/contacto/" title="Contacto">Contacto</a>  
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/protocolo/" title="Protocolo">Protocolo</a>
				</div>
				<div class="links-right d-flex justify-content-center align-items-center gap-2">
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/terminos-y-condiciones/" title="T&eacute;rminos y condiciones">T&eacute;rminos y condiciones</a>
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/privacidad/" title="Privacidad de datos">Privacidad de datos</a>
					<a class="d-block d-lg-inline-block text-decoration-none fw-semibold" rel="internal" href="{$tsConfig.url}/pages/dmca/" title="Report Abuse - DMCA">Report Abuse - DMCA</a>
				</div>
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
{zCode js=["acciones.js","dropdown.js"] scriptGlobal=true remove="colores;themes"}
{if $tsPage == 'admin' && $tsAction == ''}
	{zCode js="versiones.js"}
{/if}
{zCode js="$tsAction.js"}
</body>
</html>