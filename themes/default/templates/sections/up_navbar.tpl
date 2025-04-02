<nav class="up-navbar d-flex justify-content-between align-items-center body-bg z-99 px-3">

	<div class="up-navbar-menu d-flex justify-content-start align-items-center" data-menu="main">

		<div class="up-branding me-2 d-lg-none d-flex justify-content-start align-items-center">
			<div class="up-menu-toggle d-flex justify-content-center align-items-center" aria-labelledby="toggle-menu" role="button" onclick="$('.up-collapse').toggleClass('show');">
				{uicon name="menu-hamburger" class="pe-none" size="1.75rem"}
			</div>
			<div class="up-brand-logo">
				<a href="{$tsConfig.url}/" rel="interal" title="{$tsConfig.titulo}" class="text-uppercase text-decoration-none fw-bolder py-1 px-2 d-block fs-4">{$tsConfig.titulo}</a>
			</div>
		</div>

		<div class="up-collapse position-absolute position-lg-relative body-bg">

			<div class="up-menu p-2 p-lg-0 d-block d-lg-flex justify-content-start align-items-center column-gap-2">
				{if $tsConfig.c_allow_portal && $tsUser->is_member == true}
					<div class="up-menu--item mb-3 mb-lg-0">
						{include "MenuItemLink.tpl" title="Ir a Portal" inPage=($tsPage == "portal") link="/mi/" icon="card-view" label="Portal"}
					</div>
				{/if}

				<div class="up-menu--item position-relative mb-3 mb-lg-0">
					{if $tsPage != 'home' && $tsPage != 'posts'}
					   {assign var="link" value="/posts/"}
					{else}
					   {assign var="link" value="/"}
					{/if}
					{include "MenuItemLink.tpl" title="Ir a Inicio" inPage=($tsPage == 'home' || $tsPage == 'posts') link=$link icon="document-stack" label="Posts" dropopen="posts"}
					{include file="MenuItemDropdown.tpl" dropdownName="posts" items=[
					   [
					      "title" => "Inicio", 
					      "href" => ($tsPage == 'home' || $tsPage == 'posts' ? "/posts/" : "/"), 
					      "active" => ($tsPage == 'home' || $tsPage == 'posts')
					   ], [
					      "title" => "Buscador", 
					      "href" => "/buscador/", 
					      "active" => ($tsPage == 'buscador')
					   ], [
					      "title" => "Agregar Post", 
					      "href" => "/agregar/", 
					      "active" => ($tsSubmenu == 'agregar'), 
					      "condition" => ($tsUser->is_member && ($tsUser->is_admod || $tsUser->permisos.gopp))
					   ], [
					      "title" => "Historial de Moderación", 
					      "href" => "/mod-history/", 
					      "active" => ($tsPage == 'mod-history'), 
					      "condition" => $tsUser->is_member
					   ], [
					      "title" => "Moderación", 
					      "href" => "/moderacion/", 
					      "active" => ($tsPage == 'moderacion'), 
					      "condition" => ($tsUser->is_admod || $tsUser->permisos.moacp), 
					      "dataTotal" => "{$tsNovemods.total}"
					   ]
					]}
				</div>

				{if $tsConfig.c_fotos_private == 1 || $tsUser->is_member}
					<div class="up-menu--item position-relative mb-3 mb-lg-0">
						{include "MenuItemLink.tpl" title="Ir a Fotos" inPage=($tsPage == 'fotos') link="/fotos/" icon="camera-alt" label="Fotos" dropopen="fotos"}
						{include file="MenuItemDropdown.tpl" dropdownName="fotos" items=[
							[
						   	"title" => "Inicio", 
						   	"href" => "/fotos/", 
						   	"active" => ($tsPage == 'fotos' && $tsAction == '')
						   ], [
						      "title" => "&Aacute;lbum de {$tsFUser.1}", 
						      "href" => "/buscador/{$tsFUser.1}", 
						      "condition" => ($tsAction == 'album' && $tsFUser.0 != $tsUser->uid)
						   ], [
						      "title" => "Agregar foto", 
						      "href" => "/fotos/agregar.php", 
						      "active" => ($tsAction == 'agregar'), 
						      "condition" => ($tsUser->is_admod || $tsUser->permisos.gopf)
						   ], [
						      "title" => "Mis fotos", 
						      "href" => "/fotos/{$tsUser->nick}", 
						      "active" => ($tsAction == 'album')
						   ]
						]}
					</div>
				{/if}
				<div class="up-menu--item position-relative mb-3 mb-lg-0">
					{include "MenuItemLink.tpl" title="Ir a Tops" inPage=($tsPage == 'tops') link="/top/" icon="trophy" label="Tops" dropopen="tops"}
					{include file="MenuItemDropdown.tpl" dropdownName="tops" items=[
						[
					   	"title" => "Inicio", 
					   	"href" => "/top/", 
					   	"active" => ($tsPage == 'tops' && $tsAction != 'posts' && $tsAction != 'usuarios')
					   ], [
					      "title" => "Posts", 
					      "href" => "/top/posts/", 
					      "active" => ($tsAction == 'posts')
					   ], [
					      "title" => "Usuarios", 
					      "href" => "/top/usuarios/", 
					      "active" => ($tsAction == 'usuarios')
					   ]
					]}
				</div>
				{if !$tsUser->is_member}
					<div class="up-menu--item mb-3 mb-lg-0">
						{include "MenuItemLink.tpl" title="Registrate" link="/registro/" icon="door" label="Crear cuenta"}
					</div>
				{/if}
			</div>
		</div>
	</div>
	
	<div class="up-navbar-menu d-flex justify-content-end align-items-center" data-menu="secondary">
		{if $tsUser->is_member}
			{* NOTIFICACIONES *}
			<div class="up-menu--item position-relative monitor" data-badge="false">
				<a href="{$tsConfig.url}/monitor/" data-popup="{$tsNots}" onclick="notifica.last(); return false" title="Monitor de usuario" name="Monitor" class="up-secondary--link me-2 hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
					{uicon name="bell" size="1.5rem"}
				</a>
				<div class="up-dropdown position-absolute up-dropdown--secondary z-99 p-0 rounded body-bg" style="left: calc(-320px / 2);" id="mon_list" data-dropdown="false">
					<div class="up-dropdown--header py-1 px-2 d-flex justify-content-between align-items-center">
						<a class="d-block text-decoration-none" rel="internal" href="{$tsConfig.url}/monitor/">Notificaciones</a>
						<a class="d-flex justify-content-center align-items-center text-decoration-none" href="{$tsConfig.url}/monitor/" title="Ver m&aacute;s notificaciones">{uicon name="archive"}</a>
					</div>
					<ul class="up-droplist px-2 overflow-y-auto list-unstyled" data-list="nots"></ul>
			 	</div>
			</div>
			{* MENSAJES *}
			<div class="up-menu--item position-relative mensajes" data-badge="false">
				<a href="{$tsConfig.url}/mensajes/" data-popup="{$tsMPs}" onclick="mensaje.last(); return false" title="Mensajes Personales" name="Mensajes" class="up-secondary--link me-2 hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
					{uicon name="mail" size="1.5rem"}
				</a>
				<div class="up-dropdown position-absolute up-dropdown--secondary z-99 p-0 rounded body-bg" style="left: calc(-320px / 2);" id="mp_list" data-dropdown="false">
					<div class="up-dropdown--header py-1 px-2 d-flex justify-content-between align-items-center">
						<a class="d-block text-decoration-none" rel="internal" href="{$tsConfig.url}/mensajes/">Mensajes</a>
						<a class="d-flex justify-content-center align-items-center text-decoration-none" href="{$tsConfig.url}/mensajes/" title="Ver todos los mensajes">{uicon name="archive"}</a>
					</div>
					<ul class="up-droplist px-2 overflow-y-auto list-unstyled" data-list="mps"></ul>
			 	</div>
			</div>
			{* ALERTAS *}
			{if $tsAvisos}
				<div class="up-menu--item" data-badge="true">
					<a title="{$tsAvisos} aviso{if $tsAvisos != 1}s{/if}" data-popup="{$tsAvisos}" href="{$tsConfig.url}/mensajes/avisos/" class="up-secondary--link hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
						{uicon name="warning-hex" size="1.5rem"}
					</a>
				</div>
			{/if}
			{include "head_menu_user.tpl"}
		{else}
			<div class="up-menu--item">
				{include "MenuItemLink.tpl" title="Identificarme" link="/login/" icon="door-alt" label="Iniciar sesión"}
			</div>
		{/if}
	</div>

</nav>