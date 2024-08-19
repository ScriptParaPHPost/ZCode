{if $tsType == 'new'} 
<div class="comment rounded shadow mb-3 d-flex justify-content-start align-items-start position-relative" id="comment{$tsComment.0}"{if $tsComment.4 == $tsUser->uid} data-author{/if}>
   
   <div class="comment--left p-2">
	   <div class="mx-auto avatar avatar-8">
	      <img src="{$tsUser->use_avatar}" alt="Avatar de {$tsUser->nick}" class="w-100 h-100 object-fit-cover">
	   </div>
	</div>
   <div class="comment--right flex-grow-1">
      <div class="comment--tools d-flex justify-content-between align-items-center p-1">

         <div class="comment--name">
            <a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="fw-semibold text-decoration-none">{$tsUser->nick}</a>
             - <time class="fst-italic small text-center mt-2">{$tsComment.3|hace:true}</time>
            {if $tsUser->is_admod} (<span style="color:red;">IP:</span> <a href="{$tsConfig.url}/moderacion/buscador/1/1/{$tsComment.6}" class="geoip" target="_blank">{$tsComment.6}</a>){/if}
         </div>
         {if $tsUser->is_member}
			   <div class="comment--options position-relative d-flex justify-content-end align-items-center rounded">
			   	<span role="button" onclick="comentario.citar({$tsComment.0}, '{$tsUser->nick}')" title="Citar comentario">{uicon name="thread" class="pe-none"}</span>
			      {if $tsUser->is_admod || $tsUser->permisos.godpc}
			      	<span role="button" onclick="comentario.borrar({$tsComment.0}, {$tsUser->uid})" title="Borrar comentario">{uicon name="trash-alt" class="pe-none"}</span>
			      {/if}
			      {uicon name="menu-vertical" role="button" attrs=['cid', {$tsComment.0}]}

			      <div class="dropdown-options position-absolute z-3 body-bg shadow rounded p-2" style="display:none;">
			         {if $tsUser->is_admod || $tsUser->permisos.goepc}
							<div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="comentario.editar({$tsComment.0}, 'show');">Editar comentario</span></div>
						{/if}
						{if $tsUser->is_admod || $tsUser->permisos.moaydcp}
               		<div class="option-item rounded px-3 py-1 mb-2" role="button"><span class="d-block" onclick="ocultar_com({$tsComment.0}, {$tsUser->uid})">Ocultar/Mostrar</span></div>
						{/if}
			      </div>
			   </div>
			{/if}
         
      </div>
      <div class="comment--body pt-1 pb-2" data-reply="{$tsComment.5}">
         {$tsComment.1}
      </div>
   </div>

</div>

{elseif $tsType == 'edit'}
	 <div id="preview" class="box_cuerpo" style="margin: -15px 0 0; font-size:13px; line-height: 1.4em; min-width:300px;max-width: 760px; padding: 12px 20px; overflow-y: auto; text-align: left; border-top:1px solid #CCC">
		  <div id="new-com-html">{$tsComment.1|nl2br}</div>
		  <div id="new-com-bbcode" style="display:none">{$tsComment.5}</div>
	 </div>
{/if}