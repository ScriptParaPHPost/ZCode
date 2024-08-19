<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="funnel"}</div>
		<div class="up-header--title">
			<span>Filtrar</span>
		</div>
	</div>
	<div class="up-card--body">
		<div class="upform-group mt-3">
			<label class="upform-label" for="categorias">Categor&iacute;a:</label>
			<div class="drop-select z-3" id="drop-select-cats">
				<button type="button" class="drop-select--toggle">Selecciona una categoría</button>
				<div class="drop-select--menu">
					<div class="drop-select--item" onclick="location.href='{$tsConfig.url}/top/{$tsAction}/?fecha={$tsFecha}'">
						<img class="avatar avatar-2" src="{$tsConfig.assets}/icons/flags/xx.svg" alt="xx">
						<span>Selecciona una categoría</span>
					</div>
					{foreach from=$tsConfig.categorias item=c}
						<div class="drop-select--item" onclick="location.href='{$tsConfig.url}/top/{$tsAction}/?fecha={$tsFecha}&cat={$c.cid}'">
							<img class="avatar avatar-2" src="{$c.c_img}" alt="{$c.c_nombre}">
							<span>{$c.c_nombre}</span>
						</div>
					{/foreach}
				</div>
			</div>
		</div>

		<h4 class="fs-6">Per&iacute;odo</h4>
		<div class="items">
			<a class="d-block py-2 px-3 text-decoration-none border rounded mb-2{if $tsFecha == 2} fw-semibold translucent-bg active{/if}" href="{$tsConfig.url}/top/{$tsAction}/?fecha=2&cat={$tsCat}&sub={$tsSub}">Ayer</a>
			<a class="d-block py-2 px-3 text-decoration-none border rounded mb-2{if $tsFecha == 1} fw-semibold translucent-bg active{/if}" href="{$tsConfig.url}/top/{$tsAction}/?fecha=1&cat={$tsCat}&sub={$tsSub}">Hoy</a>
			<a class="d-block py-2 px-3 text-decoration-none border rounded mb-2{if $tsFecha == 3} fw-semibold translucent-bg active{/if}" href="{$tsConfig.url}/top/{$tsAction}/?fecha=3&cat={$tsCat}&sub={$tsSub}">&Uacute;ltimos 7 d&iacute;as</a>
			<a class="d-block py-2 px-3 text-decoration-none border rounded mb-2{if $tsFecha == 4} fw-semibold translucent-bg active{/if}" href="{$tsConfig.url}/top/{$tsAction}/?fecha=4&cat={$tsCat}&sub={$tsSub}">Del mes</a>
			<a class="d-block py-2 px-3 text-decoration-none border rounded mb-2{if $tsFecha == 5} fw-semibold translucent-bg active{/if}" href="{$tsConfig.url}/top/{$tsAction}/?fecha=5&cat={$tsCat}&sub={$tsSub}">Todos los tiempos</a>
		</div>
	</div>
</section>