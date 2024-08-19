{if $tsAutor.user_id == $tsUser->uid}
	<h4>Visitas desde...</h4>
	<div class="mb-3 d-grid" style="grid-template-columns: repeat(2, 1fr);">
		<div class="text-uppercase d-flex justify-content-start align-items-center column-gap-2 fw-semibold p-2">
			{uicon name="facebook" size="2rem" folder="social"}
			<span class="m-0 nData user_follow_count">{$tsPost.post_stats.facebook|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">facebook</span>
		</div>
		<div class="text-uppercase d-flex justify-content-start align-items-center column-gap-2 fw-semibold p-2">
			{uicon name="twitter" size="2rem" folder="social"}
			<span class="m-0 nData">{$tsPost.post_stats.twitter|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">twitter</span>
		</div>
		<div class="text-uppercase d-flex justify-content-start align-items-center column-gap-2 fw-semibold p-2">
			{uicon name="telegram" size="2rem" folder="social"}
			<span class="m-0 nData">{$tsPost.post_stats.telegram|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">telegram</span>
		</div>
		<div class="text-uppercase d-flex justify-content-start align-items-center column-gap-2 fw-semibold p-2">
			{uicon name="whatsapp" size="2rem" folder="social"}
			<span class="m-0 nData">{$tsPost.post_stats.whatsapp|human}</span>
			<span class="txtData" style="font-size: 0.875rem;">whatsapp</span>
		</div>			
	</div>
{/if}