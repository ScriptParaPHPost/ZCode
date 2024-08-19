{if $tsMensajes.data}
	<div id="mpList">
		{foreach from=$tsMensajes.data item=mp}
			<div class="border p-2 shadow mb-3 rounded position-relative{if $mp.mp_read_to == 0} unread{/if}" id="mp_{$mp.mp_id}">
				<a href="{$tsConfig.url}/mensajes/leer/{$mp.mp_id}" class="text-decoration-none d-grid column-gap-2" style="grid-template-columns:4.5rem 1fr;">
					<img loading="lazy" src="{$mp.avatar}" class="placeholder placeholder-grow avatar avatar-8 rounded" />
					<div class="mp_desc">
						<div class="autor d-flex justify-content-start align-items-center column-gap-2">
							<strong>{$mp.user_name}</strong> -
							<time class="mp_time small fst-italic">{$mp.mp_date|hace:true}</time>
						</div>
						<div class="subject">{$mp.mp_subject}</div>
						<div class="preview d-flex justify-content-start align-items-center column-gap-2">{if $mp.mp_type == 1}{uicon name="forward"} {/if}<span>{$mp.mp_preview}</span></div>
					</div>
				</a>
				<div class="position-absolute" style="top: 1rem;right: 1rem;">
					<span role="button" class="read" title="Marcar como le&iacute;do" onclick="mensaje.marcar('{$mp.mp_id}:{$mp.mp_type}', 0, 1, this); return false;"{if $mp.mp_read_to == 1} style="display:none"{/if}>{uicon name="checkbox-checked"}</span>
					<span role="button" class="unread" title="Marcar como no le&iacute;do" onclick="mensaje.marcar('{$mp.mp_id}:{$mp.mp_type}', 1, 1, this); return false;"{if $mp.mp_read_to == 0} style="display:none"{/if}>{uicon name="checkbox-empty"}</span>
					<span role="button" title="Eliminar" onclick="mensaje.eliminar('{$mp.mp_id}:{$mp.mp_type}',1); return false;">{uicon name="trash-alt"}</span>
				</div>
			</div>
		{/foreach}
	</div>
{else}
	<div class="empty">No hay mensajes</div>
{/if}
<div class="mpFooter d-flex justify-content-between align-items-center rounded p-2 translucent-bg">
	<div class="actions">
		{if $tsAction == ''}
			<strong>Ver: </strong>
			<a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/mensajes/{if $tsQT == ''}?qt=unread{/if}">{if $tsQT == ''}No le&iacute;dos{else}Todos los mensajes{/if}</a>
		{/if}
	</div>
	<div class="paginador d-flex justify-content-end align-items-center column-gap-2">
		{if $tsMensajes.pages.prev != 0}
			<a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/mensajes/{if $tsAction}{$tsAction}/{/if}?page={$tsMensajes.pages.prev}{if $tsQT != ''}&qt=unread{/if}">&laquo; Anterior</a>
		{/if}
		{if $tsMensajes.pages.prev != 0 || $tsMensajes.pages.next != 0}<span>|</span>{/if}
		{if $tsMensajes.pages.next != 0}
			<a class="text-decoration-none fw-semibold" href="{$tsConfig.url}/mensajes/{if $tsAction}{$tsAction}/{/if}?page={$tsMensajes.pages.next}{if $tsQT != ''}&qt=unread{/if}">Siguiente &raquo;</a>
		{/if}
	</div>
</div>