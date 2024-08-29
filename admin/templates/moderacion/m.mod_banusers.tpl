<div class="boxy-title">
	<h3>Moderaci&oacute;n de usuarios</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsUser->is_admod != 1}
		S&oacute;lo puedes quitar la suspenci&oacute;n a los usuarios que t&uacute; hayas suspendido.
	{/if}
	<table class="admin_table">
		<thead>
			{if $tsUser->is_admod || $tsUser->permisos.modu}
				<th>#</th>
			{/if}
			<th>Usuario</th>
			<th><a title="Ascendente" href="{$tsConfig.url}/moderacion/banusers?o=inicio&m=a"><</a>  Suspendido <a title="Descendente" href="{$tsConfig.url}/moderacion/banusers?o=inicio&m=d">></a></th>
			<th><a title="Ascendente" href="{$tsConfig.url}/moderacion/banusers?o=fin&m=a"><</a>  Termina <a title="Descendente" href="{$tsConfig.url}/moderacion/banusers?o=fin&m=d">></a></th>
			<th><a title="Ordenar por moderador ascendente" href="{$tsConfig.url}/moderacion/banusers?o=mod&m=a"><</a>  Lo suspendi&oacute; <a title="Ordenar por moderador descendente" href="{$tsConfig.url}/moderacion/banusers?o=mod&m=d">></a> </th>
		</thead>
		<tbody>
			{if $tsSuspendidos.bans}{foreach from=$tsSuspendidos.bans item=s}
				<tr id="report_{$s.user_id}">
					{if $tsUser->is_admod || $tsUser->permisos.modu}
						<td class="admin_actions text-center">
							<span role="button" onclick="mod.reboot({$s.user_id}, 'users', 'unban', false); return false;" title="Reactivar usuario">{uicon name="refresh-alt" class="pe-none"}</span>
						</td>
					{/if}
					<td>
						<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="d-block fw-semibold text-decoration-none">{$s.user_name}</a>
						<small><em class="fw-semibold">{$s.susp_causa}</em></small>
					</td>
					<td class="text-center">{$s.susp_date|hace:true}</td>
					<td class="text-center">{if $s.susp_termina == 0}Indefinidamente{elseif $s.susp_termina == 1}Permanentemente{else}{$s.susp_termina|date_format:"%d/%m/%Y - %H:%M:%S"}{/if}</td>
					<td class="text-center"><span class="fw-semibold">{$tsUser->getUserName($s.susp_mod)}</span></td>
				 </tr>
			{/foreach}{else}
				<tr>
					<td colspan="6"><div class="empty">No hay usuarios suspendidos hasta el momento.</div></td>
				</tr>
			{/if}
		</tbody>
		<tfoot>
			<td colspan="6">P&aacute;ginas: {$tsSuspendidos.pages}</td>
		</tfoot>
	</table>
</div>