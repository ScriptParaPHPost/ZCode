{foreach from=$tsTopPosts key=i item=p}
	{include "TopItemHome.tpl" key=$i href=$p.post_url block=true truncate=true label=$p.post_title rel="internal" puntos=$p.post_puntos}
{foreachelse}
	<div class="empty">No hay posts</div>
{/foreach}