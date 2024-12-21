<div class="boxy-title">
	<h3>Administrar Rangos de Usuarios</h3>
</div>
<div id="res" class="boxy-content" style="position:relative">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
	{if $tsAct == ''}
		<div style="margin:.3rem auto">
			<h3 style="margin:0">Rangos Especiales</h3>
			<hr style="margin:4px 0" />
			<table class="admin_table">
				<thead>
					<th>Rango</th>
					<th>Usuarios</th>
					<th>Puntos para dar</th>
					<th>Puntos por post</th>
					<th>Imagen</th>
					<th>Acciones</th>
				</thead>
				<tbody>
				 	{foreach from=$tsRangos.regular item=r}
					<tr>
						<td><a href="{$tsConfig.url}/admin/rangos/?act=list&rid={$r.id}&t=r"  class="text-decoration-none fw-500"style="color:#{$r.color}">{$r.name}</a></td>
						<td>{$r.num_members}</td>
						<td>{$r.user_puntos}</td>
						<td>{$r.max_points}</td>
						<td><img src="{$tsConfig.assets}/images/rangos/{$r.imagen}" class="avatar avatar-2" /></td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/rangos/?act=editar&rid={$r.id}&t=s" title="Editar Rango">{uicon name="pen" class="pe-none"}</a>
							{if $r.id > 3}
								<a href="{$tsConfig.url}/admin/rangos/?act=borrar&rid={$r.id}" title="Borrar Rango">{uicon name="trash-alt" class="pe-none"}</a>
							{/if}
							{if $tsConfig.c_reg_rango == $r.id}
								{uicon name="clipboard-check" class="pe-none" title="Rango Predeterminado al registro"}
							{else}
								<a href="{$tsConfig.url}/admin/rangos/?act=setdefault&rid={$r.id}" title="Establecer Predeterminado">{uicon name="flame-alt" class="pe-none"}</a>
							{/if}
						</td>
					</tr>
				 	{/foreach}
				</tbody>
				<tfoot>
					<td colspan="6" style="text-align:right">
						<a class="me-2 btn" href="{$tsConfig.url}/admin/rangos/?act=nuevo&t=s">Agregar nuevo rango &raquo;</a>
					</td>
				</tfoot>
			</table>
		</div>
		<div style="margin:.3rem auto">
			<h3 style="margin:0">Rangos basados en el conteo de puntos y posts</h3>
			<hr style="margin:4px 0" />
			<table class="admin_table">
				<thead>
					<th width="150">Rango</th>
					<th>Usuarios</th>
					<th>Tipo</th>
					<th>Cantidad requerida</th>
					<th>Puntos para dar</th>
					<th>Puntos por post</th>
					<th>Imagen</th>
					<th>Acciones</th>
				</thead>
			 	<tbody>
			 		{foreach from=$tsRangos.post item=r}
					<tr>
						<td><a href="{$tsConfig.url}/admin/rangos/?act=list&rid={$r.id}&t=p" class="text-decoration-none fw-500" style="color:#{$r.color}">{$r.name}</a></td>
						<td>{$r.num_members}</td>
						<td>{if $r.type == 1}Puntos{elseif $r.type == 2}Posts{elseif $r.type == 3}Fotos{elseif $r.type == 4}Comentarios{/if}</td>
						<td>{$r.cant}</td>
						<td>{$r.user_puntos}</td>
						<td>{$r.max_points}</td>
						<td><img src="{$tsConfig.assets}/images/rangos/{$r.imagen}" class="avatar avatar-2" /></td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/rangos/?act=editar&rid={$r.id}&t=p" title="Editar Rango">{uicon name="pen" class="pe-none"}</a>
							{if $r.id > 3}
								<a href="{$tsConfig.url}/admin/rangos/?act=borrar&rid={$r.id}" title="Borrar Rango">{uicon name="trash-alt" class="pe-none"}</a>
							{/if}
						</td>
					</tr>
			 		{/foreach}
			 	</tbody>
			 	<tfoot>
					<td colspan="8" style="text-align:right">
						<a class="me-2 btn" href="{$tsConfig.url}/admin/rangos/?act=nuevo">Agregar nuevo rango &raquo;</a>
					</td>
			 	</tfoot>
			</table>
		</div>
	{elseif $tsAct == 'list'}
		{if !$tsMembers.data}
			<div class="empty empty-danger">Aun no hay usuarios en este rango.</div>
		{else}
			<table class="admin_table">
				<thead>
					<th>Usuario</th>
					<th>Email</th>
					<th>&Uacute;ltima vez activo</th>
					<th>Fecha de registro</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					{foreach from=$tsMembers.data item=m}
					<tr>
						<td><a href="{$tsConfig.url}/perfil/{$m.user_name}" class="text-decoration-none" style="color:#{$m.r_color};">{$m.user_name}</a></td>
						<td>{$m.user_email}</td>
						<td>{$m.user_lastlogin|hace:true}</td>
						<td>{$m.user_registro|date_format:"%d/%m/%Y"}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/users?act=show&uid={$m.user_id}&t=7"><img src="{$tsConfig.public}/images/icons/editar.png" title="Editar rango" /></a>
						</td>
					</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<td colspan="6">P&aacute;ginas: {$tsMembers.pages}</td>
				</tfoot>
			</table>
		{/if}
	{elseif $tsAct == 'nuevo' || $tsAct == 'editar'}
		<form action="" method="post">
			<fieldset>
		 		<legend>Nuevo Rango</legend>
		 		<div class="d-flex justify-content-center align-items-center column-gap-2">
			 		<input type="button" id="button1" value="B&aacute;sico" class="button btnOk"/> 
					<input type="button" id="button2" value="Permisos" class="button btnCancel"/>
				</div>
				<!-- TAB 1 -->
				<div id="tab1">
					<dl>
						<dt><label for="rName">T&iacute;tulo:</label></dt>
						<dd><input type="text" id="rName" name="rName" value="{$tsRango.r_name}"/></dd>
					</dl>
					<dl>
						<dt><label for="rColor">Color:</label><span>Color (<a href="http://es.wikipedia.org/wiki/Colores_HTML" target="_blank">hexadecimal</a>) del rango.</span></dt>
						<dd><input type="color" id="rColor" name="rColor" value="{$tsRango.r_color|default:'#000000'}" style="color:#{$tsRango.r_color};"/></dd>
					</dl>
					<dl>
						 <dt><label for="gopfd">Puntos por d&iacute;a:</label><span>Puntos que puede otorgar este rango a otros usuarios al d&iacute;.</span></dt>
						 <dd><input type="number" id="gopfd" name="global-pointsforday" value="{$tsRango.permisos.gopfd}"/></dd>
					</dl>
					<dl>
						<dt><label for="gopfp">Puntos por post</label><span>Puntos que puede dar en cada post.</span></dt>
						<dd><input type="number" id="gopfp" name="global-pointsforposts" value="{$tsRango.permisos.gopfp}"/></dd>
					</dl>
					<dl>
						<dt><label for="goaf">Anti-flood</label><span>Tiempo que deben esperar entre acci&oacute;n.</span></dt>
						<dd><input type="number" id="goaf" name="global-antiflood" value="{$tsRango.permisos.goaf}" /></dd>
					</dl>
					<dl>
						<dt><label for="gocpr">Condici&oacute;n especial:</label><span>Cantidad requerida para obtener el rango. Elija especial si s&oacute;lo es asignado por un administrador. </span></dt>
						<dd>
							<label onclick="$('#gocpr').slideDown();"><input name="global-type" type="radio" id="ai_type" value="1"{if $tsRango.r_type == 1} checked{/if} class="radio"/><span title="Del usuario">Puntos<span></label>
							<label onclick="$('#gocpr').slideDown();"><input name="global-type" type="radio" id="ay_type" value="2"{if $tsRango.r_type == 2} checked{/if} class="radio"/>Posts</label>
							<label onclick="$('#gocpr').slideDown();"><input name="global-type" type="radio" id="ay_type" value="3"{if $tsRango.r_type == 3} checked{/if} class="radio"/>Fotos</label>
							<label onclick="$('#gocpr').slideDown();"><input name="global-type" type="radio" id="ay_type" value="4"{if $tsRango.r_type == 4} checked{/if} class="radio"/><span title="De posts y fotos">Comentarios</span></label>
							<label onclick="$('#gocpr').slideUp();"><input name="global-type" type="radio" id="ay_type" value="0"{if $tsRango.r_type == 0} checked{/if} class="radio"/>Especial</label>
							<input type="text" id="gocpr" name="global-cantidadrequerida" style="width:12%{if $tsRango.r_type == 0};display:none;{/if}" maxlength="5" value="{$tsRango.r_cant}" />
						</dd>
					</dl>
					<dl>
						<dt><label for="cat_img">Icono del rango:{$tsRango.r_image}</label></dt>
						<dd class="d-flex justify-content-start column-gap-2">
							<img src="{$tsConfig.assets}/images/rangos/{if $tsRango.r_image}{$tsRango.r_image}{else}{$tsIcons.0}{/if}" width="32" height="32" id="c_icon"/>
							<select name="r_img" id="cat_img" style="width:164px">
								{foreach from=$tsIcons key=i item=img}
									<option value="{$img}"{if $tsRango.r_image == $img} selected{/if}>{$img}</option>
								{/foreach}
							</select>
						</dd>
					</dl>
					<hr />
					<input type="button" id="continue" value="Continuar" class="button"/> 
				</div>
				<!-- TAB 2 -->
				<div id="tab2" style="display: none;">
					{foreach $tsOptions item=options key=nameOpt}
						<fieldset>
							<legend>{$nameOpt}</legend>
							{foreach $options item=opt}
								{checkbox id=$opt.id name=$opt.name checked=$opt.checked label=$opt.label optional=$opt.optional}
							{/foreach}
						</fieldset>
					{/foreach}
					
					<input type="hidden" name="sp" value="{if $tsType == 's'}1{else}0{/if}" />
					<p><input type="submit" name="save" value="Guardar Cambios" class="button"/></p>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", function() {
						// Cambiamos el icono
						$('#cat_img').on('change', () => {
							$('#c_icon').attr({ 
								src: ZCodeApp.images.assets + '/rangos/' + $("#cat_img option:selected").val() 
							});
						});
						//
						function status(tab) {
							$('#tab' + (tab === 2 ? 2 : 1)).hide();
							$('#tab' + (tab === 2 ? 1 : 2)).show();
							$('#button' + (tab === 1 ? 1 : 2)).removeClass('btnOk').addClass('btnCancel')
							$('#button' + (tab === 1 ? 2 : 1)).removeClass('btnCancel').addClass('btnOk')
						}
						$('#button1').on('click', () => status(2))
						$('#button2, #continue').on('click', () => status(1))
					})
				</script>
			</fieldset>
		</form>
	{elseif $tsAct == 'borrar'}
		<form action="" method="post" id="admin_form">
			<div class="empty empty-danger">Si borras este rango todos los usuarios que est&eacute;n en &eacute;l, ser&aacute;n asignados al rango 
				<select name="new_rango">
					{foreach from=$tsRangos item=r}
						<option value="{$r.rango_id}"{if $r.rango_id == 3} selected{/if}>{$r.r_name}</option>
					{/foreach}
				</select> <br /> &iquest;Realmente deseas borrar este rango?
			</div>
			<label>&nbsp;</label> <input type="submit" name="save" value="S&iacute;, Continuar &raquo;" class="mBtn btnCancel">
		</form>
	{/if}
</div>