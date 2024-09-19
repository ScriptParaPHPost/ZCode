<div class="shout p-3 mb-3 rounded position-relative overflow-hidden" id="muroStrem">
   <div class="shout__content position-relative">
      <textarea id="wall" class="py-2 px-3 rounded w-100" placeholder="{if $tsInfo.uid == $tsUser->uid}&iquest;Qu&eacute; est&aacute;s pensando?{else}Escribe algo....{/if}"></textarea>
      <div class="buttons position-absolute gap-1 d-flex py-1 px-2">
         <button class="d-flex justify-content-center align-items-center width width-3 height height-3" role="button" id="attach-file" title="Adjuntar archivo">{uicon name="paperclip" size="1.5rem"}</button>
         <button class="d-flex justify-content-center align-items-center width width-3 height height-3" role="button" title="Publicar ahora..." onclick="muro.stream.compartir();">{uicon name="paper-plane-alt" size="1.5rem"}</button>
      </div> 
   </div>
   <div class="input-append"></div>
   <div class="shout__buttons position-relative" style="display: none;">
      <div class="shout__pub d-flex justify-content-center align-items-center gap-2 p-3">
         <div role="button" onclick="muro.stream.load('foto', this)" title="Adjuntar una foto">{uicon name="picture" size="3rem"}</div>
         <div role="button" onclick="muro.stream.load('enlace', this)" title="Adjuntar un enlace">{uicon name="link" size="3rem"}</div>
         <div role="button" onclick="muro.stream.load('video', this)" title="Adjuntar un video">{uicon name="tv-mode" size="3rem"}</div>
         <div role="button" class="close position-absolute" onclick="muro.stream.load('stream', this)">{uicon name="cross"}</div>
      </div>
   </div>
</div>