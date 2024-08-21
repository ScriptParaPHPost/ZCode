<div class="{if $c.respuesta}ms-5 {/if}comment rounded shadow mb-3 d-flex justify-content-start align-items-start position-relative" id="comment{$c.cid}"{if $tsPost.post_user == $c.c_user} data-author{/if}{if $c.c_status == 1 || !$c.user_activo && $tsUser->is_admod} style="opacity:0.5"{/if}>
   
   <div class="comment--left p-2">
      <div class="mx-auto avatar avatar-{if $c.respuesta}5{else}6{/if}">
         <img src="{$c.c_avatar}" alt="Avatar de {$c.user_name}" class="w-100 h-100 object-fit-cover">
      </div>
   </div>
   <div class="comment--right flex-grow-1">
      <div class="comment--tools d-flex justify-content-between align-items-center{if !$c.respuesta} p-1{/if}">

         <div class="comment--name">
            <a href="{$tsConfig.url}/perfil/{$c.user_name}" class="fw-semibold text-decoration-none">{$c.user_name|verificado}</a>
             - <time class="fst-italic small text-center mt-2">{$c.c_date|hace:true}</time>
            {if $tsUser->is_admod} (<span style="color:red;">IP:</span> <a href="{$tsConfig.url}/moderacion/buscador/1/1/{$c.c_ip}" class="geoip" target="_blank">{$c.c_ip}</a>){/if}
         </div>
         {include "comentario/comentario-herramienta.tpl"}
         
      </div>
      <div class="comment--body{if !$c.respuesta} pb-2{/if}" data-reply="{$c.c_body}">
         {if $c.c_status == 1 || !$c.user_activo || $c.user_baneado}
         <div>
            <span class="d-block">Escondido {if $c.c_status == 1}por un moderador{elseif $c.user_activo == 0}por pertener a una cuenta desactivada{else}por pertenecer a una cuenta baneada{/if}.</span>
            <span role="button" onclick="$('#comment-show_{$c.cid}').slideDown(); $(this).parent().slideUp(); return false;">Click para verlo</a>.
         </div>
         <div id="comment-show_{$c.cid}" style="display:none">
         {/if}
            {$c.c_html} {if $c.c_update}<small class="fst-italic" style="color:#888; font-size: 0.75rem;"> - Editado {$c.c_update|hace:true}</small>{/if}
         {if $c.c_status == 1 || !$c.user_activo}
            </div>
         {/if}
      </div>
   </div>

</div>