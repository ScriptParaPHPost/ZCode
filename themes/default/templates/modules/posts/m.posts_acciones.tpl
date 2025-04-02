<div class="post-action d-flex justify-content-end align-items-center column-gap-3 position-relative">
   <div class="admin-posts">
      <span role="button" id="box_post">{uicon name="menu-vertical" class="pe-none"}</span>
      <div class="position-absolute body-bg border pt-2 px-2 shadow rounded z-3 box_post" style="right: 1.5rem;top: -.5rem;width: 200px;display: none;">
         {if $tsPost.post_user == $tsUser->uid && $tsUser->is_admod == 0 && $tsUser->permisos.most == false && $tsUser->permisos.moayca == false && $tsUser->permisos.moo == false && $tsUser->permisos.moep == false && $tsUser->permisos.moedpo == false}
            <a href="{$tsConfig.url}/posts/editar/{$tsPost.post_id}" class="text-decoration-none py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3">
               {uicon name="pen" class="pe-none"}
               <span>Editar post</span>
            </a>
            <div role="button" class="py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3" onclick="borrar_post();">
               {uicon name="trash" class="pe-none"}
               <span>Borrar post</span>
            </div>
         {elseif ($tsUser->is_admod && $tsPost.post_status == 0) || $tsUser->permisos.most || $tsUser->permisos.moayca || $tsUser->permisos.moop || $tsUser->permisos.moep || $tsUser->permisos.moedpo}
            {if $tsUser->is_admod || $tsUser->permisos.moedpo || $tsAutor.user_id == $tsUser->uid}
               <a href="{$tsConfig.url}/posts/editar/{$tsPost.post_id}" class="text-decoration-none py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3">
                  {uicon name="pen" class="pe-none"}
                  <span>Editar post</span>
               </a>
            {/if}
            {if $tsUser->is_admod || $tsUser->permisos.moep || $tsAutor.user_id == $tsUser->uid}
               <div role="button" class="py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3" onclick="{if $tsAutor.user_id != $tsUser->uid}mod.posts.borrar({$tsPost.post_id}, 'posts', null);{else}borrar_post();{/if}">
                  {uicon name="trash" class="pe-none"}
                  <span>Borrar post</span>
               </div>
            {/if}
            {if $tsUser->is_admod || $tsUser->permisos.most}
               <div role="button" class="py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3" onclick="mod.reboot({$tsPost.post_id}, 'posts', 'sticky', false); if($(this).find('span').text() == 'Poner Sticky') $(this).find('span').text('Quitar Sticky'); else $(this).find('span').text('Poner Sticky');">
                  {uicon name="versions" class="pe-none"}
                  <span>{if $tsPost.post_sticky == 1}Quitar{else}Poner{/if} Sticky</span>
               </div>
            {/if}
            {if $tsUser->is_admod || $tsUser->permisos.moayca}
               <div role="button" class="py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3" onclick="mod.reboot({$tsPost.post_id}, 'posts', 'openclosed', false); if($(this).find('span').text() == 'Cerrar Post') $(this).find('span').text('Abrir Post'); else $(this).find('span').text('Cerrar Post');">
                  {uicon name="lock" class="pe-none"}
                  <span>{if $tsPost.post_block_comments == 1}Abrir{else}Cerrar{/if} Post</span>
               </div>
            {/if}
            {if $tsUser->is_admod || $tsUser->permisos.moop}
               <div role="button" class="py-1 px-2 mb-2 border rounded d-flex justify-content-start align-items-center column-gap-3" onclick="$('#desapprove').slideToggle(); $(this).fadeOut().remove();">
                  {uicon name="eye-closed" class="pe-none"}
                  <span>Ocultar post</span>
               </div>
            {/if}
         {/if}
      </div>
   </div>
</div>