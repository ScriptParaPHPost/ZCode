<?php 

$body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta name=\"viewport\" content=\"width=device-width\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
</head>
<body style=\"width:500px;margin:1rem auto;\">
	<main style=\"margin:1rem auto;border-radius:.325rem;border:2px solid #555;padding:2rem;\">
		<h2 style=\"margin:0;text-align:center;font-weight:bolder;padding-bottom: 2rem;display: block;\">Un lammer ha entrado a su instalador.</h2>
		<div style=\"margin-bottom:1rem;border-radius:.325rem;\">
			<strong style=\"display: block;\">Sitio web:</strong>
			<span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}</span>
		</div>
		<div style=\"margin-bottom:1rem;border-radius:.325rem;\">
			<strong style=\"display: block;\">Usuario:</strong>
			<span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$user['nickname']}</span>
		</div>
		<div style=\"margin-bottom:1rem;border-radius:.325rem;\">
			<strong style=\"display: block;\">Password:</strong>
			<span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$user['password']}</span>
		</div>
		<div style=\"margin-bottom:1rem;border-radius:.325rem;\">
			<strong style=\"display: block;\">Email:</strong>
			<span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$user['email']}</span>
		</div>
		<div style=\"margin-bottom:1rem;border-radius:.325rem;\">
			<strong style=\"display: block;\">Direcci&oacute;n IP:</strong>
			<span style=\"display: block;padding: .75rem .5rem;background-color: #CCC4;margin:.325rem 0;border-radius:.5rem;\">{$_SERVER['REMOTE_ADDR']}</span>
		</div>
		 
	</main>
</body>
</html>";