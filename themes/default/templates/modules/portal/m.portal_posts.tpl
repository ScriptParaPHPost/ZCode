<div id="portal_posts" class="showHide lastPosts" status="" style="display:none">
	<div class="up-card--header up--reverse" icon="true">
		<div class="up-header--icon" onclick="$('#config_posts').slideDown();" title="Configurar" role="button">{uicon name="cog" folder="prime" class="pe-none"}</div>
		<div class="up-header--title">
			<span>&Uacute;ltimos posts de tu inter&eacute;s</span>
		</div>
	</div>
   <div id="config_posts" style="display:none">
      <div class="empty">Elige las categor&iacute;as que quieras filtrar en los &uacute;ltimos posts.</div>
      <div class="row row-cols-3" id="config_inputs">
        	{foreach from=$tsCategories item=c}
            <div class="col">
            	<label class="check-invisible" role="button">
            		<input type="checkbox" name="cats" value="{$c.cid}"{if $c.check == 1} checked="true"{/if} />
            		<span style="background: url('{$c.c_img}') no-repeat .325rem center / 1rem;padding: .325rem .325rem .325rem 1.75rem;">{$c.c_nombre}</span>
            	</label>
            </div>
        	{/foreach}
      </div>
      <span role="button" onclick="portal.save_configs();" class="next btn">Guardar cambios &raquo;</span>
   </div>
   <div id="portal_posts_content"></div>
</div>