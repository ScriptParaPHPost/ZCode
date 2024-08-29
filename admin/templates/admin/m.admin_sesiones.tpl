<div class="boxy-title">
	<h3>Administrar Sesiones</h3>
</div>
<div id="res" class="boxy-content" style="position:relative">
	{if !$tsAct}
		{if !$tsAdminSessions.data}
			<div class="empty hero">No hay usuarios o visitantes conectados</div>
		{else}
			<table class="admin_table">
				<thead>
					<th>ID</th>
					<th>Usuario</th>
					<th>IP</th>
					<th>Fecha</th>
					<th>Auto login</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					{foreach from=$tsAdminSessions.data item=s}
						<tr id="sesion_{$s.session_id}">
							<td>{$s.session_id}</td>
							<td align="left">{if $s.user_name}<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="fw-semibold text-decoration-none">{$s.user_name}</a>{else}Visitante{/if}</td>
							<td><a href="{$tsConfig.url}/moderacion/buscador/1/1/{$s.session_ip}" class="geoip" target="_blank">{$s.session_ip}</a></td>
							<td class="text-center">{$s.session_time|hace:true}</td>
							<td>{if $s.session_autologin == 0}<font color="red">NO</font>{else}<font color="green">S&Iacute;</font>{/if}</td>
							<td class="admin_actions text-center">
	                     <span role="button" onclick="admin.sesiones.borrar('{$s.session_id}'); return false" title="Cerrar sesi&oacute;n de {if $s.user_name}{$s.user_name}{else}este visitante{/if}">{uicon name="cross-circle"}</span>
							</td>
						</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<td colspan="7">{$tsAdminSessions.pages}</td>
				</tfoot>
			</table>
		{/if}
	{/if}
</div>