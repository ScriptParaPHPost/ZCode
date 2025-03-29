<div class="p-1">
	{foreach $tsTicketOpen item=ticket}
		<div class="border rounded shadow-sm mb-2 px-2 py-1" data-status="{$ticket.ticket_slug}">
			<strong>{$ticket.ticket_title}</strong>
			<small class="d-block"><em><span class="ticket {$ticket.ticket_slug}">{$ticket.status_title}</span> - por: {$ticket.user_name}</em></small>
		</div>
	{/foreach}
</div>