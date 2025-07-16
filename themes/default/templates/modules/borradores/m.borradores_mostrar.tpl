<div class="cat-list mb-3">
	<h4>Mostrar</h4>
	<a href="{$tsConfig.url}/borradores/{if !empty($tsOrder)}?order={$tsOrder}{/if}" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsAction == ''} active fw-bold{/if}">
		<span>Todos</span> 
		<span class="count main-bg main-color rounded px-2">{$estados.total}</span>
	</a>
	<a href="{$tsConfig.url}/borradores/borradores/{if !empty($tsOrder)}?order={$tsOrder}{/if}" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsAction == 'borradores'} active fw-bold{/if}">
		<span>Borradores</span>
		<span class="count main-bg main-color rounded px-2">{$estados.borradores}</span>
	</a>
	<a href="{$tsConfig.url}/borradores/eliminados/{if !empty($tsOrder)}?order={$tsOrder}{/if}" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsAction == 'eliminados'} active fw-bold{/if}">
		<span>Eliminados</span>
		<span class="count main-bg main-color rounded px-2">{$estados.eliminados}</span>
	</a>
</div>