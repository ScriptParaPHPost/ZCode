<?php

// AÑO ACTUAl
$tiempo = date('Y');
$plantilla = <<<EMAIL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap");
body{font-family:"Roboto",sans-serif;}
</style>
</head>
<body style="margin:0;padding:0;scroll-behavior: smooth;">
<table style="width: 100%;">
	<thead style="border-bottom:1px solid #CCC;background:#CCC3;">
		<th colspan="3" style="height:120px;text-align:left;padding:16px 32px">
			<span style="display:block;font-size: 3rem;margin-bottom:-22px;">{titulo}</span>
			<small>{slogan}</small>
		</th>
	</thead>
	<tbody>
		<tr>
			<td class="sidebar" style="width:200px;"></td>
			<td class="main">{contenido}</td>
			<td class="sidebar" style="width:200px;"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" style="padding:16px;text-align:right;">
				<p style="margin:0;padding:0;font-size:20px;font-weight:bolder;margin-bottom:8px;">Para m&aacute;s informaci&oacute;n</p>
				<p style="margin:0;padding:0;">
					<a style="text-decoration:none;font-weight:bold;color:#2588BC;display:inline-block;" href="https://phpost.es/" target="_blank">PHPost</a>&nbsp;-&nbsp;
					<a style="text-decoration:none;font-weight:bold;color:#2588BC;display:inline-block;" href="{url}/pages/terminos-y-condiciones/">Terminos & condiciones</a>&nbsp;-&nbsp;
					<a style="text-decoration:none;font-weight:bold;color:#2588BC;display:inline-block;" href="{url}/pages/privacidad/">Privacidad</a>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:center;padding: 16px 0;border-top:1px solid #CCC;margin-top:16px;background:#CCC5;">
				<p style="margin:0;padding:0;">El Staff de <strong>{titulo}</strong></p>
				<p style="margin:0;padding:0;font-size:.75rem;margin-top:8px;">Copyright 2022-$tiempo</p>
			</td>
		</tr>
	</tfoot>
</table>
</body>
</html>
EMAIL;