<div class="boxy-title">
	<h3>Moderaci&oacute;n de posts</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsAct == ''}
		<p>Recuerda leer el protocolo para poder moderar los post que han sido denunciados por otros usuarios, si te es posible y se puede editar un post no lo borres, <strong>Editalo!</strong></p>
		<hr class="separator" />
		<table class="admin_table">
			<thead>
				<th>Denuncias</th>
				<th>Post</th>
				<th>Fecha</th>
				<th>Raz&oacute;n</th>
				<th>Acciones</th>
			</thead>
			<tbody>
			{if $tsReports}
				{foreach from=$tsReports item=r}
					<tr id="report_{$r.post_id}">
						<td>{$r.total}</td>
						<td><a href="{$r.post_url}" target="_blank">{$r.post_title}</a></td>
						<td>{$r.d_date|hace:true}</td>
						<td>{$tsDenuncias[$r.d_razon]}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/moderacion/posts?act=info&obj={$r.post_id}" title="Ver Detalles">{uicon name="document-justified"}</a>
							<span role="button" onclick="mod.posts.view({$r.post_id}); return false;" title="Ver Post">{uicon name="eye"}</span>
							{if $tsUser->is_admod || $tsUser->permisos.mocdp}
								<span role="button" onclick="mod.reboot({$r.post_id}, 'posts', 'reboot', false); return false;" title="{if $r.post_status == 1}Reactivar Post{else}Desechar denuncias{/if}">{uicon name="refresh-alt"}</span>
							{/if}
							{if $tsUser->is_admod || $tsUser->permisos.moedpo}
								<a href="{$tsConfig.url}/posts/editar/{$r.post_id}" target="_blank" title="Editar Post">{uicon name="pen"}</a>
							{/if}
							{if $tsUser->is_admod || $tsUser->permisos.moep}
								<span role="button" onclick="mod.posts.borrar({$r.post_id}, false); return false" title="Borrar Post">{uicon name="trash"}</span>
							{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				<tr>
					<td colspan="5"><div class="empty">No hay post denunciados hasta el momento.</div></td>
				</tr>
			{/if}
			</tbody>
			<tfoot>
				<th colspan="5">&nbsp;</th>
			</tfoot>
		</table>
	{elseif $tsAct == 'info'}
		<div class="h5 d-flex justify-content-between align-items-center">
			<span>
				<a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/posts/{$tsDenuncia.data.c_seo}/{$tsDenuncia.data.post_id}/{$tsDenuncia.data.post_title|seo}.html" target="_blank">{$tsDenuncia.data.post_title}</a> de <a class="text-decoration-none fw-bold" href="{$tsConfig.url}/perfil/{$tsDenuncia.data.user_name}">{$tsDenuncia.data.user_name}</a>
			</span>
			<span class="admin_actions">
				<span role="button" onclick="mod.posts.view({$tsDenuncia.data.post_id}); return false" title="Ver Post">{uicon name="eye"}</span>
				{if $tsUser->is_admod || $tsUser->permisos.mocdp}
					<span role="button" onclick="mod.reboot({$tsDenuncia.data.post_id}, 'posts', 'reboot', true); return false" title="{if $tsDenuncia.data.post_status == 1}Reactivar Post{else}Desechar denuncias{/if}">{uicon name="refresh-alt"}</span>
				{/if}
				{if $tsUser->is_admod || $tsUser->permisos.moedpo}
					<a href="{$tsConfig.url}/posts/editar/{$tsDenuncia.data.post_id}" target="_blank" title="Editar Post">{uicon name="pen"}</a>
				{/if}
				{if $tsUser->is_admod || $tsUser->permisos.moep}
					<span role="button" onclick="mod.posts.borrar({$tsDenuncia.data.post_id}, 'posts', true); return false" title="Borrar Post"></span>
				{/if}
			</span>
		</div>
												<h2 style="border-bottom:1px dashed #CCC; padding-bottom:5px;">
													  
													 
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