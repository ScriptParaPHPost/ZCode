{if $tsGeneral.fotos_total > 0}
	<section class="up-card">
		{include "CardHeader.tpl" iconName="picture" label="&Uacute;ltimas fotos"}
		<div class="up-card--body">
			<div id="perfil-foto-bar" class="gap-3 p-2">
				{foreach from=$tsGeneral.fotos item=f key=i}
					{if $f.foto_id}
						<div class="foto rounded mb-3">
							<a href="{$tsConfig.url}/fotos/{$tsInfo.nick}/{$f.foto_id}/{$f.f_title|seo}.html" title="{$f.f_title}">
								<img class="image rounded w-100 h-100 object-fit-cover d-block" src="{$tsConfig.assets}/images/favicon/logo-128.webp" data-src="{$f.f_url}" />
							</a>
						</div>
					{/if}
				{/foreach}
			</div>
		</div>
	</section>
{/if}