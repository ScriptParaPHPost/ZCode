<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="graph-box"}
		</div>
		<div class="up-header--title">
			<span>Estad&iacute;sticas</span>
		</div>
	</div>
	<div class="up-card--body up-card--stats">
		<div class="d-grid gap-2">
			<div class="text-center text-uppercase small py-3 position-relative">
				{uicon name="graph-box" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
				<a href="{$tsConfig.url}/usuarios/?online=true" class="text-decoration-none z-2 fw-bold position-relative body-color">
					<span class="h3 d-block m-0">{$tsStats.stats_online}</span> online
				</a>
			</div>
			<div class="text-center text-uppercase small py-3 position-relative">
				{uicon name="users" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
				<a href="{$tsConfig.url}/usuarios/" class="text-decoration-none z-2 fw-bold position-relative body-color">
					<span class="h3 d-block m-0">{$tsStats.stats_miembros}</span> miembros
				</a>
			</div>
			<div class="text-center text-uppercase small py-3 position-relative">
				{uicon name="document-stack" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
				<span class="z-2 fw-bold position-relative body-color">
					<span class="h3 d-block m-0">{$tsStats.stats_posts}</span> posts
				</span>
			</div>
		</div>
	</div>
	<div class="up-card--footer">
		<span>Actualizado: {$tsStats.stats_time|hace:true}</span>
	</div>
</section>