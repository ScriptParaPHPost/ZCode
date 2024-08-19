<div class="d-flex justify-content-start align-items-center column-gap-3 mb-3">
	{assign var="imageCount" value=$tsGeneral.fotos|count}
	{foreach from=$tsGeneral.fotos item=f key=i}
		{if $f.foto_id}
			<div style="width:calc(calc(100% - 3rem) / 6)">
				<div class="foto rounded shadow w-100" style="height:120px;">
					<a href="{$tsConfig.url}/fotos/{$tsInfo.nick}/{$f.foto_id}/{$f.f_title|seo}.html" title="{$f.f_title}">
						<img class="image rounded w-100 h-100 object-fit-cover d-block" src="{$tsConfig.assets}/images/favicon/logo-128.webp" data-src="{$f.f_url}" />
					</a>
				</div>
			</div>
		{/if}
	{/foreach}
	{section name=emptyImage loop=6-$imageCount}
		<div style="width:calc(calc(100% - 3rem) / 6)">
		  <div class="foto rounded shadow w-100" style="height:120px;">
				<!-- Este contenedor no tiene una imagen y se llenará con el fondo de color #CCC -->
		  </div>
		</div>
	{/section}
</div>