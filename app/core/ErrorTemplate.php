<?php  
	$title = ($type === 'db') ? "Base de datos" : $type;
?>
<html>
<head>
<meta charset="UTF-8" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<title>Error › <?= $title ?></title>
	<style type="text/css">
		*,*::after,*::before{padding:0;margin:0;box-sizing: content-box;}
		html{
			background:#EEE;
		}
		html,body{
			width:100%;
			height:100dvh;
		}
		body{
			font: normal normal 400 1rem/1.5rem 'Poppins',sans-serif;
		}
		#error-page{
			background:#FFF;
			padding: 2rem;
			border-radius: .5rem;
			min-width:650px;
			max-width:780px;
			margin:1rem auto
		}
		#error-page h1{
			font-size: 1.5rem;
			border-bottom: 1px solid #CCC5;
			padding: .5rem;
			margin-bottom: 1rem;
		}
		p.warning {
			background: #FFEEEE;
			color: #D75454;
			border-radius: .5rem;
			text-align: center;
			padding: 1rem;
			margin: 6px 0;
		}
		table{
			border-collapse:collapse;
			text-align:left;
			width:100%;
		}
		table td,
		table th{
			padding: .325rem;
		}
		table tbody td {
			padding:1rem;
			color:#5a5a5a;
			font-weight:normal;
		}
		table tbody .alt td{
			background:#E1EEf4;
			color:#00557F;
		}
		table tbody td:first-child{
			width: 10%;
			font-weight: bold;
		}
		table tbody tr:last-child td{
			font-weight: normal; 
			padding: 0;
		}
		td pre code {
			line-height:1.325rem;
			display:block;
			padding:.875rem;
			font-size:1rem;
			background: #DDD5;
		}
	</style>
</head>
<body>
	<div id="error-page">
		<h1><?= $title ?></h1>
		<?= $error ?>
		<?= $table ?>
	</div>
</body>
</html>