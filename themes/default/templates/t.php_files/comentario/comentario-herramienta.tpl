{if $tsUser->is_member}
   <div class="comment--options position-relative d-flex justify-content-end align-items-center rounded{if $c.respuesta} border-0{/if}">
      {if $c.votado > 0}
         <div class="position-absolute fw-bold d-flex justify-content-end align-items-center column-gap-2 voto voto--{if $c.c_votos >= 0}up{else}down{/if}" id="votos_{$c.cid}">{uicon name="face-happy" class="pe-none"} {$c.votado|human}</div>
      {/if}
      {if $tsUser->uid != $c.c_user && ($tsUser->permisos.govpp || $tsUser->permisos.govpn || $tsUser->is_admod)}
         {*if $tsUser->permisos.govpp || $tsUser->is_admod}
            <span role="button" class="thumbs-up" onclick="comentario.votar({$c.cid}, 'up')" title="Votar comentario">{uicon name="thumbs-up" class="pe-none"}</span>
         {/if}
         {if $tsUser->permisos.govpn || $tsUser->is_admod}
            <span role="button" class="thumbs-down" onclick="comentario.votar({$c.cid}, 'down')" title="Votar comentario">{uicon name="thumbs-down" class="pe-none"}</span>
         {/if*}
         <span role="button" class="" onclick="comentario.reaccionar({$c.cid})" title="Reaccionar a comentario">{uicon name="face-happy" class="pe-none"}</span>
         <div class="reaccion position-absolute body-bg rounded d-none" id="{$c.cid}">
            {foreach $tsReactions key=type item=reaction}
               <span class="flex-grow-1 d-flex justify-content-center align-items-center p-2" onclick="comentario.reaccion({$c.cid}, '{$type}')"  tooltip="{$reaction}" data-reaction="{$type}">
                  {uicon name="$type" folder="other" class="pe-none"} 
               </span>
            {/foreach}
         </div>
      {/if}
      
      {*<span role="button" onclick="comentario.citar({$c.cid}, '{$c.user_name}')" title="Citar comentario">{uicon name="thread" class="pe-none"}</span>*}

      {if $reply || $tsUser->uid != $c.user_id}
         <span role="button" onclick="comentario.responser({$c.cid})" title="Responder comentario">{uicon name="backward" class="pe-none"}</span>
      {/if}
      {if ($c.c_user == $tsUser->uid && $tsUser->permisos.godpc) || $tsUser->is_admod || $tsUser->permisos.moecp}
         <span role="button" onclick="comentario.borrar({$c.cid}, {$c.c_user}, {$c.c_post_id})" title="Borrar comentario">{uicon name="trash-alt" class="pe-none"}</span>
      {/if}
      {uicon name="menu-vertical" role="button" attrs=['cid' => {$c.cid}]}

      <div class="dropdown-options position-absolute z-3 body-bg shadow rounded p-2" dropdown="{$c.cid}" style="display:none;">
         {if $tsUser->is_member && $tsUser->info.user_id != $c.c_user}
            <div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="mensaje.nuevo('{$c.user_name}'); return false">Enviar Mensaje</span></div>
            <div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="bloquear({$c.c_user}, {if $tsComments.block}false{else}true{/if}, 'comentarios')">{if $tsComments.block}Desb{else}B{/if}loquear</span></div>
         {/if}
         {if ($c.c_user == $tsUser->uid && $tsUser->permisos.goepc) || $tsUser->is_admod || $tsUser->permisos.moedcopo}
            <div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="comentario.editar({$c.cid});">{if $c.c_user == $tsUser->uid}Editar{else}Moderar{/if} comentario</span></div>
         {/if}
         {if ($c.c_user == $tsUser->uid && $tsUser->permisos.godpc) || $tsUser->is_admod || $tsUser->permisos.moecp}
            {if $tsUser->is_admod || $tsUser->permisos.moaydcp}
               <div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="ocultar_com({$c.cid}, {$c.c_user})">{if $c.c_status == 1}Mostrar/Ocultar{else}Ocultar/Mostrar{/if}</span></div>
            {/if}
         {/if}
      </div>
   </div>
{/if}