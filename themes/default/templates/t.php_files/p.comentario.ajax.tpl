{if $tsComments.num > 0}
<div class="comments py-1">
   {foreach from=$tsComments.data item=c}
      {include "comentario/comentario-ajax.tpl"}

      {if $c.respuestas.num > 0}
         {foreach from=$c.respuestas.data item=c}
            {include "comentario/comentario-ajax.tpl"}
         {/foreach}
      {/if}
      
      <div id="respuestas{$c.cid}"></div>
      {include "comentario/comentario-responder.tpl"}

   {/foreach}
</div>
{else}
   <div id="no-comments" class="empty">Este post no tiene comentarios, S&eacute; el primero!</div>
{/if}
<div id="nuevos"></div>