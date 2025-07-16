<div class="cat-list" id="borradores-categorias">
	<h4>Categorias</h4>
	<a href="{$tsConfig.url}/borradores/categoria/" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsAction == 'categoria' && empty($tsID)} active fw-bold{/if}">
		<span>Ver todas</span>
		<span class="count main-bg main-color rounded px-2">{$categorias|count}</span>
	</a>
	{foreach $categorias item=c}
		<a href="{$tsConfig.url}/borradores/categoria/{$c.cid}" class="text-decoration-none d-flex justify-content-between align-items-center p-1{if $tsID == $c.cid} active fw-bold{/if}">
			<span>{$c.c_nombre}</span>
			<span class="count main-bg main-color rounded px-2">{$c.total}</span>
		</a>
	{/foreach}
</div>