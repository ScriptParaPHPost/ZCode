<!-- hoas {$tsTicketList} -->
{foreach $tsTicketList item=ticket}
	<!-- {$ticket.ticket_id} -->
	<tr class="border rounded shadow-sm mb-2 px-2 py-1 text-center" data-status="{$ticket.ticket_id}">
		<td class="text-start"><a href="{$tsConfig.url}/tickets/ver-ticket/{$ticket.ticket_id}" class="text-decoration-none">{$ticket.ticket_title}</a></td>
		<td class="fw-bold">{$ticket.status_title}</td>
		<td>{$ticket.type_title}</td>
		<td>{include "LinkAuthor.tpl" user=$ticket.user_name itemprop="creator" itemtype="Person"}</td>
		<td>{$ticket.ticket_date|hace:true}</td>
		<td>{$ticket.ticket_update|hace:true}</td>
		<td>
			<div class="d-flex justify-content-center align-items-center column-gap-2">
				<span role="button" onclick="ticket.ver({$ticket.ticket_id})">{uicon name="eye"}</span>
				<span role="button" onclick="ticket.cambiar({$ticket.ticket_id})">{uicon name="reverse"}</span>
				<span role="button" onclick="ticket.eliminar({$ticket.ticket_id})">{uicon name="trash"}</span>
			</div>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="7">
			<div class="empty">No hay resultados</div>
		</td>
	</tr>
{/foreach}