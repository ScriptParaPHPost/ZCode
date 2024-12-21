<div class="boxy-title">
	 <h3>Administrar Lista Negra</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
	{if !$tsAct}
		{if !$tsBlackList.data}
			<div class="empty">No hay nada en tu lista negra.</div>
		{else}
			<table class="admin_table">
				<thead>
					<th>ID</th>
					<th>Tipo</th>
					<th>Texto</th>
					<th>Raz&oacute;n</th>
					<th>Autor</th>
					<th>Fecha</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					{foreach from=$tsBlackList.data item=b}
					<tr id="block_{$b.id}">
						<td>{$b.id}</td>
						<td>{if $b.type == 1}IP{elseif $b.type == 2}Email{elseif $b.type == 3}Proveedor{elseif $b.type == 4}Nombre{else}Indefinido{/if}</td>
						<td>{$b.value}</td>
						<td>{$b.reason}</td>
						<td><a href="{$tsConfig.url}/perfil/{$b.user_name}" class="text-decoration-none fw-500">{$b.user_name}</a></td>
						<td>{$b.date|hace}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/blacklist?act=editar&id={$b.id}" title="Editar">{uicon name="pen"}</a>
							<span role="button" class="color" onclick="admin.blacklist.borrar({$b.id}); return false" title="Eliminar">{uicon name="trash"}</span>
						</td>
					</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<td colspan="7">P&aacute;ginas: {$tsBlackList.pages}</td>
				</tfoot>
			</table>
		{/if}
		<a href="{$tsConfig.url}/admin/blacklist?act=nuevo" class="btn btnCancel">Agregar nuevo bloqueo</a>
	{elseif $tsAct == 'editar' || $tsAct == 'nuevo'}
		<form action="" method="post" autocomplete="off">
			<fieldset>
				<legend>{if $tsAct == 'editar'}Editar{else}Agregar{/if} bloqueo</legend>
				<span>Para bloquear correos masivos como ejemplo@<strong>yopmail.com</strong>, seleccione "<em><u>proveedor de correo</u></em>" e introduzca <strong>yopmail.com</strong> en valor.</span>
				<dl>
					<dt><label for="b_type">Tipo:</label></dt>
					<dd>
						<select name="type" id="b_type" style="width:164px">
							<option value="1"{if $tsBL.type == 1} selected{/if}>IP</option>
							<option value="2"{if $tsBL.type == 2} selected{/if}>Email concreto</option>
							<option value="3"{if $tsBL.type == 3} selected{/if}>Proveedor de correo</option>
							<option value="4"{if $tsBL.type == 4} selected{/if}>Nombre</option>
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label for="b_value">Valor:</label></dt>
					<dd><input type="text" id="b_value" name="value" value="{$tsBL.value}" /></dd>
				</dl>
				{if $tsAct == 'nuevo'}
					<dl>
						<dt><label for="b_reason">Raz&oacute;n:</label><span>Indica el motivo por el cual quiere agregarlo a la lista negra.</span></dt>
						<dd><textarea name="reason" id="b_reason" rows="3" cols="40">{$tsBL.reason}</textarea></dd>
					</dl>
				{/if}
				<hr />
			 	<p><input type="submit" name="{if $tsAct == 'editar'}edit{else}new{/if}" value="{if $tsAct == 'editar'}Guardar{else}Agregar{/if}" class="button"/>
			</fieldset>
		</form>
	{/if}
</div>