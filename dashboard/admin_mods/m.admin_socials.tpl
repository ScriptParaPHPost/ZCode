<div class="boxy-title">
   <h3>Configurar redes sociales</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsAct == ''}

      <div class="row">
         {foreach from=$tsSocials item=social}
            <div class="col-12 col-lg-6">
               <div class="rounded shadow border p-2 mb-3 position-relative" id="{$social.social_id}">
                  <div class="fw-semibold text-capitalize h4">{$social.social_name}</div>
                  <div>Client ID: <pre><code>{$social.social_client_id}</code></pre></div>
                  <div>Client Secret: <pre><code>{$social.social_client_secret}</code></pre></div>
                  <div>Redirect URI: <pre><code>{$social.social_redirect_uri}</code></pre></div>
                  <div class="position-absolute" style="top: 1rem; right: 1rem;">
                     <div class="admin_actions d-flex justify-content-end align-items-center column-gap-2">
                        <a href="{$tsConfig.url}/admin/socials?act=editar&id={$social.social_id}" title="Editar red social">{uicon name="pen" class="pe-none"}</a>
                        <a href="{$tsConfig.url}/admin/socials?act=borrar&id={$social.social_id}" title="Borrar red social">{uicon name="trash-alt" class="pe-none"}</a>
                     </div>
                  </div>
               </div>
            </div>
         {foreachelse}
            <div class="col-12">
               <div class="empty">Sin conexiones</div>
            </div>
         {/foreach}
      </div>
		<a href="{$tsConfig.url}/admin/socials?act=nueva" class="btn text-decoration-none mb-3 btnOk">Agregar Nueva Red social</a>
   {elseif $tsAct === 'nueva' || $tsAct === 'editar'}
	   <form action="" method="post" autocomplete="off">
	      <fieldset>
	         <legend><span style="text-transform: capitalize;">{$tsAct}</span> red social</legend>
	         {if $tsAct === 'editar'}
	         	<input type="hidden" name="social_id" value="{$tsSocial.social_id}">
	         {else}
	         	<dl>
            		<dt><label for="social_name">Sitio:</label></dt>
            		<dd>
            			{html_options name='social_name' id='social_name' options=$tsNetsSocials selected=$tsSocial.social_name class="form-select"}
            		</dd>
         		</dl>
	         {/if}
	         <dl>
            	<dt><label for="clientid">Client-ID:</label></dt>
            	<dd><input class="form-control" type="text" id="clientid" name="social_client_id" value="{$tsSocial.social_client_id}" /></dd>
         	</dl>
	         <dl>
            	<dt><label for="clientsecret">Client-Secret:</label></dt>
            	<dd><input class="form-control" type="text" id="clientsecret" name="social_client_secret" value="{$tsSocial.social_client_secret}" /></dd>
         	</dl>
	         <dl class="position-relative">
            	<dt><label for="redirect_uri">Redirect URL:</label></dt>
            	<dd><input class="form-control" type="text" id="redirect_uri" value="{$tsSocial.social_redirect_uri}" />
                  <small class="position-absolute" style="right:4rem;top:1.325rem"></small>
                  <span style="top:1.325rem;right:1.325rem;" role="button" class="position-absolute" id="botonCopiar" title="Copiar"><span uicon="replicate"></span></span>
               </dd>
         	</dl>
	         <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary" /></p>
	      </fieldset>
	   </form>
	  {/if}
</div>