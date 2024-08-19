{include "main_header.tpl"}

	<div class="row">
		{if $tsAction == ''}
			<div class="col-lg-8 col-12">
				{include "m.monitor_content.tpl"}
			</div>
			<div class="col-lg-4 col-12">
				{include "m.monitor_sidebar.tpl"}
			</div>
		{else}
			<div class="col-12">
				{include "m.monitor_menu.tpl"}
				{include "m.monitor_listado.tpl"}
			</div>
		{/if}
	</div>
	
{include "main_footer.tpl"}