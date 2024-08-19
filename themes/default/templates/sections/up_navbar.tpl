<nav class="up-navbar d-flex justify-content-between align-items-center body-bg z-99 w-100 px-3">

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
						<a title="Ir a Inicio" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $tsPage == 'portal'} active{/if}" rel="internal" href="{$tsConfig.url}/mi/">
							{uicon name="card-view" size="1.5rem"}
							<span class="item--text">Portal</span>
						</a>
					</div>
				{/if}

				<div class="up-menu--item position-relative mb-3 mb-lg-0">
					<a title="Ir a Inicio" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $tsPage == 'home' || $tsPage == 'posts'} active{/if}" rel="internal" href="{$tsConfig.url}/mi/" data-dropopen="posts">
						{uicon name="document-stack" size="1.5rem"}
						<span class="item--text">Posts</span>
					</a>
					<div class="up-dropdown z-99 position-relative position-lg-absolute p-2 rounded body-bg" data-dropname="posts" data-dropdown="false">
						<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'home' || $tsPage == 'posts'} active{/if}" href="{$tsConfig.url}/{if $tsPage == 'home' || $tsPage == 'posts'}posts/{/if}">Inicio</a>
						<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'buscador'} active{/if}" title="Buscador" href="{$tsConfig.url}/buscador/">Buscador</a>
						{if $tsUser->is_member}
							{if $tsUser->is_admod || $tsUser->permisos.gopp}
				  				<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsSubmenu == 'agregar'} active{/if}" title="Agregar Post" href="{$tsConfig.url}/agregar.php">Agregar Post</a>
				  			{/if}
				  			<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'mod-history'} active{/if}" title="Historial de Moderaci&oacute;n" href="{$tsConfig.url}/mod-history/">Historial</a>
				  			{if $tsUser->is_admod || $tsUser->permisos.moacp}
					  			<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'moderacion'} active{/if}" title="Panel de Moderador" href="{$tsConfig.url}/moderacion/" data-total="{$tsNovemods.total}">Moderaci&oacute;n</a>
							{/if}
						{/if}
					</div>
				</div>

				{if $tsConfig.c_fotos_private == 1 || $tsUser->is_member}
					<div class="up-menu--item position-relative mb-3 mb-lg-0">
						<a title="Ir a Fotos" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $tsPage == 'fotos'} active{/if}" rel="internal" href="{$tsConfig.url}/fotos/" data-dropopen="fotos">
							{uicon name="camera-alt" size="1.5rem"}
							<span class="item--text">Fotos</span>
						</a>
						<div class="up-dropdown z-99 position-relative position-lg-absolute p-2 rounded body-bg" data-dropname="fotos" data-dropdown="false">
							<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'fotos' && $tsAction == ''} active{/if}" title="Inicio" href="{$tsConfig.url}/fotos/">Inicio</a>
							{if $tsAction == 'album' && $tsFUser.0 != $tsUser->uid}
								<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color active" title="&Aacute;lbum de {$tsFUser.1}" href="{$tsConfig.url}/buscador/{$tsFUser.1}">&Aacute;lbum de {$tsFUser.1}</a>
							{/if}
							{if $tsUser->is_admod || $tsUser->permisos.gopf}
								<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsAction == 'agregar'} active{/if}" title="Agregar foto" href="{$tsConfig.url}/fotos/agregar.php">Agregar Foto</a></li>
							{/if}
							<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsAction == 'album'} active{/if}" title="Mis fotos" href="{$tsConfig.url}/fotos/{$tsUser->nick}">Mis fotos</a>
						</div>
					</div>
				{/if}
				<div class="up-menu--item position-relative mb-3 mb-lg-0">
					<a title="Ir a Tops" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $tsPage == 'tops'} active{/if}" rel="internal" href="{$tsConfig.url}/top/" data-dropopen="tops">
						{uicon name="trophy" size="1.5rem"}
						<span class="item--text">Tops</span>
					</a>
					<div class="up-dropdown z-99 position-relative position-lg-absolute p-2 rounded body-bg" data-dropname="tops" data-dropdown="false">
						<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsPage == 'tops' && $tsAction != 'posts' && $tsAction != 'usuarios'} active{/if}" title="Inicio" href="{$tsConfig.url}/top/">Inicio</a>
						<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsAction == 'posts'} active{/if}" title="Posts" href="{$tsConfig.url}/top/posts/">Posts</a>
						<a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if $tsAction == 'usuarios'} active{/if}" title="Usuarios" href="{$tsConfig.url}/top/usuarios/">Usuarios</a>
					</div>
				</div>
				{if !$tsUser->is_member}
					<div class="up-menu--item mb-3 mb-lg-0">
						<a title="Registrate!" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 up-menu--register d-flex justify-content-start justify-content-lg-center align-items-center" rel="internal" href="{$tsConfig.url}/registro/">
							{uicon name="door" size="1.5rem"}
							<span class="item--text">Crear cuenta</span>
						</a>
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
				<a title="Identificarme!" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 up-menu--login d-flex justify-content-center align-items-center" rel="internal" href="javascript:login_modal()">
					{uicon name="door-alt" size="1.5rem"}
					<span class="item--text">Iniciar sesión</span>
				</a>
			</div>
		{/if}
	</div>

</nav>