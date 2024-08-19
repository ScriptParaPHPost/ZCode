{if ($tsPage == 'home' || $tsPage == 'portal') && $tsNews}
	<div id="mensaje-top" class="overflow-hidden shadow-sm rounded mb-3">
    	<div id="top_news" class="msgtxt">
        	{foreach from=$tsNews key=i item=news}
        		<div class="news--item text-center fw-normal" id="new_{$i+1}" data-news-type="{$news.not_type}">{$news.not_body} <span class="countdown">6s</span></div>
        	{/foreach}
    	</div>
	</div>
{/if}