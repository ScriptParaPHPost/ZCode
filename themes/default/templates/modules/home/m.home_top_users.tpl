<section class="up-card" category="topsUser">
	<div class="up-card--header" icon="true">
		<a class="up-header--icon" href="{$tsConfig.url}/top/" title="Ver más">
			{uicon name="trophy"}
		</a>
		<div class="up-header--title">
			<span>TOPs usuarios</span>
		</div>
	</div>
	<div class="up-card--body">
		<div class="filter fw-semibold d-flex my-1">
			<span class="flex-grow-1 d-block text-center small rounded py-1" role="button" data-active="false" data-category="topsUser" data-period="ayer">Ayer</span>
			<span class="flex-grow-1 d-block text-center small rounded py-1" role="button" data-active="false" data-category="topsUser" data-period="semana">Semana</span>
			<span class="flex-grow-1 d-block text-center small rounded py-1" role="button" data-active="false" data-category="topsUser" data-period="mes">Mes</span>
			<span class="flex-grow-1 d-block text-center small rounded py-1" role="button" data-active="true" data-category="topsUser" data-period="historico">Hist&oacute;rico</span>
		</div>
		<div class="filterShow">
			{foreach from=$tsTopUsers key=i item=u}
				<div class="filterShow-item d-flex entry-animation">
					<div class="filterShow-item--position text-center fw-bold flex-grow-0">{if $i+1 < 10}0{/if}{$i+1}</div>
					<div class="filterShow-item--title text-truncate flex-grow-1"><a href="{$tsConfig.url}/perfil/{$u.user_name}" rel="internal" class="text-truncate text-decoration-none w-100 d-block">{$u.user_name|verificado}</a></div>
					<div class="filterShow-item--number text-center flex-grow-0">{$u.total}</div>
				</div>
			{foreachelse}
				<div class="empty">No hay usuarios</div>
			{/foreach}
		</div>
	</div>
</section>