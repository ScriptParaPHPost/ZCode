<div class="up-menu--item position-relative username">
	<a href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}" title="Mi Perfil" class="up-secondary--link hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded w-max-content p-0 ps-lg-1 d-flex justify-content-center align-items-center column-gap-2 up--user" data-dropopen="userpanel">
		<span class="d-none d-lg-block pe-1 ps-2">{$tsUser->nick}</span>
		<img class="avatar avatar-3 dropdown-avatar avatar_loader" src="{$tsUser->use_avatar}" alt="avatar {$tsUser->nick}">
	</a>
	<div class="up-dropdown up-dropdown--secondary position-absolute overflow-hidden p-2 body-bg rounded" style="right: 0;" data-dropname="userpanel" data-dropdown="false">
		<div class="menu-user d-grid gap-2 mb-2 end-0">
			<img class="avatar avatar-6 dropdown-avatar avatar_loader" src="{$tsUser->use_avatar}" alt="avatar {$tsUser->nick}">
			<div class="menu-userdata p-1 d-flex justify-content-center align-items-start flex-column">
				<a title="Mi perfil" class="fw-bold" href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}">{$tsUser->nick|verificado}</a>
				{$tsUser->email|protected_mail}
			</div>
			<div class="settings d-flex justify-content-center align-items-center" data-dropaction="true" style="cursor: pointer;">
				<span title="Configuración">{uicon name="settings" class="box iconify-32 pe-none"}</span>
			</div>
		</div>
		{if $tsUser->is_member}
			{if $tsUser->is_admod == 1}
				<a class="up-dropdown--item text-decoration-none fw-semibold rounded p-2 mt-2 hover:main-bg active:main-bg hover:main-color d-flex justify-content-start align-items-center gap-2 mx-0{if $tsPage == 'admin'} active{/if}" title="Administraci&oacute;n" href="{$tsConfig.url}/admin/">{uicon name="diamond" class="box iconify-28"} Administraci&oacute;n</a>
			{/if}
		{/if}
		
		<a class="up-dropdown--item text-decoration-none fw-semibold rounded p-2 mt-2 hover:main-bg active:main-bg hover:main-color d-flex justify-content-start align-items-center gap-2 mx-0" title="Mis Favoritos" href="{$tsConfig.url}/favoritos.php">{uicon class="box iconify-28" name="heart"} Mis Favoritos</a>
		<a class="up-dropdown--item text-decoration-none fw-semibold rounded p-2 mt-2 hover:main-bg active:main-bg hover:main-color d-flex justify-content-start align-items-center gap-2 mx-0" title="Mis Borradores" href="{$tsConfig.url}/borradores.php">{uicon class="box iconify-28" name="trash"} Mis Borradores</a>
		<a class="up-dropdown--item text-decoration-none fw-semibold rounded p-2 mt-2 hover:main-bg active:main-bg hover:main-color logout d-flex justify-content-start align-items-center gap-2 mx-0" title="Salir" href="{$tsConfig.url}/login-salir.php" style="cursor: pointer;" >{uicon class="box iconify-28" name="exit-right"} Cerrar sesión</a>
		<div class="up-subdropdown position-absolute w-100 h-100 rounded body-bg px-2">
			<div class="subitem-drop up--close fw-semibold mb-1 d-flex justify-content-between align-items-center" data-dropaction="false">
				<span class="pe-none">Configuraciones</span>
				{uicon class="box iconify-28 pe-none" name="close"}
			</div>
			{foreach $tsMenuCuenta item=item key=i}
				<a class="subitem-drop up-dropdown--item text-decoration-none fw-semibold rounded p-2 mt-2 hover:main-bg active:main-bg hover:main-color d-flex justify-content-start align-items-center px-2 gap-2" title="{$item.name}" href="{$tsConfig.url}/cuenta/{$i}">{uicon class="box iconify-28" name="{$item.icon}"} {$item.name}</a>
			{/foreach}
		</div>
	</div>
</div>