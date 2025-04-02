<div class="up-menu--item position-relative username">
	<a href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}" title="Mi Perfil" class="up-secondary--link hover:main-bg active:main-bg hover:main-color position-relative text-decoration-none rounded w-max-content p-0 ps-lg-1 d-flex justify-content-center align-items-center column-gap-2 up--user" data-dropopen="userpanel">
		<span class="d-none d-lg-block pe-1 ps-2">{$tsUser->nick}</span>
		<img class="avatar avatar-3 dropdown-avatar avatar_loader" src="{$tsUser->use_avatar}" alt="avatar {$tsUser->nick}">
	</a>
	<div class="up-dropdown up-dropdown--secondary position-absolute overflow-hidden p-2 body-bg rounded" style="right: 0;" data-dropname="userpanel" data-dropdown="false">
		{include "MenuUserItem.tpl" title="Mi perfil" link="/perfil/{$tsUser->nick}" icon="person"}
		
		{if $tsUser->is_member && $tsUser->is_admod == 1}
			{include "MenuUserItem.tpl" title="Administraci&oacute;n" link="/admin/" icon="diamond"}
		{/if}
		
		<span role="button" data-dropaction="true" class="up-dropdown--item text-decoration-none fw-semibold rounded py-4 px-2 my-1 hover:main-bg active:main-bg hover:main-color main-bg-color d-flex justify-content-start align-items-center gap-2 mx-0 position-relative" title="Configuraciones">{uicon class="box iconify-28" name="settings"} Configuraciones {uicon class="box iconify-28 position-absolute" style="right:.5rem;" name="chevron_right_double"}</span>
		{include "MenuUserItem.tpl" title="Seguridad" link="/cuenta/seguridad" icon="lock"}
		{include "MenuUserItem.tpl" title="Mis Favoritos" link="/favoritos.php" icon="heart"}
		{include "MenuUserItem.tpl" title="Mis Borradores" link="/borradores.php" icon="trash"}
		{include "MenuUserItem.tpl" title="Cerrar sesión" link="/login-salir.php" icon="exit-right"}

		<div class="up-subdropdown position-absolute w-100 h-100 rounded body-bg px-2">
			<div class="subitem-drop up--close fw-semibold mb-1 d-flex justify-content-between align-items-center" data-dropaction="false">
				<span class="pe-none">Configuraciones</span>
				{uicon class="box iconify-28 pe-none" name="close"}
			</div>
			{foreach $tsMenuCuenta item=item key=i}
				{if $i !== 'seguridad'}
					<a class="subitem-drop up-dropdown--item text-decoration-none fw-semibold rounded py-4 px-2 my-1 hover:main-bg active:main-bg hover:main-color d-flex justify-content-start align-items-center px-2 gap-2" title="{$item.name}" href="{$tsConfig.url}/cuenta/{$i}">{uicon class="box iconify-28" name="{$item.icon}"} {$item.name}</a>
				{/if}
			{/foreach}
		</div>
	</div>
</div>