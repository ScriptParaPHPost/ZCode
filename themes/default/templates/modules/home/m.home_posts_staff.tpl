{if $tsPostsStickys}
<section class="lastPosts up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="lightning"}
		</div>
		<div class="up-header--title">
			<span>Posts importantes en {$tsConfig.titulo}</span>
		</div>
	</div>
	<div class="up-card--body p-2">
		{foreach from=$tsPostsStickys item=p}
			{include "m.home-post-item.tpl"}
		{/foreach}
	</div>
</section>
{/if}