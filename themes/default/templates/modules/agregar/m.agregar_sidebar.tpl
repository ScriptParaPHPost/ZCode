{include "m.agregar_portada.tpl"}

<div class="upform-group">
   <label class="upform-label" for="categoria">Categor&iacute;a</label>
   <div class="upform-group-input">
      <select class="upform-select" name="categoria" id="categoria" size="8">
         <option class="upform-select--option" value="" selected>Elegir una categor&iacute;a</option>
         {foreach from=$tsCategorias item=c}
            <option class="upform-select--option" value="{$c.cid}"{if $tsDraft.b_category == $c.cid} selected{/if} style="background-image:url({$c.c_img})">{$c.c_nombre}</option>
         {/foreach}
      </select>
   </div>
   <small class="upform-status help"></small>
</div>

<div class="upform-group">
   <span class="fw-bold">Opciones</span>
   {if $tsUser->is_admod == 1}
      <div class="upform-check mb-3">
         <label>
            <input type="checkbox" name="sponsored" id="patrocinado"{if $tsDraft.b_sponsored == 1} checked{/if}>
            <span class="upform-check-icon"></span>
            <span>Patrocinado <small class="d-block">Resalta este post entre los dem&aacute;s.</small></span>
         </label>
      </div>
   {/if}
   {if $tsUser->is_admod || $tsUser->permisos.most}
      <div class="upform-check mb-3">
         <label>
            <input type="checkbox" name="sticky" id="sticky"{if $tsDraft.b_sticky == 1} checked{/if}>
            <span class="upform-check-icon"></span>
            <span>Sticky</span>
            <small class="d-block">Colocar a este post fijo en la home.</small>
         </label>
      </div>
   {/if}
   <div class="upform-check mb-3">
      <label>
         <input type="checkbox" name="private" id="privado"{if $tsDraft.b_private == 1} checked{/if}>
         <span class="upform-check-icon"></span>
         <span>S&oacute;lo usuarios registrados.</span>
         <small class="d-block">Tu post ser&aacute; visto s&oacute;lo por los usuarios que tengan cuenta!</small>
      </label>
   </div>
   <div class="upform-check mb-3">
      <label>
         <input type="checkbox" name="block_comments" id="sin_comentarios"{if $tsDraft.b_block_comments == 1} checked{/if}>
         <span class="upform-check-icon"></span>
         <span>Cerrar Comentarios.</span>
         <small class="d-block">Si tu post es pol&eacute;mico ser&iacute;a mejor que cierres los comentarios.</small>
      </label>
   </div>
   <div class="upform-check mb-3">
      <label>
         <input type="checkbox" name="smileys" id="smileys"{if $tsDraft.b_visitantes == 1} checked{/if}>
         <span class="upform-check-icon"></span>
         <span>Sin Smileys.</span>
         <small class="d-block">Si tu post no necesita smileys, desact&iacute;valos.</small>
      </label>
   </div>

</div>