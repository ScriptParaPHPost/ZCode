<?php 

$temp = (isset($_GET['template']) && !empty($_GET['template'])) ? htmlentities($_GET['template']) : 'default';

$user = [
	'nickname' => "Miguel92",
	'password' => uniqid(),
	'email' => "example@noreply.com"
];

$site = [
	'titulo' => 'ZCode'
];
$script['forum'] = 'PHPost.es';

include_once __DIR__ . DIRECTORY_SEPARATOR . "$temp.php";
      
// Definir búsqueda y reemplazo
$placeholders = ['{url}', '{titulo}', '{slogan}', '{contenido}', '{asunto}'];

$emailSubject = "Mi super asunto";

$emailBody = "Lorem ipsum, dolor, sit amet consectetur adipisicing elit. Neque suscipit, itaque dolor tempore quisquam laborum adipisci omnis eligendi harum, voluptate, atque perspiciatis quo vel, earum nulla labore. Ipsa in eius rem magni hic, excepturi, provident corporis consectetur porro, architecto laboriosam dolores sint vero fugit doloribus delectus molestias! Ab, nulla ut. Rerum voluptates qui maiores praesentium similique dolor, dolorum omnis earum explicabo quibusdam dicta possimus, facere temporibus quam! Provident officia perspiciatis eveniet eum velit dolorem, atque porro, ullam ab quae, cum tenetur. Error dolor expedita, iure inventore minus fugit aperiam beatae et recusandae eos aliquid fugiat dignissimos, sapiente, vitae. Aut, suscipit?";

// Por lo que vamos a reemplazar
$replacements = [
   htmlspecialchars('http://localhost/ZCode/', ENT_QUOTES, 'UTF-8'), 
   htmlspecialchars('ZCode', ENT_QUOTES, 'UTF-8'), 
   htmlspecialchars('Mi super eslogan', ENT_QUOTES, 'UTF-8'), 
   htmlspecialchars($emailBody, ENT_QUOTES, 'UTF-8'), 
   htmlentities($emailSubject, ENT_QUOTES | ENT_HTML401, 'UTF-8')
];

// Reemplazar contenido en la plantilla
echo str_replace($placeholders, $replacements, $plantilla);