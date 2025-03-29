{if $tsPostsStickys}
<section class="lastPosts up-card">
	{include "CardHeader.tpl" iconName="lightning" label="Posts importantes"}
	<div class="up-card--body p-2">
		{foreach from=$tsPostsStickys item=p}
			{include "m.home-post-item.tpl"}
		{/foreach}
	</div>
</section>
{/if}