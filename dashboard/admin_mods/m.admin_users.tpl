<div class="boxy-title">
	<h3>Administrar Usuarios</h3>
</div>
<div id="res" class="boxy-content" style="position:relative">
{if !$tsAct}
	{if !$tsMembers.data}
		<div class="empty hero">No hay usuarios registrados.</div>
	{else}
		<table class="admin_table">
			<thead>
				<th>Rango</th>
				<th>Usuario</th>
				<th><a title="Ordenar por email ascendente" href="{$tsConfig.url}/admin/users?o=c&m=a"><</a> Email <a title="Ordenar por email descendente" href="{$tsConfig.url}/admin/users?o=c&m=d">></a></th>
				<th><a title="Ordenar por &uacute;ltima vez activo ascendente" href="{$tsConfig.url}/admin/users?o=u&m=a"><</a> &Uacute;ltima actividad <a title="Ordenar por &uacute;ltima vez activo desccendente" href="{$tsConfig.url}/admin/users?o=u&m=d">></a></th>
				<th><a title="Ordenar por IP ascendente" href="{$tsConfig.url}/admin/users?o=i&m=a"><</a> IP <a title="Ordenar por IP descendente" href="{$tsConfig.url}/admin/users?o=i&m=d">></a> </th>
				<th><a title="Ordenar por estado ascendente" href="{$tsConfig.url}/admin/users?o=e&m=a"><</a> Estado <a title="Ordenar por estado descendente" href="{$tsConfig.url}/admin/users?o=e&m=d">></a></th>
				<th>Acciones</th>
			</thead>
			<tbody>
				{foreach from=$tsMembers.data item=m}
				<tr>
					<td class="text-center"><img src="{$tsConfig.assets}/images/rangos/{$m.r_image}" width="16" height="16" /></td>
					<td align="left"><a href="{$tsConfig.url}/perfil/{$m.user_name}" class="text-decoration-none fw-semibold" uid="{$m.user_id}" style="color:#{$m.r_color};">{$m.user_name}</a><small class="d-block">{$m.user_registro|date_format:"%d/%m/%Y"}</small></td>
					<td>{$m.user_email}</td>
					<td>{if $m.user_lastactive == 0} Nunca{else}{$m.user_lastactive|hace:true}{/if}</td>
					<td><a href="{$tsConfig.url}/moderacion/buscador/1/1/{$m.user_last_ip}" class="geoip" target="_blank">{$m.user_last_ip}</a></td>
					<td id="status_user_{$m.user_id}">{if $m.user_baneado == 1}<font color="red">Suspendido</font>{elseif $m.user_activo == 0}<font color="purple">Inactivo</font>{else}<font color="green">Activo</font>{/if}</td>
					<td class="admin_actions">
						<a href="{$tsConfig.url}/admin/users?act=show&uid={$m.user_id}" title="Editar Usuario">{uicon name="pen"}</a>
						<span role="button" onclick="admin.users.setInActive({$m.user_id}); return false;" title="Activar/Desactivar Usuario">{uicon name="refresh-alt"}</span>
						<span role="button" onclick="mod.users.action({$m.user_id}, 'aviso', false); return false;" title="Enviar Alerta">{uicon name="warning-triangle"}</span>
						<span role="button" onclick="mod.{if $m.user_baneado == 1}reboot({$m.user_id}, 'users', 'unban', false){else}users.action({$m.user_id}, 'ban', false){/if}; return false;" title="{if $m.user_baneado == 1}Reactivar{else}Suspender{/if} Usuario">{uicon name="no-sign"}</span>
					</td>
				</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<td colspan="8">{$tsMembers.pages}</td>
			</tfoot>
		</table>
	{/if}
{elseif $tsAct == 'show'}
<div class="d-flex justify-content-between align-items-center w-100">
	<h4>Administrar: <strong>{$tsUsername}</strong></h4>
	<div><strong>Seleccionar:</strong> 
		<select onchange="location.href='{$tsConfig.url}/admin/users?act=show&uid={$tsUserID}&t=' + this.value;">
			<option value="1"{if $tsType == 1} selected{/if}>Vista general</option>
			<option value="5"{if $tsType == 5} selected{/if}>Preferencias</option>
			<option value="6"{if $tsType == 6} selected{/if}>Borrar Contenido</option>
			<option value="7"{if $tsType == 7} selected{/if}>Rango</option>
			<option value="8"{if $tsType == 8} selected{/if}>Firma</option>
		</select>
	</div>
</div>
{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
<form action="" method="post" class="mt-3">
	<fieldset>
	{if !$tsType || $tsType == 1}
		<legend>Vista general</legend>
		<dl>
			<dt><label for="user">Nombre de Usuario:</label></dt>
			<dd><input type="text" name="nick" id="user" value="{$tsUserD.user_name}" title="El nick s&oacute;lo se cambiar&aacute; si escribe una nueva contrase&ntilde;a" /></dd>
		</dl>
		<dl>
			<dt><label for="user">Rango:</label></dt>
			<dd><strong style="color:#{$tsUserD.r_color}">{$tsUserD.r_name}</strong></dd>
		</dl>
		<dl>
			<dt><label for="registro">Registrado:</label></dt>
			<dd><strong>{$tsUserD.user_registro|date_format:"%d/%m/%Y a las %H:%M"} ({$tsUserD.user_registro|hace:true})</strong></dd>
		</dl>
		<dl>
			<dt><label>&Uacute;ltima vez activo:</label></dt>
			<dd><strong>{$tsUserD.user_lastactive|hace}</strong></dd>
		</dl>
		<dl>
			<dt><label>Puntos:</label></dt>
			<dd><input type="text" name="points" id="points" value="{$tsUserD.user_puntos}" style="width:10%" /></dd>
		</dl>
		<dl>
			<dt><label>Puntos para dar:</label></dt>
			<dd><input type="text" name="pointsxdar" id="pointsxdar" value="{$tsUserD.user_puntosxdar}" style="width:10%" /></dd>
		</dl>
		<dl>
			<dt><label>Cambios de nick disponibles:</label></dt>
			<dd><input type="text" name="changenicks" id="changenicks" value="{$tsUserD.user_name_changes}" style="width:10%" /></dd>
		</dl>
		<dl>
         <dt><label for="verificar">Usuario Verificado:</label></dt>
         <dd><a href="{$tsConfig.url}/admin/users?act=verificar&id={$tsUserD.user_id}">{if $tsUserD.user_verificado}Desverificar usuario{else}Verificar usuario{/if}</a></dd>
      </dl>
		<hr />
		<dl>
			<dt><label for="email">E-mail:</label></dt>
			<dd><input type="text" name="email" id="email" value="{$tsUserD.user_email}" /></dd>
		</dl>
		<dl>
			<dt><label for="pwd">Nueva contrase&ntilde;a:</label><span>Debe tener entre 5 y 35 caracteres.</span></dt>
			<dd><input type="password" name="pwd" id="pwd" onkeypress="if($('#cpwd').val() != '') $('#sendata').fadeIn();"/></dd>
		</dl>
		<dl>
			<dt><label for="cpwd">Confirmar contrase&ntilde;a:</label><span>Necesita confirmar su contrase&ntilde;a s&oacute;lo si la ha cambiado arriba.</span></dt>
			<dd><input type="password" name="cpwd" id="cpwd" onkeypress="if($('#pwd').val() != '') $('#sendata').fadeIn();"/></dd>
		</dl>
		 <dl id="sendata" style="display:none;">
			<dt><label for="sendata">Informar al usuario</label><span>Marque esta casilla si quiere enviar un e-mail al usuario con los nuevos datos</span></dt>
			<dd><input type="checkbox" name="sendata"/></dd>
		</dl>
	{elseif $tsType == 5}
		<legend>Modificar privacidad del usuario</legend>
			<h2 class="active">&iquest;Qui&eacute;n puede...</h2>
			<dl>
				<dt><label>ver su muro?</label></dt>
				<dd>
					<select name="muro">
					{foreach from=$tsPrivacidad item=p key=i}
						<option value="{$i}"{if $tsPerfil.p_configs.m == $i} selected="true"{/if}>{$p}</option>
					{/foreach}
					</select>
				</dd>
			</dl>
			{$tsPerfil.p_configs.muro}
			<dl>
			<dt><label>firmar su muro?</label></dt>
				<dd>
					<select name="muro_firm">
					{foreach from=$tsPrivacidad item=p key=i}
					{if $i != 6}<option value="{$i}"{if $tsPerfil.p_configs.mf == $i} selected{/if}>{$p}</option>{/if}
					{/foreach}
					</select>
				</dd>
			</dl>
			<dl>
			<dt><label>ver visitantes recientes?</label></dt>
				<dd>
					<select name="last_hits">
					{foreach from=$tsPrivacidad item=p key=i}
						{if $i != 1 && $i != 2}<option value="{$i}"{if $tsPerfil.p_configs.hits == $i} selected{/if}>{$p}</option>{/if}
					{/foreach}
					</select>
				</dd>
			</dl>
			<dl>
				<dt><label>enviarles mensajes privados?</label><span>Esta opci&oacute;n no se aplica a moderadores y administradores.</span></dt>
				<dd>
					<select name="rec_mps">
						{foreach from=$tsPrivacidad item=p key=i}
							{if $i != 6}<option value="{$i}"{if $tsPerfil.p_configs.rmp == $i} selected{/if}>{$p}</option>{/if}
						{/foreach}
						<option value="8"{if $tsPerfil.p_configs.rmp == 8} selected{/if}>Deshabilitar mensajer&iacute;a (opci&oacute;n administrativa)</option>
					</select>
				</dd>
			</dl>
		</div>
	{elseif $tsType == 6}
		<legend>Eliminaci&oacute;n de contenidos</legend>
		<div class="upform-check p-2">
			<label class="fw-normal">
				<input type="checkbox" name="bocuenta" id="bocuenta" onclick="$('#ext').slideToggle();">
				<span class="upform-check-icon"></span>
				<span><strong>Cuenta Completa</strong>: <small>Se eliminar&aacute; la cuenta y todo el contenido relacionado a {$tsUsername}.</small></span>
			</label>
		</div>
		<div id="ext">
			{foreach $contentUser item=content key=i}
				<div class="upform-check p-2">
					<label class="fw-normal">
						<input type="checkbox" name="{$i}" id="{$i}">
						<span class="upform-check-icon"></span>
						<span><strong>{$content.title}</strong>: <small>{$content.text}</small></span>
					</label>
				</div>
			{/foreach}
		</div>

      <dl>
         <dt><label for="password">Introduzca su contrase&ntilde;a para continuar:</label></dt>
         <dd><input type="password" id="password" name="password"/></dd>
      </dl>
					  
	{elseif $tsType == 7}
		<legend>Modificar rango de usuario</legend>
		<dl>
			<dt><label>Rango actual:</label></dt>
			<dd><strong style="color:#{$tsUserR.user.r_color}">{$tsUserR.user.r_name}</strong></dd>
		</dl>
		<dl>
			<dt><label for="user">Nuevo rango:</label></dt>
			<dd><select name="new_rango">
			{foreach from=$tsUserR.rangos item=r}
			<option value="{$r.rango_id}" style="color:#{$r.r_color}" {if $r.rango_id == $tsUserR.user.rango_id} selected="selected"{/if}>{$r.r_name}</option>
			{/foreach}
			</select></dd>
		</dl>
	{elseif $tsType == 8}

		<dl>
			<dt><label for="firma">Modificar firma de usuario:</label></dt>
			<dd><textarea name="firma" rows="3" cols="50">{$tsUserF.user_firma}</textarea></dd>
		</dl>
		
	{else}
		<div class="empty hero">Pendiente</div>
	{/if}
	<p><input type="submit" name="save" value="Enviar Cambios" class="btn_g"/></p>
	</fieldset>
</form>
{/if}
</div>