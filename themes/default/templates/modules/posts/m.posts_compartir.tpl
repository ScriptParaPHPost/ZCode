<div class="compartir d-flex justify-content-center align-items-center gap-3 py-3" data-url="{$tsPost.post_url}" data-title="{$tsPost.post_title|seo}">

	{if $tsUser->is_member}
		<div class="compartir--item ps-3 rounded shadow d-grid justify-content-start align-items-center position-relative" data-social="web" data-count="true">
			{uicon name="retweet" size="2rem"}
			<span class="count fw-semibold d-block text-center">{$tsPost.post_shared}</span>
		</div>
	{/if}

	<div class="compartir--item rounded shadow d-flex justify-content-center align-items-center" data-social="whatsapp">
		{uicon name="whatsapp" size="2rem" folder="social"}
	</div>

	<div class="compartir--item rounded shadow d-flex justify-content-center align-items-center" data-social="telegram">
		{uicon name="telegram" size="2rem" folder="social"}
	</div>

	<div class="compartir--item rounded shadow d-flex justify-content-center align-items-center" data-social="twitter">
		{uicon name="twitter" size="2rem" folder="social"}
	</div>

	<div class="compartir--item rounded shadow d-flex justify-content-center align-items-center" data-social="facebook">
		{uicon name="facebook" size="2rem" folder="social"}
	</div>
</div>