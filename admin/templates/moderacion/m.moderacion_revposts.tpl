<div class="boxy-title">
	<h3>Posts desaprobados</h3>
</div>
<div id="res" class="boxy-content" style="position:relative">                          
	{if !$tsPosts.datos}
		<div class="empty hero">No hay posts esperando aprobaci&oacute;n</div>
	{else}
		<table class="admin_table">
			<thead>
				<th>ID</th>
				<th>Post</th>
				<th>Moderador</th>							
				<th>Fecha</th>                                                           
				<th>IP</th>
				<th>Acciones</th>
			</thead>
			<tbody>
				{foreach from=$tsPosts.datos item=p}
					<tr id="report_{$p.post_id}">                                            
						<td class="text-center fw-bold">{$p.post_id}</td>
						<td>
							<a href="{$p.post_url}" target="_blank" class="fw-semibold text-decoration-none d-block">{$p.post_title}</a>
							<small><em>{$p.reason}</em></small>
						</td> 
						<td><a href="{$tsConfig.url}/perfil/{$p.user_name}" class="fw-semibold text-decoration-none">{$p.user_name}</a></td>
						<td class="text-center">{$p.date|hace:true}</td> 
						<td class="text-center">{$p.mod_ip}</td>                					
						<td class="admin_actions">
							<div class="d-flex justify-content-around align-items-center column-gap-2">
								<span role="button" onclick="mod.posts.view({$p.post_id}); return false;" title="Ver Post">{uicon name="eye"}</span>
								<span role="button" onclick="mod.reboot({$p.post_id}, 'posts', 'reboot', false); return false;" title="Reactivar Post">{uicon name="refresh-alt"}</span>
								<a href="{$tsConfig.url}/posts/editar/{$p.post_id}" target="_blank" title="Editar Post">{uicon name="pen"}</a>
								<span role="button" onclick="mod.posts.borrar({$p.post_id}, false); return false" title="Borrar Post">{uicon name="trash"}</span>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<td colspan="8">P&aacute;ginas: {$tsPosts.pages}</td>
			</tfoot>
		</table>
	{/if}								
</div>