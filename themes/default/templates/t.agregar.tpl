{include "main_header.tpl"}
	<div style="display:none" id="preview"></div>
   <div id="form_div">
      {if $tsUser->is_admod || $tsUser->permisos.gopp}
         <div class="form-add-post">
            <form action="{$tsConfig.url}/agregar/{if $tsAction == 'editar'}editar?pid={$tsPid}{/if}" method="POST" name="newpost" autocomplete="off" enctype="multipart/form-data">
               {if $tsAction == 'editar'}
                  <input type="hidden" value="editar" name="action"/>
                  <input type="hidden" value="{$tsDraft.bid}" name="borrador_id"/>
               {/if}
               <div class="row">
                  <div class="col-12 col-lg-8">{include "m.agregar_form.tpl"}</div>
                  <div class="col-12 col-lg-4">{include "m.agregar_sidebar.tpl"}</div>
               </div>
            </form>
         </div>
      {else}
         <div class="empty">Lo sentimos, pero no puedes publicar un nuevo post.</div>
      {/if}
   </div>
{include "main_footer.tpl"}