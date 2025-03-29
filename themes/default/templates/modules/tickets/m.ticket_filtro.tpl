<section class="lastPosts up-card">
	{include "CardHeader.tpl" iconName="ticket" label="Filtros"}
	<div class="up-card--body pt-1">
		<a href="{$tsConfig.url}/tickets/" class="text-decoration-none mb-2 d-flex justify-content-start align-items-center column-gap-2 p-2 rounded{if $tsFilter == ''} fw-bold{/if}">{uicon name="document_list"} Todos</a>
		{foreach $tsTicketFilter item=arr}
			<a href="{$tsConfig.url}/tickets/{$arr.status_slug}/" class="text-decoration-none mb-2 d-flex justify-content-start align-items-center column-gap-2 p-2 rounded{if $tsFilter == $arr.status_slug} fw-bold{/if}">{uicon name=$arr.status_icon} {$arr.status_title}</a>
		{/foreach}
	</div>
</section>