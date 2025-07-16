<div class="cat-list mb-3">
	<h4>Ordenar por</h4>
	<a href="{$tsConfig.url}/borradores/{if !empty($tsAction)}{$tsAction}/{/if}{if !empty($tsID)}{$tsID}{/if}?order=fecha" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsOrder == '' || $tsOrder == 'fecha'} active fw-bold{/if}">
		<span>Fecha guardado</span>
	</a>
	<a href="{$tsConfig.url}/borradores/{if !empty($tsAction)}{$tsAction}/{/if}{if !empty($tsID)}{$tsID}{/if}?order=titulo" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsOrder == 'titulo'} active fw-bold{/if}">
		<span>Titulo</span>
	</a>
	<a href="{$tsConfig.url}/borradores/{if !empty($tsAction)}{$tsAction}/{/if}{if !empty($tsID)}{$tsID}{/if}?order=categoria" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsOrder == 'categoria'} active fw-bold{/if}">
		<span>Categoría</span>
	</a>
</div>
