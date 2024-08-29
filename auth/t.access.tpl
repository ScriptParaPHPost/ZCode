<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" data-theme="light" data-theme-color="default">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta facebook=true twitter=true}
{zCode css=["base.css"]}
</head>
<body class="vh-100 align-content-center">

	<div id="mydialog"></div>

	<main class="my-3 rounded shadow mx-auto">
		<section>
			{if $tsAction == 'login'}
				{assign "pageLogin" "active"}
				{include "t.php_files/p.login.form.tpl"}
			{else}
				{include "t.$tsAction.tpl"}
			{/if}
		</section>
	</main>

	{if $tsAction == 'login'}
		<script>
			let reload = ['reload=false'];
		</script>
	{else}
		<link rel="preconnect" href="https://www.google.com">
		<link rel="preconnect" href="https://www.gstatic.com" crossorigin>
	{/if}
	{zCode js=[] scriptGlobal=true}
</body>
</html>