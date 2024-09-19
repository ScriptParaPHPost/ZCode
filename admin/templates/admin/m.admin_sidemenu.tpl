<h4>General</h4>
<ul class="cat-list">
	<li id="a_main"><a class="nav-item{if $tsAction == ''} active{/if}" href="{$tsConfig.url}/admin/">Centro de Administraci&oacute;n</a></li>
	<li id="a_creditos"><a class="nav-item{if $tsAction == 'creditos'} active{/if}" href="{$tsConfig.url}/admin/creditos">Soporte y Cr&eacute;ditos</a></li>
	<li id="a_creditos"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'actualizacion'} active{/if}" href="{$tsConfig.url}/admin/actualizacion">Sistema de actualización <span class="fw-bold">{$tsFilesTotal}</span></a></li>
</ul>
<h4>Configuraci&oacute;n</h4>
<ul class="cat-list">
	<li id="a_configs"><a class="nav-item{if $tsAction == 'configs'} active{/if}" href="{$tsConfig.url}/admin/configs">Configuraci&oacute;n </a></li>
	<li id="a_registro"><a class="nav-item{if $tsAction == 'registro'} active{/if}" href="{$tsConfig.url}/admin/registro">Configurar registro </a></li>
	{*<li id="a_temas"><a class="nav-item{if $tsAction == 'temas'} active{/if}" href="{$tsConfig.url}/admin/temas">Temas y apariencia</a></li>*}
	<li id="a_news"><a class="nav-item{if $tsAction == 'news'} active{/if}" href="{$tsConfig.url}/admin/news">Noticias</a></li>
	<li id="a_ads"><a class="nav-item{if $tsAction == 'ads'} active{/if}" href="{$tsConfig.url}/admin/ads">Publicidad</a></li>
</ul>
<h4>Control</h4>
<ul class="cat-list">
	<li id="a_medals"><a class="nav-item{if $tsAction == 'medals'} active{/if}" href="{$tsConfig.url}/admin/medals">Medallas</a></li>
	<li id="a_afs"><a class="nav-item{if $tsAction == 'afs'} active{/if}" href="{$tsConfig.url}/admin/afs">Afiliados</a></li>
	<li id="a_stats"><a class="nav-item{if $tsAction == 'stats'} active{/if}" href="{$tsConfig.url}/admin/stats">Estad&iacute;sticas</a></li>
	<li id="a_blacklist"><a class="nav-item{if $tsAction == 'blacklist'} active{/if}" href="{$tsConfig.url}/admin/blacklist">Bloqueos</a></li>
	<li id="a_badwords"><a class="nav-item{if $tsAction == 'badwords'} active{/if}" href="{$tsConfig.url}/admin/badwords">Censuras</a></li>
</ul>
<h4>Contenido</h4>
<ul class="cat-list">
	<li id="a_posts"><a class="nav-item{if $tsAction == 'posts'} active{/if}" href="{$tsConfig.url}/admin/posts">Todos los Posts</a></li>
	<li id="a_fotos"><a class="nav-item{if $tsAction == 'fotos'} active{/if}" href="{$tsConfig.url}/admin/fotos">Todas las Fotos</a></li>
	<li id="a_cats"><a class="nav-item{if $tsAction == 'cats'} active{/if}" href="{$tsConfig.url}/admin/cats">Categor&iacute;as</a></li>
	<li id="a_mensajes"><a class="nav-item{if $tsAction == 'mensajes'} active{/if}" href="{$tsConfig.url}/admin/mensajes">Todos los mensajes</a></li>
</ul>
<h4>Usuarios</h4>
<ul class="cat-list">
	<li id="a_users"><a class="nav-item{if $tsAction == 'users'} active{/if}" href="{$tsConfig.url}/admin/users">Todos los Usuarios</a></li>
	<li id="a_sesiones"><a class="nav-item{if $tsAction == 'sesiones'} active{/if}" href="{$tsConfig.url}/admin/sesiones">Sesiones</a></li>
	<li id="a_nicks"><a class="nav-item{if $tsAction == 'nicks'} active{/if}" href="{$tsConfig.url}/admin/nicks">Cambios de Nicks</a></li>
	<li id="a_rangos"><a class="nav-item{if $tsAction == 'rangos'} active{/if}" href="{$tsConfig.url}/admin/rangos">Rangos de Usuarios</a></li>
</ul>
<h4>Varios</h4>
<ul class="cat-list">
	<li id="a_foro"><a class="nav-item{if $tsAction == 'foro'} active{/if}" href="{$tsConfig.url}/admin/foro">Configurar foro</a></li>
	<li id="a_socials"><a class="nav-item{if $tsAction == 'socials'} active{/if}" href="{$tsConfig.url}/admin/socials">Configurar redes sociales</a></li>
	<li id="a_seo"><a class="nav-item{if $tsAction == 'seo'} active{/if}" href="{$tsConfig.url}/admin/seo">Configurar SEO</a></li>
	<li id="a_database"><a class="nav-item{if $tsAction == 'database'} active{/if}" href="{$tsConfig.url}/admin/database">Base de datos</a></li>
	<li id="a_favicon"><a class="nav-item{if $tsAction == 'favicon'} active{/if}" href="{$tsConfig.url}/admin/favicon">Generar favicon</a></li>
	<li id="a_sitemap"><a class="nav-item{if $tsAction == 'sitemap'} active{/if}" href="{$tsConfig.url}/admin/sitemap">Sitemap</a></li>
</ul>