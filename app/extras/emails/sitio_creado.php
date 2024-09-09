<?php

$body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta name=\"viewport\" content=\"width=device-width\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
</head>
<body style=\"width:500px;margin:1rem auto;\">
	<main style=\"margin:1rem auto;border-radius:.325rem;border:2px solid #555;padding:4rem 2rem 3rem 2rem;\">
		<h2 style=\"margin:0;text-align:center;border-bottom:1px solid #CCC;font-weight:bolder;padding-bottom: 2rem;display: block;\">{$site['titulo']} fue instalado correctamente!</h2>
		<p style=\"display: block;text-align:center;font-size:.875rem;margin:1rem 0\">Ahora podr&aacute;s empezar a personalizar, crear todo el contenido para compartir con todos tus amigos!</p>
		<p>Estas son sus credenciales de acceso:
			<div style=\"margin-bottom:1rem;border-radius:.325rem;\"><strong style=\"display: block;\">Usuario:</strong> <span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$user['nickname']}</span></div>
			<div style=\"margin-bottom:1rem;border-radius:.325rem;\"><strong style=\"display: block;\">Contrase&ntilde;a:</strong> <span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$user['password']}</span></div>
		</p>
		<p style=\"display: block;margin: 0;padding: 2rem 0 0 0;font-size:.75rem;color:#666;text-align:center;\">Gracias por usar {$script['forum']} para compartir enlaces.</p>
	</main>
</body>
</html>";