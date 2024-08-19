{foreach from=$tsTopPosts key=i item=p}
	<div class="filterShow-item d-flex entry-animation">
		<div class="filterShow-item--position text-center fw-bold flex-grow-0">{if $i+1 < 10}0{/if}{$i+1}</div>
		<div class="filterShow-item--title text-truncate flex-grow-1"><a href="{$p.post_url}" rel="internal" class="text-truncate text-decoration-none w-100 d-block" title="{$p.post_title}">{$p.post_title}</a></div>
		<div class="filterShow-item--number text-center flex-grow-0">{$p.post_puntos|human}</div>
	</div>
{foreachelse}
	<div class="empty">No hay posts</div>
{/foreach}