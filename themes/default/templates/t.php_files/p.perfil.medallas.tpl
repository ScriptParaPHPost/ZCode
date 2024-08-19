1:
<div id="perfil_medallas" class="widget" status="activo">
	<div class="title-w border mb-3 p-2 rounded d-flex justify-content-between align-items-center">
		<h3 class="m-0 fs-5">Medallas de {$tsUsername} ({$tsMedallas.total})</h3>
	 </div>
	 {if $tsMedallas.medallas}
		<div class="listado">
		  	{foreach from=$tsMedallas.medallas item=m}
			  	<div class="border-bottom p-2 mb-2">
					<div class="listado-content d-flex justify-content-start align-items-center column-gap-2">
						<div class="listado-avatar avatar avatar-3">
							<img src="{$m.m_image}" class="avatar avatar-3" title="{$m.medal_date|hace:true}"/>
						</div>
						<div class="txt">
							<strong class="d-block">{$m.m_title}</strong>
							{$m.m_description}
						</div>
					</div>
			  	</div>
		 	{/foreach}
	 	</div>
	{else}
		<div class="empty">No tiene medallas</div>
	{/if}
</div>