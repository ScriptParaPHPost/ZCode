<div class="categoriaUsuario d-flex justify-content-start align-items-center column-gap-2 border py-2 ps-2 pe-3 mt-2 rounded">
	<img class="avatar avatar-2 rounded-circle" loading="lazy" src="{$u.avatar}"/>
	<span class="d-flex justify-content-between align-items-center flex-grow-1">
		<a class="fw-semibold text-decoration-none" href="{$tsConfig.url}/perfil/{$u.user_name}">{$u.user_name|verificado}</a> 
		<span class="up-badge" style="height: 1.125rem; line-height: 1rem;">{$u.total}</span>
	</span>
</div>