<section class="lastPosts up-card">
	{include "CardHeader.tpl" iconName="browser" label="&Uacute;ltimos posts"}
	<div class="up-card--body p-2">
		{foreach from=$tsPosts item=p}
		 	{include "m.home-post-item.tpl"}
		 {foreachelse}
		 	<div class="empty">No hay posts aqu&iacute;</div>
		{/foreach}
	</div>
	{$tsPages.item}
</section>