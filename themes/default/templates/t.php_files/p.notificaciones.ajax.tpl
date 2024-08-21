{if $tsData}
	{foreach from=$tsData item=noti}
  		<li class="up-droplist--item py-1 px-2 mb-2 position-relative rounded translucent-bg overflow-hidden d-flex justify-content-start align-items-center column-gap-2{if $noti.unread > 0} unread{/if}" style="font-size: 0.875rem;">
  			<span class="iconify ma_{$noti.style}"></span>
  			<span class="d-block">
  				{if $noti.total == 1}<a href="{$tsConfig.url}/perfil/{$noti.user}" class="obj text-decoration-none fw-bold" title="{$noti.user}">{$noti.user|verificado}</a>{/if} {$noti.text} <a title="{$noti.ltit}" class="obj text-decoration-none fw-bold" href="{$noti.link}">{$noti.ltext}</a>
  			</span>
  		</li>
   {/foreach}
{else}
	<li class="empty">
		<span>No hay notificaciones</span>
	</li>
{/if}