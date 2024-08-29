<div class="boxy-title">
	<h3>Moderaci&oacute;n de usuarios</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsAct == ''}
		<p>No suspendas a un usuario sin una causa razonable, si no tu podr&iacute;as hacerle compa&ntilde;ia.</p>
		
		<div class="row">
			{if $tsReports}
				{foreach from=$tsReports item=r}
					<div class="col-12 col-lg-4" id="report_{$r.obj_id}">
						<div class="rounded border shadow position-relative">
							<div class="p-3">
								<a href="{$tsConfig.url}/perfil/{$r.user_name}" class="fw-semibold h5 text-decoration-none d-block mb-2" uid="{$r.obj_id}">{$r.user_name}</a>
								<span class="small d-block">{$r.d_date|hace:true} | Denuncias {$r.total}</span>
								<span class="fst-italic">{$tsDenuncias[$r.d_razon]}</span>
							</div>
							
							<div class="admin_actions translucent-bg w-100 d-flex justify-content-around align-items-center mt-2 pt-2 pb-1">
								<a href="{$tsConfig.url}/moderacion/users?act=info&obj={$r.obj_id}" title="Ver Detalles">{uicon name="document-justified"}</a>
								<a href="{$tsConfig.url}/perfil/{$r.user_name}" target="_blank" title="Ver Perfil">{uicon name="eye"}</a>
								<span role="button" onclick="mod.users.action({$r.obj_id}, 'aviso', false); return false;" title="Enviar Alerta">{uicon name="warning-hex"}</span>
								{if $tsUser->is_admod || $tsUser->permisos.mosu}
									<span role="button" onclick="mod.users.action({$r.obj_id}, 'ban', false); return false;" title="Suspender Usuario">{uicon name="user-remove"}</span>
								{/if}
								{if $tsUser->is_admod || $tsUser->permisos.modu}
									<span role="button" onclick="mod.reboot({$r.obj_id}, 'users', 'reboot', false); return false" title="Cancelar denuncias">{uicon name="thumbs-down"}</span>
								{/if}
							</div>
						</div>
					</div>
				{/foreach}{else}
			<tr>
				<td colspan="5"><div class="emptyData">No hay usuarios denunciados hasta el momento.</div></td>
			</tr>
			{/if}
		</tbody>
		<tfoot>
			<th colspan="5">&nbsp;</th>
		</tfoot>
	</table>
	{elseif $tsAct == 'info'}
	<h2 style="border-bottom:1px dashed #CCC; padding-bottom:5px;">
		<a href="{$tsConfig.url}/perfil/{$tsDenuncia.data.user_name}">{$tsDenuncia.data.user_name}</a> 
		<span class="floatR admin_actions">
			<a href="#" onclick="mod.users.action({$tsDenuncia.data.user_id}, 'aviso', true); return false;"><img src="{$tsConfig.public}/images/icons/warning.png" title="Enviar Advertencia" /></a>
			<a href="#" onclick="mod.users.action({$tsDenuncia.data.user_id}, 'ban', true); return false;"><img src="{$tsConfig.public}/images/icons/power_off.png" title="Suspender Usuario" /></a>
			<a href="#" onclick="mod.reboot({$tsDenuncia.data.user_id}, 'users', 'reboot', true); return false"><img src="{$tsConfig.public}/images/icons/close.png" title="Cancelar denuncias" /></a>
		</span>
	</h2>
	<table cellpadding="0" cellspacing="0" border="0" class="admin_table" width="100%" align="center">
		<thead>
			<th>Denunciante</th>
			<th>Raz&oacute;n</th>
			<th>Informaci&oacute;n extra</th>
			<th>Fecha</th>
		</thead>
		<tbody>
			{foreach from=$tsDenuncia.denun item=d}
			<tr>
				<td><a href="{$tsConfig.url}/perfil/{$d.user_name}">{$d.user_name}</a></td>
				<td>{$tsDenuncias[$d.d_razon]}</td>
				<td>{$d.d_extra}</td>
				<td>{$d.d_date|hace:true}</td>
			</tr>
			{/foreach}
		</tbody>
		<tfoot>
			<th colspan="5">&nbsp;</th>
		</tfoot>
	</table>
	{/if}
</div>