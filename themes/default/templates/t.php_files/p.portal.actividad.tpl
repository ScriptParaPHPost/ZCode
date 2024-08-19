{foreach from=$tsActividad.data item=itemActividad key=id}
	{if $itemActividad.data}
		<div id="more-{$id}" nid="last-activity-date-{$id}" class="date-sep" active="false">
			<h3 style="display:none" class="fs-4">{$itemActividad.title}</h3>
			{foreach from=$itemActividad.data item=actividad}
				<div class="sep border rounded p-2 d-flex justify-content-between align-items-center mb-2">
					<div class="ac_content d-flex justify-content-start align-items-start column-gap-2">
						<img src="{$actividad.uid}" loading="lazy" width="20" height="20" class="avatar avatar-2" />
						{if $actividad.style != ''}<span class="monac_icons ma_{$actividad.style}"></span>{/if}
						<span>
							<a href="{$tsConfig.url}/perfil/{$actividad.user}" class="text-decoration-none fw-semibold">{$actividad.user}</a> {$actividad.text} <a href="{$actividad.link}" class="text-decoration-none fw-semibold">{$actividad.ltext}</a>
						</span>
					</div>
					<span class="time small">{$actividad.date|hace:true}</span>
				</div>
			{/foreach}
		</div>
	{/if}
{/foreach}
{if $tsActividad.total > 0 && $tsActividad.total >= 25}
	<div class="text-center">
		<h3 id="last-activity-view-more" class="text-uppercase h5 m-0" onclick="actividad.cargar({$tsUserID},'more', 0); return false;" role="button">Ver m&aacute;s actividad</h3>
	</div>	
{/if}
<div id="total_acts" val="{$tsActividad.total}"></div>
<script>
	const portal_actividad = $('#total_acts').val();
</script>