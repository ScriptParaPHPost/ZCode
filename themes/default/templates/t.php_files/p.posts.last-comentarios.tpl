<div id="filterByTodos" class="filterBy cleanlist">
   {foreach from=$tsComments key=i item=c}
      <div class="entry-animation d-flex justify-content-start align-items-center gap-2 height height-6" style="line-height: 1.325rem;">
         <div class="number h3 m-0 text-center fw-bolder align-content-center">
            {if $i+1 < 10}0{/if}{$i+1}
         </div>
         <div class="post-comment">
            <a href="{$c.cm_url}" class="text-decoration-none text-truncate truncate-1 w-100 fw-semibold">{$c.post_title}</a>
            <a href="{$tsConfig.url}/perfil/{$c.user_name}/" class="text-decoration-none fw-normal" style="color:{if $c.post_status == 3}#BBB{elseif $c.post_status == 1} purple {elseif $c.post_status == 2}rosyBrown{elseif $c.c_status == 1}coral{elseif $c.user_activo == 0}brown{elseif $c.user_baneado == 1}orange{else}var(--main-bg){/if};" title="{if $c.post_status == 3} El post se encuentra en revisi&oacute;n{elseif $c.post_status == 1} El post se encuentra oculto por acumulaci&oacute;n de denuncias {elseif $c.post_status == 2} El post se encuentra eliminado {elseif $c.c_status == 1} El comentario est&aacute; oculto{elseif $c.user_activo == 0}El autor del comentario tiene la cuenta desactivada{elseif $c.user_baneado == 1}El autor del comentario tiene la cuenta suspendida{else}{$c.user_name}{/if}">{$c.user_name|verificado}</a>
         </div>
      </div>
   {foreachelse}
      <div class="empty">No hay comentarios</div>
   {/foreach}
</div>