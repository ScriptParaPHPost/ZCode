<header>

	<div class="brand d-block d-lg-flex justify-content-between align-items-center">
		<h1 class="title-site p-0 m-0 fw-bolder">
			<a title="{$tsConfig.titulo}" href="{$tsConfig.url}" class="text-decoration-none text-uppercase d-block text-center text-lg-end">
				<span>{$tsConfig.titulo}</span>
				<small class="d-block fw-bold mt-1">{$tsConfig.slogan}</small>
			</a>
		</h1>
		
		<div id="banner" class="d-none d-lg-block overflow-hidden rounded ad ad468">
			{if $tsPage == 'posts' && $tsPost.post_id}
				{include "home/m.home_search.tpl"}
			{else}
				{include "m.global_ads_468.tpl"}
			{/if}
		</div>
	</div>

	{include "up_navbar.tpl"}
</header>