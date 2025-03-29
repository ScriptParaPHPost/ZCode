<!DOCTYPE html>
<html lang="es" {$tsThemeSettings}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta 
	facebook=true 
	twitter=true 
	analytics=false 
	robots=[
		'active' => true, 
		'data' => [
			'name' => 'robots', 
			'content' => 'index, follow'
		]
	]
}
{zCode 
	css=["base.css","theme.css"] 
	js=["acciones.js","dropdown.js"] 
	global=true 
	aditional=true 
	notifica=true
}
</head>
<body>
	
	<div class="UIBeeper" id="BeeperBox"></div>

	<div id="pagebox-one" class="{if $tsThemeBox}no-{/if}container">
		<main id="brandday" class="{if !$tsThemeBox}my-3 rounded{/if}">
			{include "head_header.tpl"}
			<section id="pagebox-two" class="container{if $tsThemeBox}-fluid{/if} py-3 px-3">
				{include "head_noticias.tpl"}
				<a name="cielo"></a>