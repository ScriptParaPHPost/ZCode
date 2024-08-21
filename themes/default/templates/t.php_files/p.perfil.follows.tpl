1:
{if $tsHide != 'true'}<div id="perfil_{$tsType}" class="widget" status="activo">{/if}
<div class="title-w border p-2 rounded d-flex justify-content-between align-items-center mb-3">
	<h3 class="m-0 fs-5">{if $tsType == 'seguidores'}Usuarios que siguen a {$tsUsername}{else}Usuarios que {$tsUsername} sigue{/if}</h3>
</div>
{if $tsData.data}
	<div class="row">
		{foreach from=$tsData.data item=u}
		  <div class="col-6 col-lg-4">
				<div class="position-relative d-grid column-gap-2 border rounded p-2" style="grid-template-columns: 4rem 1fr;">
					<a href="{$tsConfig.url}/perfil/{$u.user_name}" class="d-block avatar avatar-7 rounded overflow-hidden">
						<img src="{$u.avatar}" class="w-100 h-100"/>
					</a>
					<div class="txt">
						<a href="{$tsConfig.url}/perfil/{$u.user_name}" class="d-block text-decoration-none fw-semibold">{$u.user_name|verificado}</a>
						<em class="grey small d-block">{if empty($u.p_mensaje)}Sin mensaje{else}{$u.p_mensaje}{/if}</em>
						<img src="{$u.pais_image}" alt="{$u.pais}" class="position-absolute avatar avatar-1 border-0" style="top:1rem;right:1rem"/>
					</div>
				</div>
		  </div>
		{/foreach}
		<div class="listado-paginador d-flex justify-content-around align-items-center column-gap-3">
			{if $tsData.pages.prev != 0}<span role="button" onclick="perfil.follows('{$tsType}', {$tsData.pages.prev}); return false;" class="anterior-listado btn">Anterior</span>{/if}
			{if $tsData.pages.next != 0}<span role="button" onclick="perfil.follows('{$tsType}', {$tsData.pages.next}); return false;" class="siguiente-listado btn">Siguiente</span>{/if}
		</div>
	 </div>
{else}
	<div class="empty">{if $tsType == 'seguidores'}No tiene seguidores{else}No sigue usuarios{/if}</div>
{/if}    
{if $tsHide != 'true'}</div>{/if}