<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="bell-ringing"}
		</div>
		<div class="up-header--title">
			<span>&Uacute;ltimas {$tsData.total} notificaciones</span>
		</div>
	</div>
	<div class="up-card--body">
		<div class="nots">
			{foreach from=$tsData.data item=noti}
				<div class="not--item d-flex justify-content-start align-items-center gap-2 p-2 m-2 rounded shadow-sm{if $noti.unread > 0} unread{/if}">
					{if $noti.user === 'Sistema'}
						<span class="d-block avatar avatar-5 overflow-hidden rounded shadow">
							<img loading="lazy" class="w-100 h-100 object-fit-cover" src="{$noti.avatar}"/>
						</span>
					{else}
						<a href="{$tsConfig.url}/perfil/{$noti.user}" class="d-block avatar avatar-5 overflow-hidden rounded shadow">
							<img loading="lazy" class="w-100 h-100 object-fit-cover" src="{$noti.avatar}"/>
						</a>
					{/if}
					<div class="notification-info">
						<div>
							{if $noti.total == 1}{/if}
							{if $noti.user === 'Sistema'}
								<span class="fw-semibold">{$noti.user}</span>
							{else}
								<a href="{$tsConfig.url}/perfil/{$noti.user}" class="fw-semibold text-decoration-none">{$noti.user}</a>
							{/if} - 
							
							<span title="{$noti.date|hace}" class="time small">{$noti.date|hace:true}</span>
						</div>
						<div class="action d-flex justify-content-start align-items-center gap-2 text-truncate">
							<span class="monac_icons ma_{$noti.style}"></span> 
							<span>{$noti.text} <a href="{$noti.link}" class="fw-semibold text-decoration-none">{$noti.ltext}</a></span>
						</div>
					</div>
				</div>
			{foreachelse}
				<div class="empty">No tienes notificaciones</div>
			{/foreach}
		</div>
	</div>
</section>