<section class="lastPosts up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="browser"}
		</div>
		<div class="up-header--title">
			<span>&Uacute;ltimos posts en {$tsConfig.titulo}</span>
		</div>
	</div>
	<div class="up-card--body p-2">
		{foreach from=$tsPosts item=p}
		 	{include "m.home-post-item.tpl"}
		 {foreachelse}
		 	<div class="empty">No hay posts aqu&iacute;</div>
		{/foreach}
	</div>
	{$tsPages.item}
</section>