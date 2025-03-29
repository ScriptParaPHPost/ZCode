<section class="lastPosts up-card">
	{include "CardHeader.tpl" iconName="ticket" label=$tsTicketTitle}
	<div class="up-card--body mt-2">
		<table>
			<thead>
				<th>Titulo</th>
				<th><a class="main-color text-decoration-none" title="Ordenar por estado ascendente" href="{$tsConfig.url}/tickets/?o=status&m=a&s={$tsPageNow}"><</a> Estado <a class="main-color text-decoration-none" title="Ordenar por estado descendente" href="{$tsConfig.url}/tickets/?o=status&m=d&s={$tsPageNow}">></a></th>
				<th><a class="main-color text-decoration-none" title="Ordenar por estado ascendente" href="{$tsConfig.url}/tickets/?o=type&m=a&s={$tsPageNow}"><</a> Tipo <a class="main-color text-decoration-none" title="Ordenar por estado descendente" href="{$tsConfig.url}/tickets/?o=type&m=d&s={$tsPageNow}">></a></th>
				<th>Usuario</th>
				<th>Creado</th>
				<th>Actualizado</th>
				<th>Acción</th>
			</thead>
			<tbody id="tbody">
				{include "t.php_files/p.ticket.list.tpl"}
			</tbody>
		</table>
		{$tsTicketPages}
	</div>
</section>