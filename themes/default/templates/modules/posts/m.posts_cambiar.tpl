 <div class="post-antsig">
       
      <div class="post-anterior{if !$tsAnterior.post_title} off{/if}">
         <a href="{$tsAnterior.post_url}" title="{if $tsAnterior.post_title}{$tsAnterior.post_title}{else}No hay posts{/if}" class="d-flex justify-content-start align-items-center text-decoration-none h-100">
            <div class="icon d-flex justify-content-center align-items-center me-2">
               {uicon name="push-left" class="pe-none" size="2rem"}
            </div>
            <div class="titulo">
               <small class="text-uppercase small">No te piedas</small>
               <span class="text-truncate h6">{if $tsAnterior.post_title}{$tsAnterior.post_title}{else}No hay posts{/if}</span>
            </div>
         </a>
      </div>
       <div class="post-aleatorio d-flex justify-content-center align-items-center">
           <a href="{$tsConfig.url}/posts/fortuitae" title="Post aleatorio" class="d-inline-block text-decoration-none">
               <div class="icon">
                  {uicon name="reverse-alt" class="pe-none" size="2rem"}
               </div>
           </a>
       </div>
       <div class="post-siguiente{if !$tsSiguente.post_title} off{/if}">
           <a href="{$tsSiguente.post_url}" title="{if $tsSiguente.post_title}{$tsSiguente.post_title}{else}No hay posts{/if}" class="d-flex justify-content-end align-items-center t-end text-decoration-none h-100">
               <div class="titulo">
                   <small class="text-uppercase small">A continuación</small>
                   <span class="text-truncate h6">{if $tsSiguente.post_title}{$tsSiguente.post_title}{else}No hay posts{/if}</span>
               </div>
               <div class="icon d-flex justify-content-center align-items-center ms-2">
                  {uicon name="push-right" class="pe-none" size="2rem"}
               </div>
           </a>
       </div>

   </div>