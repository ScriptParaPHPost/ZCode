{foreach from=$tsTopUsers key=i item=u}
	<div class="filterShow-item d-flex entry-animation">
		<div class="filterShow-item--position text-center fw-bold flex-grow-0">{if $i+1 < 10}0{/if}{$i+1}</div>
		<div class="filterShow-item--title text-truncate flex-grow-1">{include "LinkAuthor.tpl" user=$u.user_name itemprop="url" itemtype="Person" normal=true}</div>
		<div class="filterShow-item--number text-center flex-grow-0">{$u.total}</div>
	</div>
{foreachelse}
	<div class="empty">No hay usuarios</div>
{/foreach}