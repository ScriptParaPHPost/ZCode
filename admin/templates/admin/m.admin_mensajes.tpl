<div class="boxy-title">
	<h3>{if $tsAct == ''}Control de Mensajes{elseif $tsAct == 'leer'}Leer Mensaje{/if}</h3>		
</div>
<div id="res" class="boxy-content" style="position:relative">
	{if !$tsAct}
		{if !$tsControlMensajes.data}
			<div class="empty hero">No hay mensajes.</div>
		{else}
			<div style="overflow-x:auto;">
				<table cellpadding="0" cellspacing="0" border="0" class="admin_table" width="100%" align="center">
					<thead>
						<th>Rango</th>
						<th>De:</th>
						<th>Para:</th>
						<th>Enviado</th>
						<th>Asunto:</th>
						<th>Acciones</th>
					</thead>
					<tbody>
						{foreach from=$tsControlMensajes.data item=m}
						<tr id="mp_{$m.mp_id}">
							<td><img title="{$m.r_name}" src="{$m.r_image}" class="avatar avatar-2" /></td>
							<td><a href="{$tsConfig.url}/perfil/{$m.user_name}" class="text-decoration-none fw-semibold" style="color:#{$m.mp_from_color};">{$m.user_name}</a></td>
							<td><a href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.mp_to)}" class="text-decoration-none fw-semibold" style="color:#{$m.mp_to_color};">{$tsUser->getUserName($m.mp_to)}</a></td>
							<td>{$m.mp_date|hace:true}</td>
							<td title="{$m.mp_subject} - {$m.mp_preview}">{$m.mp_subject|truncate:40}</td>
							<td class="admin_actions">
								<a href="{$tsConfig.url}/admin/mensajes?act=leer&mpid={$m.mp_id}" title="Leer Mensajes">{uicon name="eye" class="pe-none"}</a>
								{if $m.user_id!=$tsUser->uid}
									<span role="button" onclick="mod.users.action({$m.user_id}, 'aviso', false); return false;" title="Enviar Alerta">{uicon name="warning-triangle" class="pe-none"}</span>
								{/if}
								<span role="button" onclick="admin.mp.borrar('{$m.mp_id}'); return false" title="Eliminar Mensaje">{uicon name="trash-alt" class="pe-none"}</span>
							</td>
						</tr>
						{/foreach}
					</tbody>
					<tfoot>
						<td colspan="8" class="pag-compl">{$tsControlMensajes.pages}</td>
					</tfoot>
				</table>
			</div>
		{/if}
	{elseif $tsAct == 'leer'}
		<div class="head">
			<div class="h4">Asunto: <strong>{$tsDatamp.mp_subject}</strong></div>
			<small>Entre <a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/perfil/{$tsDatamp.from_user_name}">{$tsDatamp.from_user_name}</a> y <a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/perfil/{$tsDatamp.to_user_name}">{$tsDatamp.to_user_name}</a></small>
		</div>
		<div class="content">
			{foreach from=$tsLeermp item=m}
				{if !$m.mr_id}
			  		<div class="empty hero">Respuesta eliminada</div>
			  {else}
			  		<div class="position-relative border shadow mb-3 p-3" id="rmp_{$m.mr_id}">
					  	<div class="position-absolute" style="top: 1rem;right: 1rem;">
						   {if $m.mr_from != $tsUser->uid}
						   	<span role="button" onclick="mod.users.action({$m.mr_from}, 'aviso', false); return false;" title="Enviar Alerta">{uicon name="warning-triangle" class="pe-none"}</span>
						   {/if}
						   <span role="button" onclick="admin.rmp.borrar('{$m.mr_id}'); return false"title="Eliminar Respuesta">{uicon name="trash-alt" class="pe-none"}</span>
					  	</div>
					  	<div class="d-grid column-gap-2" style="grid-template-columns: 3rem 1fr;">
						  	<a href="{$tsConfig.url}/perfil/{$m.from_user_name}" class="avatar avatar-5 rounded shadow overflow-hidden">
						  		<img src="{$m.avatar}" class="w-100 h-100 object-fit-cover">
						  	</a>
						  	<div>
							  	<span class="d-block">
							  		<a style="color:#{$m.from_user_name};" class="text-decoration-none fw-semibold" href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.mr_from)}">{$tsUser->getUserName($m.mr_from)}</a> - <time class="fst-italic small">{$m.mr_date|hace:true}</time>
							  	</span>
							  	<div class="bodymp">{$m.mr_body}</div>
							</div>
						</div>
				  </div>
			  {/if}
			{/foreach}
		</div>
	{/if}
</div>