{foreach from=$tsTopUsers key=i item=u}
	{include "TopItemHome.tpl" key=$i user=$u.user_name itemprop="url" itemtype="Person" normal=true puntos=$u.total}
{foreachelse}
	<div class="empty">No hay usuarios</div>
{/foreach}