<?php

/**
 * Generamos el icono
*/

$array = [
	'background' => '0D8ABC',
	'color' => 'fff',
	'name' => $tsCore->settings['titulo'],
	'size' => 128, 
	'font-size' => '0.50',
	'bold' => true,
	'format' => 'png'
];
foreach ($array as $key => $value) $params[$key] = "$key=$value";
// URL COMPLETO
$icono = 'https://ui-avatars.com/api/?' . join('&', $params);
// AÑO ACTUAl
$tiempo = date('Y');
$plantilla = <<<EMAIL
<html>
<head>
<style>
html,body,table,table>*,p,h1,h2,h3,h4,h5,h6{padding:0;margin:0}
.contenido{min-width:400px;max-width:100%;margin:0 auto;background:#EEE}
table{width:100%}
table thead th{background-color:#{$array['background']};padding:20px 0}
table thead th img{width:{$array['size']}px;border-radius:.3rem}
tfoot>td.foot{width:calc(100%/2);padding:10px;}
td.sidebar{width:20px}
td.main{padding:20px}
table tbody p{display:block;font-size:16px;padding:4px 0;line-height:18px}
table tfoot{background-color:#EEE3}
table tfoot td{padding:8px 0;}
table tfoot a{color:#2D68C0;font-weight:bold;text-decoration:none;display:inline-block;}
.small{font-size:12px;}
hr{border:transparent;border-bottom:1px solid #CCC5;width:80%;margin:1rem auto}
</style>
</head>
<body class="contenido">
<table>
	<thead>
		<th colspan="3">
			<img src="$icono" alt="{2}">
		</th>
	</thead>
	<tbody>
		<tr>
			<td class="sidebar"></td>
			<td class="main">{3}</td>
			<td class="sidebar"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" style="text-align:left;padding-left:10px;">
				<p>Para m&aacute;s informaci&oacute;n</p>
				<p><a href="https://www.phpost.net/foro/" target="_blank">PHPost Oficial</a> - <a href="https://phpost.es/" target="_blank">PHPost</a></p>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<p>
					<a href="{1}/pages/terminos-y-condiciones/">Terminos & condiciones</a>&nbsp;-&nbsp;
					<a href="{1}/pages/privacidad/">Privacidad</a>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="background-color:#EEE3">
				<p style="text-align:center;text-transform:uppercase;font-weight: 600;">El Staff de <b>{2}</b></p>
				<p style="text-align:center;text-transform:uppercase;font-weight: 600;font-size: 12px;">Copyright 2022-$tiempo</p>
			</td>
		</tr>
	</tfoot>
</table>
</body>
</html>
EMAIL;