<nav class="up-navbar d-flex justify-content-between align-items-center body-bg z-99 w-100 px-3 shadow">

	<div class="up-navbar-menu d-flex justify-content-start align-items-center" data-menu="main">

		<div class="up-branding me-2 d-flex justify-content-start align-items-center">
			<div class="up-menu-toggle d-flex justify-content-center align-items-center" aria-labelledby="toggle-menu" role="button" onclick="$('.up-sidebar').toggleClass('show');">
				{uicon name="menu-hamburger" class="pe-none" size="1.75rem"}
			</div>
			<div class="up-brand-logo">
				<a href="{$tsConfig.url}/" rel="interal" title="{$tsConfig.titulo}" class="text-uppercase text-decoration-none fw-bolder py-1 px-2 d-block fs-4">{$tsConfig.titulo}</a>
			</div>
		</div>

			<div class="up-menu--item mb-lg-0">
				<a title="Ir a Moderacion" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 d-flex justify-content-start justify-content-lg-center align-items-center column-gap-2{if $tsPage == 'moderacion'} active{/if}" rel="internal" href="{$tsConfig.url}/{if $tsPage == 'moderacion'}admin{else}moderacion{/if}/">
					{uicon name="hierarchy" size="1.5rem"}
					<span class="item--text">{if $tsPage == 'moderacion'}Administ{else}Mode{/if}ración</span>
				</a>
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
				<a title="Identificarme!" class="up-menu--link text-decoration-none rounded position-relative py-2 py-lg-0 px-3 px-lg-2 up-menu--login d-flex justify-content-center align-items-center" rel="internal" href="{$tsConfig.url}/login/">
					{uicon name="door-alt" size="1.5rem"}
					<span class="item--text">Iniciar sesión</span>
				</a>
			</div>
		{/if}
	</div>

</nav>