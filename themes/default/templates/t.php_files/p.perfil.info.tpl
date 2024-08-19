1:
<div id="perfil_info" status="activo">
	 <div class="widget big-info clearfix">
		<div class="title-w border p-2 rounded d-flex justify-content-between align-items-center">
			<h3 class="m-0 fs-5">Informaci&oacute;n de {$tsUsername}</h3>
		</div>
		<div class="p-2">
			<div class="d-flex justify-content-between align-items-center py-1">
				<span>Pa&iacute;s</span>
				<strong>{$tsPais}</strong>
			</div>
			{if $tsPerfil.p_sitio}
				<div class="d-flex justify-content-between align-items-center py-1">
					<span>Sitio Web</span>
					<strong><a rel="nofollow" class="text-decoration-none fw-semibold" href="{$tsPerfil.p_sitio}">{$tsPerfil.p_sitio}</a></strong>
				</div>
			{/if}
			<div class="d-flex justify-content-between align-items-center py-1">
				<span>Es usuario desde</span>
				<strong>{$tsPerfil.user_registro|hace:true}</strong>
			</div>
			<div class="d-flex justify-content-between align-items-center py-1">
				<span>&Uacute;ltima vez activo</span>
				<strong>{$tsPerfil.user_lastactive|hace:true}</strong>
			</div>
		</div>
	</div>
</div>