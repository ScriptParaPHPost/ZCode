{include "main_header.tpl"}
{$tsInstall}
   
<div class="row">
	{if $tsCatData}
		<div class="col-12">
			<div class="p-3 d-flex justify-content-start align-items-center gap-3">
				<div class="category-image">
					<img src="{$tsCatData.c_img}" alt="{$tsCatData.c_nombre}" class="avatar avatar-5" loading="lazy">
				</div>
				<div class="category-title">
					<span class="h3 d-block m-0 text-uppercase fw-bold">{$tsCatData.c_nombre}</span>
					<small>{$tsCatData.c_descripcion}</small>
				</div>
			</div>
		</div>
	{/if}
	{if $tsConfig.c_allow_foro && empty($tsCatData)}
		<div class="col-12 col-lg-8">
			{include "foro/m.foro_group.tpl"}
		</div>
		<div class="col-12 col-lg-4">
			{include "m.home_search.tpl"}
			{include "m.home_stats.tpl"}
			{include "m.home_last_comments.tpl"}
			{include "m.home_top_posts.tpl"}
			{include "m.home_top_users.tpl"}
		</div>
	{else}
		<div class="col-12 col-md-12 col-lg-5">
			{include "m.home_last_posts.tpl"}
		</div>
		<div class="col-12 col-md-6 col-lg-4">
			{include "m.home_stats.tpl"}
			{include "m.home_posts_staff.tpl"}
			{include "m.home_top_posts.tpl"}
			{include "m.home_top_users.tpl"}
		</div>
		<div class="col-12 col-md-6 col-lg-3">
			{include "m.home_search.tpl"}
			{include "m.home_last_comments.tpl"}
			{include "m.home_afiliados.tpl"}
			<br class="spacer"/>
			{include "m.global_ads_160.tpl"}
		</div>
	{/if}
</div>

{include "main_footer.tpl"}