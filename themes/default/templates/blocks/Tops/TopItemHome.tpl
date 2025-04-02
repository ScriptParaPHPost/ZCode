<div class="filterShow-item d-flex entry-animation">
	<div class="filterShow-item--position text-center fw-bold flex-grow-0">{if $key+1 < 10}0{/if}{$key+1}</div>
	<div class="filterShow-item--title text-truncate flex-grow-1">
		{if isset($href)}
			{include "LinkTitle.tpl" href=$href block=$block truncate=$truncate label=$label rel=$rel class="text-decoration-none w-100 d-block"}
		{else}
			{include "LinkAuthor.tpl" user=$user itemprop=$itemprop itemtype=$itemtype normal=$normal}
		{/if}
	</div>
	<div class="filterShow-item--number text-center flex-grow-0">{$puntos|human}</div>
</div>