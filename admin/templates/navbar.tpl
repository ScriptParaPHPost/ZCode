<header>
	<nav class="up-navbar d-flex justify-content-between align-items-center body-bg z-99 w-100 px-3">

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
				<div class="up-menu--item position-relative monitor">
					<a href="{$tsConfig.url}/monitor/" data-popup="{$tsNots}" title="Monitor de usuario" name="Monitor" class="up-secondary--link me-2 hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
						{uicon name="bell" size="1.5rem"}
					</a>
				</div>
				{* MENSAJES *}
				<div class="up-menu--item position-relative mensajes">
					<a href="{$tsConfig.url}/mensajes/" data-popup="{$tsMPs}" title="Mensajes Personales" name="Mensajes" class="up-secondary--link me-2 hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
						{uicon name="mail" size="1.5rem"}
					</a>
				</div>
				{* ALERTAS *}
				<div class="up-menu--item">
					<a title="{$tsAvisos} aviso{if $tsAvisos != 1}s{/if}" data-popup="{$tsAvisos}" href="{$tsConfig.url}/mensajes/avisos/" class="up-secondary--link hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded py-1 px-2 d-flex justify-content-center align-items-center">
						{uicon name="warning-hex" size="1.5rem"}
					</a>
				</div>
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
</header>