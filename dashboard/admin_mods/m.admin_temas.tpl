<div class="boxy-title">
	 <h3>Administrar Temas</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsAct == ''}
		{foreach from=$tsTemas item=tema}
			<div class="d-grid border mb-3 rounded shadow overflow-hidden position-relative" style="grid-template-columns: 340px 1fr;">
				<div style="width: 100%;height: 180px;">
					<img src="{$tema.t_url}/screenshot.png" class="w-100 h-100 rounded shadow" />
				</div>
				<div class="p-3">
					<h4 class="d-flex justify-content-start align-items-center">{if $tsConfig.tema_id == $tema.tid}{uicon name="star" class="pe-none me-2"} {/if}{$tema.t_name}{if !empty($tema.t_other)} <a href="{$tema.t_other}" target="_blank" rel="external" style="color:var(--main-bg)">{uicon name="external"}</a>{/if}</h4>
					<span class="d-block my-2">{$tema.t_description}</span>
					<span>
						{foreach $tema.t_tags item=tag}
							<small class="badge main-bg">#{$tag}</small>
						{/foreach}
					</span>
					<span class="badge main-bg position-absolute py-2 px-3" style="top: 1rem;left:1rem">By: {if !empty($tema.t_link)}<a href="{$tema.t_link}" class="main-color" target="_blank" rel="external">{$tema.t_copyright}</a>{else}{$tema.t_copyright}{/if}</span>
					<div class="d-flex justify-content-end align-items-center column-gap-3 position-absolute" style="top: 1rem;right:1rem">
						<a class="avatar avatar-2" href="{$tsConfig.url}/admin/temas?act=editar&tid={$tema.tid}" title="Editar este tema">{uicon name="pen" class="pe-none" size="1.5rem"}</a>
					 	{if $tsConfig.tema_id != $tema.tid}
							<a class="avatar avatar-2" href="{$tsConfig.url}/admin/temas?act=usar&tid={$tema.tid}&tt={$tema.t_name}" title="Usar este tema">{uicon name="plus-circle" class="pe-none" size="1.5rem"}</a>
							{if $tema.tid != 1}
								<a class="avatar avatar-2" href="{$tsConfig.url}/admin/temas?act=borrar&tid={$tema.tid}&tt={$tema.t_name}" title="Borrar este tema">{uicon name="trash-alt" class="pe-none" size="1.5rem"}</a>
							{/if}
					 	{/if}
					</div>
				</div>
			</div>
		{/foreach}
		 
	 	<hr />
	 	<a href="{$tsConfig.url}/admin/temas?act=nuevo" class="btn mt-3">Instalar nuevo tema</a>
	{elseif $tsAct == 'editar'}
	 	<form action="" method="post" id="admin_form" autocomplete="off">
	 		<dl>
            <dt><label for="ai_name">Nombre del tema:</label><span>Por copyright no se pude modificar.</span></dt>
            <dd><input type="text" id="ai_name" name="name" maxlength="32" value="{$tsTema.t_name}" disabled /></dd>
         </dl>
	 		<dl>
            <dt><label for="ai_copy">Autor del tema:</label><span>Por copyright no se pude modificar.</span></dt>
            <dd><input type="text" id="ai_copy" name="copy" maxlength="32" value="{$tsTema.t_copy}" disabled /></dd>
         </dl>
	 		<dl>
            <dt><label for="ai_path">Nombre de la carpeta donde esta el tema:</label></dt>
            <dd><input type="text" id="ai_path" name="path" maxlength="32" value="{$tsTema.t_path}" /></dd>
         </dl>
	 		<dl>
            <dt><label for="ai_url">Url completa del tema:</label></dt>
            <dd><input type="text" id="ai_url" name="url" maxlength="32" value="{$tsTema.t_url}" /></dd>
         </dl>
		
		  	<hr />
		  <label>&nbsp;</label> <input type="submit" value="Guardar tema" name="save" class="mBtn btnOk">
	 	</form>
	{elseif $tsAct == 'usar' || $tsAct == 'borrar'}
	 	<form action="" method="post" id="admin_form" autocomplete="off">
			<h3 align="center">{$tt}</h3>
			<label>&nbsp;</label> <input type="submit" name="confirm" value="{if $tsAct == 'usar'}Confirmar el cambio de{else}Continuar borrando este{/if} tema &raquo;" class="mBtn btnOk">
		  	{if $tsAct == 'borrar'}<p align="center">Te recordamos que debes borrar la carpeta del Tema manualmente en el servidor.</p>{/if}
	 	</form>
	{elseif $tsAct == 'nuevo'}
	 	{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
	 	<form action="" method="post" id="admin_form" autocomplete="off">
			<label for="ai_path">Nombre de la carpeta donde esta el tema a instalar:<br /><i>{$tsConfig.url}/themes/</i></label> <input type="text" id="ai_path" name="path" size="30" />
		  	<hr />
		  	<label>&nbsp;</label> <input type="submit" value="Instalar tema" class="mBtn btnOk">
	 	</form>
	{/if}
</div>