{if $tsMensajes.data}
	{foreach from=$tsMensajes.data item=mp}
		<li class="up-droplist--item p-0 px-2 mb-2 small position-relative rounded translucent-bg overflow-hidden d-block h-auto{if $mp.mp_read_to == 0 || $mp.mp_read_mon_to == 0} unread{/if}">
		 	<a class="d-flex justify-content-start align-items-center lh-base column-gap-2 py-1 fw-semibold text-decoration-none" href="{$tsConfig.url}/mensajes/leer/{$mp.mp_id}" title="{$mp.mp_subject}">
				<img src="{$mp.avatar}" class="avatar avatar-5 rounded" alt="{$mp.user_name}"/>
				<div class="content flex-grow-1">
					<div class="subject fw-semibold">{$mp.mp_subject}</div>
					<div class="preview d-block fst-italic">{$mp.mp_preview}</div>
					<div class="time small fst-italic d-flex justify-content-between align-items-center">
						<span class="autor">{$mp.user_name|verificado}</span> 
						<span>{$mp.mp_date|hace:true}</span>
					</div>
				</div>
		  	</a>
	 	</li>
	{/foreach}
{else}
	<li class="empty">
		<span>No tienes mensajes</span>
	</li>
{/if}