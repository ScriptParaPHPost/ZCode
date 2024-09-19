<div class="boxy-title">
	<h3>Administrar Sitemap</h3>
</div>
<div id="res" class="boxy-content">
  {if $tsAct == ''}
		<ol style="list-style: decimal;">
			<li>Estas son las URLs que hay en la BD (base de datos), cuando las hayas seleccionado y editado, haz click donde pone Generar Sitemap para que tus cambios tengan efecto.</li>
		   <li>Si quieres restaurar el sitemap como por defecto solo pulsa donde dice Restaurar sitemap.</li>
		   <li>Y si quiere editar cualquiera URL de las ya creadas simplemente haga click en la acción que desee de las disponibles a la derecha de cada columna de la siguiente tabla.</li>
		   <li>El botón de "<strong>Generar Sitemap</strong>" sirve para, cuando borras, o añades una URL manualmente desde la admin, para que cuando acabes de eliminar y añadir todas las URLs que quieras, estas se carguen en el sitemap.</li>
		   <li>El botón "<strong>Configuración</strong>" te llevará a la sección donde puedes seleccionar la configuración del sitemap</li>
		   <li>El botón "<strong>Ver URLs incluídas en el sitemap</strong>", te mostrará las URLs ya incluídas en tu sitemap.</li>
		</ol>
		<hr class="separator" />
		
		<strong>Lista de URLs en la base de datos</strong>
		<table cellpadding="0" cellspacing="0" border="0" class="admin_table" width="100%">
			<thead>
				<th>ID</th>
				<th>URL</th>
				<th>Frecuencia</th>
				<th>Prioridad</th>
				<th>Fecha</th>
				<th>Acciones</th>
			</thead>
			<tbody>
				{foreach from=$tsURLs.data item=u}
					<tr style="font-size:.8rem;">
						<td style="text-align:center;font-weight:bold;">{$u.id}</td>
						<td style="text-align:left;width:50%"><a href="{$u.url}">{$u.url}</a></td>
						<td style="text-align:center;font-weight:bold;">{$u.frecuencia}</td>
						<td style="text-align:center;font-weight:bold;">{$u.prioridad}</td>
						<td>{$u.fecha|hace}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/sitemap/?act=editar&id={$u.id}"><img src="{$tsConfig.public}/images/icons/editar.png" title="Editar URL" /></a>
							<a href="{$tsConfig.url}/admin/sitemap?act=borrar&id={$u.id}"><img src="{$tsConfig.public}/images/icons/close.png" title="Borrar URL" /></a>
						</td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<td colspan="8">P&aacute;ginas: {$tsURLs.pages}</td>
			</tfoot>
		</table>
		<div style="display:flex;justify-content:flex-start;align-items:center;gap:.3rem;padding:1rem 0">
	      <a href="{$tsConfig.url}/admin/sitemap/?act=generar-sitemap" class="button">Generar Sitemap</a>
	      <a href="{$tsConfig.url}/admin/sitemap/?act=actualizar" class="button">Actualizar</a>
	     	<a href="{$tsConfig.url}/admin/sitemap/?act=config" class="button">Configuración</a>
	      <a href="{$tsConfig.url}/admin/sitemap/?act=actual" class="button">Ver URLs añadidas</a>
		</div>
	{elseif $tsAct == 'config'}
		<form action="" method="post" autocomplete="off">
         <fieldset>
            <legend>Configuraci&oacute;n del Sitemap</legend>
            <dl>
               <dt><label for="sm_posts">Agregar automáticamente al sitemap los nuevos posts:</label></dt>
               <dd>
               	{html_radios name="sm_posts" values=[1,0] output=["S&iacute;", "No"] selected=$tsConfig.sm_posts}
               </dd>  
           	</dl>
            <dl>
               <dt><label for="sm_fotos">Agregar automáticamente al sitemap las nuevas fotos:</label></dt>
       			<dd>
               	{html_radios name="sm_fotos" values=[1,0] output=["S&iacute;", "No"] selected=$tsConfig.sm_fotos}  
               </dd>    
            </dl>
            <dl>
            	<dt><label for="sm_update_p">Actualizar fecha de modificación al editar posts:</label></dt>
            	<dd>
               	{html_radios name="sm_update_p" values=[1,0] output=["S&iacute;", "No"] selected=$tsConfig.sm_update_p}
               </dd>    
            </dl>
            <dl>
            	<dt><label for="sm_update_f">Actualizar fecha de modificación al editar fotos:</label></dt>
            	<dd>
               	{html_radios name="sm_update_f" values=[1,0] output=["S&iacute;", "No"] selected=$tsConfig.sm_update_f}
            	</dd>
            </dl>
				<div class="buttons">
					<input type="submit" name="save" value="Guardar Cambios" class="button"/>
					<a href="{$tsConfig.url}/admin/sitemap/" class="button">Volver</a>
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
							{html_options values=[0,1,2,3,4,5,6,7,8,9,10] output=['Prioridad 1','Prioridad 0.9','Prioridad 0.8','Prioridad 0.7','Prioridad 0.6','Prioridad 0.5','Prioridad 0.4','Prioridad 0.3','Prioridad 0.2','Prioridad 0.1','Prioridad 0'] selected=$tsPrioridad[$tsURL.prioridad]}
						</select>
					</dd>
				</dl>
            <dl>
					<dt><label for="frecuencia">Frecuencia de cambio:</label><span>Frecuencia de cambio de la página. ¿Cada cuanto aproximadamente se modificará el contenido de la página?:</span></dt>
					<dd>
						<select name="frecuencia" id="frecuencia">
							{html_options values=[0,1,2,3,4,5,6] output=['Never (nunca)','Always (siempre)','Daily (diariamente)','Hourly (cada hora)','Weekly (semanalmente)','Monthly (mensualmente)','Yearly (anualmente)'] selected=$tsFrecuencia[$tsURL.frecuencia]}
						</select>				
					</dd>
				</dl>
				<div class="buttons">
					<input type="submit" name="save" value="Añadir URL" class="button"/>
					<a href="{$tsConfig.url}/admin/sitemap/" class="button">Volver</a>
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
			<a href="{$tsConfig.url}/admin/sitemap/" class="button">Volver</a>
		</div>
	{/if}
</div>