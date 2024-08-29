<div class="post-header mb-3 py-3">
   <div class="post-header--title">
      <div class="d-flex justify-content-between align-items-center">
         <h1 class="h3 my-2">{$tsPost.post_title}</h1>
         {include "m.posts_acciones.tpl"}
      </div>
      
      <div class="d-block d-lg-flex justify-content-between align-items-center">
         <span class="d-block"><a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/posts/{$tsPost.categoria.c_seo}/">{$tsPost.categoria.c_nombre}</a> - {$tsPost.post_date|hace:true}{if $tsPost.post_update} | <strong>Editado</strong>{/if}</span>
         <span><strong>{$tsPost.post_hits|human}</strong> Visitas &bull; <strong id="puntos_post">{$tsPost.post_puntos|human}</strong> Puntos &bull; <strong id="seguidores_post">{$tsPost.post_seguidores|human}</strong> Seguidores</span>
      </div>
   </div>
   <small>{$tsPost.post_read}</small>
</div>
<div class="post-contenedor">
   {if !$tsUser->is_member}{include "m.global_ads_728.tpl"}{/if}
        
	<span class="d-block px-4{if !$tsUser->is_member} mt-3{/if}">{$tsPost.post_body|unescape}</span>

   {if $tsPost.post_fuentes}
      <div class="pt-3 mt-3 border-top">
         <h5>Fuentes:</h5>
         {foreach $tsPost.post_fuentes key=titulo item=enlace name=coma}
            <a href="{$enlace}" target="_blank" class="fw-semibold text-decoration-none">{$titulo}</a>{if $smarty.foreach.coma.last}{else}, {/if}
         {/foreach}
      </div>
   {/if}
     
   {if $tsPost.user_firma && $tsConfig.c_allow_firma}
      <span class="d-block mt-2 py-1 firma monospace rounded text-center">{$tsPost.user_firma}</span>
   {/if}

   <div class="tags pt-3 my-3 d-flex gap-2 justify-content-start align-items-center flex-wrap">
      {foreach $tsPost.post_tags item=$tag}
         <a class="tag-item rounded d-block px-2 main-bg main-color text-decoration-none" href="{$tsConfig.url}/buscador/?e=tags&q={$tag}&autor=&cat=-1">#{$tag}</a>
      {/foreach}
   </div>
      {include "m.posts_metadata.tpl"}
   {include "m.posts_cambiar.tpl"}
   {include "m.posts_compartir.tpl"}
 </div>