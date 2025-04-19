<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta}
{if $tsAction == 'login'}
<script>
	let TYPE_LOAD = 'page';
	let reload = ['reload=false'];
</script>
{else}
<link rel="preconnect" href="https://www.google.com">
<link rel="preconnect" href="https://www.gstatic.com" crossorigin>
{/if}
{zCode 
	css=["auth.css"] 
	js=[] 
	global=true 
}
</head>
<body class="flex justify-center items-center flex-wrap">

	<main class="mt-3">
		<header class="fixed w-100">
			<div class="logo flex justify-start items-center column-gap-2">
				<img src="{$tsRoutes.logos.128}" alt="{$tsConfig.titulo} - {$tsConfig.slogan}">
				<span>{$tsConfig.titulo}</span>
			</div>
		</header>
		<form method="POST" class="py-5 px-4{if $tsAction == 'registro'} mt-3{/if}"{if $tsAction == 'registro'} disabled{/if}>
			<input type="hidden" name="csrf_token" value="{$csrf_token}">
			{include "t.$tsAction.tpl"}
		</form>
		<footer class="text-align-center">
			<p class="text-align-center block">
				<a href="{$tsConfig.url}/pages/terminos-y-condiciones/" class="link">T&eacute;rminos</a> |
				<a href="{$tsConfig.url}/pages/privacidad/" class="link">Pol&iacute;ticas de privacidad</a> |
				<span>Versi&oacute;n {SCRIPT_VERSION}</span>
			</p>
		</footer>
	</main>
</body>
</html>