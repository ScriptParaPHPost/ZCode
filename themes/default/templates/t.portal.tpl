{include "main_header.tpl"}

<div class="row">
	<div class="col-12 col-lg-2">
		{include "m.portal_tabs.tpl"}
	</div>
	<div class="col-12 col-lg-7">
		<div id="portal">
			<div id="portal_content">
				{include "m.portal_noticias.tpl"}
				{include "m.portal_activity.tpl"}
				{include "m.portal_posts.tpl"}
				{include "m.portal_posts_favoritos.tpl"}
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-3">
		{include "home/m.home_stats.tpl"}
		{include "m.portal_posts_visitados.tpl"}
		{include "m.portal_afiliados.tpl"}
	</div>
</div>
					 
{include "main_footer.tpl"}