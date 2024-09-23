<div class="boxy-title">
	<h3>Administrar Sitemap</h3>
</div>
<div id="res" class="boxy-content">
  {if $tsAct == ''}
		<ol style="list-style: decimal;">
			<li class="d-block py-1"><strong class="d-block">Sincronizar:</strong> Esto revisara toda la base de datos y agregará todas las urls que no esten y actualizará el archivo sitemap.xml</li>
		   <li class="d-block py-1"><strong class="d-block">Agregar:</strong> También puedes agregar las urls manualmente.</li>
		   <li class="d-block py-1"><strong class="d-block">Configuración:</strong> Podrás configurar si se añaden url de forma automática o no</li>
		</ol>
		<hr class="separator" />
		
		<strong>Lista de URLs en la base de datos</strong>
		<table class="admin_table">
			<thead>
				<th>ID</th>
				<th>URL</th>
				<th>Frecuencia</th>
				<th>Prioridad</th>
				<th>Fecha</th>
				<th>Acciones</th>
			</thead>
			<tbody>
				{foreach from=$tsURLs item=u}
					<tr>
						<td class="text-center fw-bold">{$u.id}</td>
						<td><a href="{$u.url}" class="text-decoration-none" target="_blank">{$u.url|truncate:40}</a></td>
						<td class="text-center">{$u.frecuencia}</td>
						<td class="text-center">{$u.prioridad}</td>
						<td class="text-center">{$u.fecha|hace:true}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/sitemap?act=editar&id={$u.id}" title="Editar URL">{uicon name="pen"}</a>
							<a href="{$tsConfig.url}/admin/sitemap?act=borrar&id={$u.id}" title="Borrar URL">{uicon name="trash"}</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<td colspan="8">P&aacute;ginas: {$tsURLs.pages}</td>
			</tfoot>
		</table>
		<div style="display:flex;justify-content:flex-start;align-items:center;gap:.3rem;padding:1rem 0">
	      <a href="{$tsConfig.url}/admin/sitemap/?act=sync&type=sitemap" class="btn">Sincronizar</a>
	      <a href="{$tsConfig.url}/admin/sitemap/?act=nueva" class="btn">Agregar</a>
	     	<a href="{$tsConfig.url}/admin/sitemap/?act=config" class="btn">Configuración</a>
		</div>
	{elseif $tsAct == 'config'}
		<form action="" method="post" autocomplete="off">
         <fieldset>
            <legend>Configuraci&oacute;n del Sitemap</legend>
            <dl>
               <dt><label for="register_post">Agregar automáticamente al sitemap los nuevos posts:</label></dt>
               <dd>
               	{html_radios name="register_post" values=[1,0] output=["S&iacute;", "No"] selected=$tsSitemap.register_post}
               </dd>  
           	</dl>
            <dl>
            	<dt><label for="update_post">Actualizar fecha de modificación al editar posts:</label></dt>
            	<dd>
               	{html_radios name="update_post" values=[1,0] output=["S&iacute;", "No"] selected=$tsSitemap.update_post}
               </dd>    
            </dl>
            <dl>
               <dt><label for="register_foto">Agregar automáticamente al sitemap las nuevas fotos:</label></dt>
       			<dd>
               	{html_radios name="register_foto" values=[1,0] output=["S&iacute;", "No"] selected=$tsSitemap.register_foto}  
               </dd>    
            </dl>
            <dl>
            	<dt><label for="update_foto">Actualizar fecha de modificación al editar fotos:</label></dt>
            	<dd>
               	{html_radios name="update_foto" values=[1,0] output=["S&iacute;", "No"] selected=$tsSitemap.update_foto}
            	</dd>
            </dl>
				<div class="buttons">
					<input type="submit" name="save" value="Guardar Cambios" class="btn"/>
					<a href="{$tsConfig.url}/admin/sitemap/" class="btn">Volver</a>
				</div>
         </fieldset>
      </form>
	{elseif $tsAct == 'nueva' || $tsAct == 'editar'}
      <form action="" method="post" autocomplete="off">
			<fieldset>
				<legend>{if $tsAct == 'nueva'}Agregar{else}Editar{/if} URL</legend>
				<dl>
					<dt><label for="url">Dirección:</label><span>Dirección URL.</span></dt>
					<dd><input class="form-control" type="text" id="url" name="url" value="{$tsURL.url}" /></dd>
				</dl>
				<dl>
					<dt><label for="prioridad">Prioridad:</label><span>Prioridad de la URL (Utilizar valores desde 0 a 1 ambos incluídos) Ejemplos: 1, 0.8, 0.3 etc..{$tsURL.prioridad}</span></dt>
					<dd>
						<select name="prioridad" id="prioridad">
							{html_options values=[0,1,2,3,4,5,6,7,8,9,10] output=['Prioridad 1','Prioridad 0.9','Prioridad 0.8','Prioridad 0.7','Prioridad 0.6','Prioridad 0.5','Prioridad 0.4','Prioridad 0.3','Prioridad 0.2','Prioridad 0.1','Prioridad 0'] selected=$tsURL.prioridad}
						</select>
					</dd>
				</dl>
            <dl>
					<dt><label for="frecuencia">Frecuencia de cambio:</label><span>Frecuencia de cambio de la página. ¿Cada cuanto aproximadamente se modificará el contenido de la página?:</span></dt>
					<dd>
						<select name="frecuencia" id="frecuencia">
							{html_options values=[0,1,2,3,4,5,6] output=['Never (nunca)','Always (siempre)','Daily (diariamente)','Hourly (cada hora)','Weekly (semanalmente)','Monthly (mensualmente)','Yearly (anualmente)'] selected=$tsURL.frecuencia}
						</select>				
					</dd>
				</dl>
				<div class="buttons">
					<input type="submit" name="save" value="{if $tsAct == 'nueva'}Agregar{else}Editar{/if} URL" class="btn"/>
					<a href="{$tsConfig.url}/admin/sitemap/" class="btn">Volver</a>
				</div>
			</fieldset>
		</form>
	{elseif $tsAct == 'actual'}
		<table cellpadding="0" cellspacing="0" border="0" class="admin_table" width="100%">
			<thead>
				<th>URL</th>
			</thead>
			<tbody>
				{foreach from=$tsSetURLActually item=u}
					<tr>
						<td><a href="{$u.url}" rel="internal">{$u.url}</a></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<div style="display:flex;justify-content:center;align-items:center;padding:1rem 0">
			<a href="{$tsConfig.url}/admin/sitemap/" class="btn">Volver</a>
		</div>
	{/if}
</div>