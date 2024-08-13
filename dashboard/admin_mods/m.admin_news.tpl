<div class="boxy-title">
	<h3>Noticias</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsDelete == 'true'}<div class="empty empty-danger">Noticia eliminada.</div>{/if}
	{if $tsAct == ''}
		<div class="empty my-3">Si necesitas hacer un comunicado a todos los usuarios en general, desde aqu&iacute; podr&aacute;s administrar tus anuncios y los usuarios sin importar donde se encuentren navegando podr&aacute;n visualizarlos.</div>
		<hr class="separator" />
		<h4 class="p-2 border-bottom">Lista de noticias</h4>
		

		{foreach from=$tsNews item=n}
			<div class="border rounded p-3 shadow mb-3 position-relative d-flex justify-content-start align-items-center" nid="{$n.not_id}">
				<div class="block-number avatar avatar-5 d-flex justify-content-center align-items-center me-3 h3">
					#{$n.not_id}
				</div>
				<div class="block-info flex-grow-1">
					<span class="d-block">{$n.not_body}</span>
					<small>Por: <a href="{$tsConfig.url}/perfil/{$n.user_name}" class="text-decoration-none fw-semibold">{$n.user_name}</a> - <time>{$n.not_date|hace:true}</time> - Estado: <strong id="status_noticia_{$n.not_id}">{if $n.not_active == 0}<span class="text-danger">Inactiva</span>{else}<span class="text-success">Activa</span>{/if}</strong> - Tipo: <strong>{if $n.not_type == 0}Normal{elseif $n.not_type == 1}Importante{else}Cambios{/if}</strong></small>
				</div>
		
				<div class="d-flex justify-content-end align-items-center">
					<a class="avatar avatar-3" href="{$tsConfig.url}/admin/news?act=editar&nid={$n.not_id}" title="Editar">{uicon name="pen" class="pe-none"}</a>
					<span class="avatar avatar-3" role="button" onclick="admin.news.accion({$n.not_id}); return false" title="Activar/Desactivar Noticia">{uicon name="refresh-alt" class="pe-none"}</span>
					<span class="avatar avatar-3" role="button" onclick="admin.news.borrar({$n.not_id}); return false" title="Borrar">{uicon name="trash-alt" class="pe-none"}</span>
				</div>
			</div>
		{/foreach}

		<a href="{$tsConfig.url}/admin/news/?act=nuevo" class="btn">Nueva noticia</a>
								
	{elseif $tsAct == 'nuevo' || $tsAct == 'editar'}
		<form action="" method="post" autocomplete="off">
			<fieldset>
				<legend>{if $tsAct == 'nuevo'}Agregar nueva{else}Editar{/if} noticia</legend>
				<dl>
					<dt><label for="ai_new">Noticia:</label><span>Puedes utilizar los siguentes BBCodes [url], [i] [b] y [u]. El m&aacute;ximo de caracteres permitidos es de <strong>190</strong>.</span></dt>
					<dd><textarea name="not_body" id="ai_new" rows="3" cols="50">{$tsNew.not_body}</textarea></dd>
				</dl>
				<dl>
					<dt><label for="ai_not_active">Activar noticia:</label><span>Activar inmediatamente esta noticia en {$tsConfig.titulo}.</span></dt>
					<dd>
						<label><input name="not_active" type="radio" id="ai_not_active" value="1" {if $tsNew.not_active == 1}checked="checked"{/if} class="radio"/>S&iacute;</label>
						<label><input name="not_active" type="radio" id="ai_not_active" value="0" {if $tsNew.not_active != 1}checked="checked"{/if} class="radio"/>No</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="ai_not_type">Tipo de noticia:</label><span>Que tipo de noticia es.</span></dt>
					<dd>
						<label><input name="not_type" type="radio" id="ai_not_type" value="0"{if $tsNew.not_type == 0} checked{/if} class="radio"/>Normal</label>
						<label><input name="not_type" type="radio" id="ai_not_type" value="1"{if $tsNew.not_type == 1} checked{/if} class="radio"/>Importante</label>
						<label><input name="not_type" type="radio" id="ai_not_type" value="2"{if $tsNew.not_type == 2} checked{/if} class="radio"/>Cambios</label>
					</dd>
				</dl>
				<p><input type="submit" name="save" value="{if $tsAct == 'new'}Agregar noticia{else}Guardar Cambios{/if}" class="btn"/></p>
			</fieldset>
		</form>
	{elseif $tsAct == 'borrar'}                                   
		<form action="" method="post" id="admin_form" autocomplete="off">									                                    
			<center><font color="red">Noticia eliminada</font>																		
			<hr />									                                    
			<a href="{$tsConfig.url}/admin/news?borrar=true" class="btn">Volver &#187;</a>
			</center>
		</form>								
	{/if}									
</div>