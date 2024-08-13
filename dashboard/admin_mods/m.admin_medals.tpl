<div class="boxy-title">
	<h3>Medallas</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
	{if !$tsAct}
      {if !$tsMedals.medallas}
         <div class="empty hero">No hay medallas.</div>
         <a href="{$tsConfig.url}/admin/medals?act=nueva" class="btn btnOk">Agregar nueva medalla</a>
		{else}
			
			{foreach from=$tsMedals.medallas item=m}
				<div id="medal_id_{$m.medal_id}" class="position-relative rounded border mb-3 d-grid column-gap-2 p-2" style="grid-template-columns: 6rem 1fr;" data-mid="{$m.medal_id}">
					<div><img class="avatar avatar-8" src="{$tsConfig.assets}/images/medallas/{$m.m_image}" title="{$m.m_title}" /></div>
					<div>
						<div class="h6 m-0 py-2">{$m.m_title} (<strong title="Asignaciones" id="total_med_assig_{$m.medal_id}">{$m.m_total}</strong>)</div>
						<small class="d-block">{$m.m_description}</small>
						<span class="d-block small fst-italic">
							Tipo: <strong>{if $m.m_type == 1}Usuario{elseif $m.m_type == 2}Post{else}Foto{/if}</strong> - 
							Por: {if $m.m_autor == 0}Sistema{else}<a href="{$tsConfig.url}/perfil/{$m.user_name}" class="text-decoration-none fw-semibold">{$m.user_name}</a>{/if} - 
							{$m.m_date|hace:true}
						</span>

						<div class="d-flex justify-content-end align-items-center column-gap-2 position-absolute" style="top: 1rem;right: 1rem;">
							<span role="button" onclick="admin.medallas.asignar({$m.medal_id}); return false" title="Asignar Medalla">{uicon name="plus" class="pe-none"}</span>
							<a href="{$tsConfig.url}/admin/medals/?act=editar&mid={$m.medal_id}" title="Editar Medalla">{uicon name="pen" class="pe-none"}</a>
							<span role="button"onclick="admin.medallas.borrar({$m.medal_id}); return false" title="Borrar Medalla">{uicon name="trash-alt" class="pe-none"}</span>
						</div>
					</div>
				</div>
			{/foreach}
			<div> {$tsMedals.pages}</div>

			<a href="{$tsConfig.url}/admin/medals?act=nueva" class="btn">Agregar nueva medalla</a>
			<a href="{$tsConfig.url}/admin/medals?act=showassign" class="btn">Ver medallas asignadas</a>
		{/if}
	{elseif $tsAct == 'showassign'}

		{foreach from=$tsAsignaciones.asignaciones item=m}
			<div id="assign_id_{$m.id}" class="position-relative rounded border mb-3 d-grid column-gap-2 p-2" style="grid-template-columns: 4rem 1fr;" data-mid="{$m.id}">
				<div><img class="avatar avatar-5" src="{$tsConfig.assets}/images/medallas/{$m.m_image}" title="{$m.m_title}" /></div>
				<div>
					<div class="h6 m-0 py-2">{$m.m_title}</div>
					<span class="d-block small fst-italic">
						Tipo: <strong>{if $m.m_type == 1}Usuario{elseif $m.m_type == 2}Post{else}Foto{/if}</strong> - 
						Asignada a: {if $m.m_type == 1}<a href="{$tsConfig.url}/perfil/{$m.user_name}" class="text-decoration-none fw-semibold">@{$m.user_name}</a>{elseif $m.m_type == 2}<a href="{$tsConfig.url}/posts/{$m.c_seo}/{$m.post_id}/{$m.post_title|seo}.html" class="text-decoration-none fw-semibold" target="_blank">{$m.post_title}</a>{else}<a href="{$tsConfig.url}/fotos/autor/{$m.foto_id}/{$m.f_title}.html" class="text-decoration-none fw-semibold" target="_blank">{$m.f_title}</a>{/if} - 
						{$m.medal_date|hace:true} - IP {$m.medal_ip}
					</span>

					<div class="d-flex justify-content-end align-items-center column-gap-2 position-absolute" style="top: 1rem;right: 1rem;">
						<span role="button" onclick="admin.medallas.borrar_asignacion({$m.id}, {$m.medal_id}); return false" title="Borrar Asignaci&oacute;n">{uicon name="trash-alt" class="pe-none"}</span>
					</div>
				</div>
			</div>
		{/foreach}
		
		<div>{$tsAsignaciones.pages}</div>
	
	{elseif $tsAct == 'nueva' || $tsAct == 'editar'}
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function() {
				$('#med_img').on('change', () => {
					var cssi = $("#med_img option:selected").val();
					$('#c_icon').css({ 
	         		"background": 'url(\'{$tsConfig.assets}/images/medallas/'+cssi+'\') no-repeat center',
	         		"background-size": '2rem'
	         	});
				});
			});
		</script>
		<form action="" method="post" autocomplete="off">
			<fieldset>
				<legend>{if $tsAct == 'nueva'}Nueva{else}Editar{/if} medalla</legend>
				<dl>
					<dt><label for="med_name">T&iacute;tulo de la medalla:</label></dt>
					<dd><input type="text" id="med_name" name="med_title" value="{$tsMed.m_title}" /></dd>
				</dl>
				<dl>
					<dt><label for="ai_desc">Descripci&oacute;n:</label><span>Describe el motivo por el cual el contenido gana esta medalla.</span></dt>
					<dd><textarea name="med_desc" id="ai_desc" rows="3" cols="40">{$tsMed.m_description}</textarea></dd>
				</dl>
				<dl>
					<dt><label for="cat_img">Icono de la categor&iacute;a:</label></dt>
					<dd>
						<div class="d-flex justify-content-start align-items-center">
							<div style="background:url({$tsConfig.assets}/images/medallas/{if $tsMed.m_image}{$tsMed.m_image}{else}{$tsIcons.0}{/if}) no-repeat left center;" width="2rem" height="2rem" class="avatar avatar-3" id="c_icon"></div>
						
							<select name="med_img" id="med_img" style="width:220px">
							{foreach from=$tsIcons key=i item=img}
								<option value="{$img}"{if $tsMed.m_image == $img} selected{/if}>{$img}</option>
							{/foreach}
							</select>
						</div>
					</dd>
				</dl>
				<dl>
					<dt><label for="rSpecial">Condici&oacute;n especial:</label></dt>
					<dd>
						<span>Cuando </span>
					
						<label onclick="$('#ai_cond_post').slideUp(); $('#ai_cond_foto').slideUp(); $('#ai_cond_user').slideDown(); $('#ai_cond_user_rango_span').slideDown();"><input name="med_type" type="radio" id="ai_type" value="1" {if $tsMed.m_type == 1}checked{/if} class="radio"/>Usuario</label>
						<label onclick="$('#ai_cond_user').slideUp(); $('#ai_cond_user_rango').slideUp();  $('#ai_cond_foto').slideUp(); $('#ai_cond_post').slideDown();"><input name="med_type" type="radio" id="ay_type" value="2" {if $tsMed.m_type == 2}checked{/if} class="radio"/>Post</label>
						<label onclick="$('#ai_cond_user').slideUp(); $('#ai_cond_user_rango').slideUp();  $('#ai_cond_post').slideUp(); $('#ai_cond_foto').slideDown();"><input name="med_type" type="radio" id="ay_type" value="3" {if $tsMed.m_type == 3}checked{/if} class="radio"/>Foto</label>
						<span>consiga</span>
						<input type="text" id="ai_cant" name="med_cant" style="width:7%" maxlength="5" value="{$tsMed.m_cant}" {if $tsMed.m_cond_user == 9} style="display:none;"{/if} />
						<select name="med_cond_user" id="ai_cond_user" style="width:125px;{if $tsMed.m_type != 1}display:none;{/if}" onchange="if($('#ai_cond_user').val() == 9) $('#ai_cond_user_rango').slideDown();  else  $('#ai_cond_user_rango').slideUp();">
							<option value="1"{if $tsMed.m_cond_user == 1} selected{/if}>Puntos</option>
							<option value="2"{if $tsMed.m_cond_user == 2} selected{/if}>Seguidores</option>
							<option value="3"{if $tsMed.m_cond_user == 3} selected{/if}>Siguiendo</option>
							<option value="4"{if $tsMed.m_cond_user == 4} selected{/if}>Comentarios en posts</option>
							<option value="5"{if $tsMed.m_cond_user == 5} selected{/if}>Comentarios en fotos</option>
							<option value="6"{if $tsMed.m_cond_user == 6} selected{/if}>Posts</option>
							<option value="7"{if $tsMed.m_cond_user == 7} selected{/if}>Fotos</option>
							<option value="8"{if $tsMed.m_cond_user == 8} selected{/if}>Medallas</option>
							<option value="9"{if $tsMed.m_cond_user == 9} selected{/if}>Rango</option>
						</select>
						<select name="med_cond_user_rango" id="ai_cond_user_rango" {if $tsMed.m_type != 1 || $tsMed.m_cond_user != 9}style="display:none;"{/if}  onchange="if($('#ai_cond_user').val() != 9) $('#ai_cond_user_rango').slideUp();">
							{foreach from=$tsRangos item=r}
								<option value="{$r.rango_id}" style="color:#{$r.r_color}"{if $r.rango_id == $tsMed.m_cond_user_rango} selected{/if}>{$r.r_name}</option>
							{/foreach}
						</select>
						<select name="med_cond_post" id="ai_cond_post" style="width:125px;{if $tsMed.m_type != 2}display:none;{/if}">
							<option value="1"{if $tsMed.m_cond_post == 1} selected{/if}>Puntos</option>
							<option value="2"{if $tsMed.m_cond_post == 2} selected{/if}>Seguidores</option>
							<option value="3"{if $tsMed.m_cond_post == 3} selected{/if}>Comentarios</option>
							<option value="4"{if $tsMed.m_cond_post == 4} selected{/if}>Favoritos</option>
							<option value="5"{if $tsMed.m_cond_post == 5} selected{/if}>Denuncias</option>
							<option value="6"{if $tsMed.m_cond_post == 6} selected{/if}>Visitas</option>
							<option value="7"{if $tsMed.m_cond_post == 7} selected{/if}>Medallas</option>
							<option value="8"{if $tsMed.m_cond_post == 8} selected{/if}>veces compartido</option>
						</select>
						<select name="med_cond_foto" id="ai_cond_foto" style="width:125px;{if $tsMed.m_type != 3}display:none;{/if}">
							<option value="1"{if $tsMed.m_cond_foto == 1} selected{/if}>Puntos positivos</option>
							<option value="2"{if $tsMed.m_cond_foto == 2} selected{/if}>Puntos negativos</option>
							<option value="3"{if $tsMed.m_cond_foto == 3} selected{/if}>Comentarios</option>
							<option value="4"{if $tsMed.m_cond_foto == 4} selected{/if}>Visitas</option>
							<option value="5"{if $tsMed.m_cond_foto == 5} selected{/if}>Medallas</option>
						</select>
					</dd>
				</dl>	
				<hr />
				<p><input type="submit" name="{if $tsAct == 'nueva'}save{else}edit{/if}" value="{if $tsAct == 'nueva'}Crear medalla{else}Guardar{/if}" class="btn_g"/></p>
			</fieldset>
		</form>
	{/if}
</div>