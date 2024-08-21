<div class="boxy-title">
   <h3>Editar .htaccess</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Configuraciones guardadas</div>{/if}
	{if $tsAct === ''}
		<form action="" method="post" autocomplete="off">
			<fieldset>
		     	<legend>Configuraci&oacute;n ErrorDocument</legend>
		     	<dl>
		     	   <dt><label for="ai_error">Activar páginas de errores:</label></dt>
		     	   <dd>
		     	   	{foreach $tsErrorDocument key=error item=info}
			     	   	<div class="checkbox mb-3">
			     	   		<input type="checkbox" class="up-checkbox" name="error[]" value="{$info.lines.1}"{if $info.active} checked{/if}>
			     	   		<span>ErrorDocument {$info.lines.1} <small class="d-block"><strong>{$info.type}</strong>: {$info.description}</small></span>
			     	   	</div>
			     	   {/foreach}
		     	   </dd>
		     	</dl>
		   </fieldset>
         <p>
         	<input type="button" onclick="htaccess.backup()" value="Crear copia .htaccess" class="btn" /> 
         	<input type="submit" name="saveError" value="Guardar ErrorDocument" class="btn" />
         </p>
		</form>
		<br>
		<form action="" method="post" autocomplete="off">
			<fieldset>
		     	<legend>Configuraci&oacute;n RewriteRules</legend>
		     	<dl>
		     	   <dt><label for="ai_active">Activar RewriteBase:</label></dt>
		     	   <dd>{html_radios name="active" values=[1, 0] id="active" output=['Activar', 'Desactivar'] selected=$tsRewriteRules.base.active class="radio"}</dd>
		     	</dl>
		     	<dl>
		     	   <dt><label for="ai_site">Añadir regla:</label><span>Redirección de tu sitio de http:// a https://</span></dt>
		     	   <dd><input type="text" id="ai_site" name="site" value="{$tsConfig.domain}" /></dd>
		     	</dl>
			</fieldset>
         <p>
         	<input type="button" onclick="htaccess.backup()" value="Crear copia .htaccess" class="btn" /> 
         	<input type="submit" name="saveRewrite" value="Guardar RewriteRules" class="btn" />
         </p>
		</form>
	{else}
	{/if}
</div>