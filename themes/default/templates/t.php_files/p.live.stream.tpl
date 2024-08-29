<div id="live-stream" notifications="{$tsStream.total}" messages="{$tsMensajes.total}">
	{foreach from=$tsStream.data item=noti key=id}
	 	<div class="UIBeeper_Full" id="beep_{$id}">
			<div class="UIBeep UIBeep--notification">
				<a href="{$noti.link}" class="UIBeep_NonIntentional">
					<div class="UIBeep_Icon action"><span class="monac_icons ma_{$noti.style}"></span></div>
					<span class="beeper_x" bid="{$id}">&times;</span>
					<div class="UIBeep_Title">
						{if $noti.total == 1}<span class="blueName">{$noti.user}</span> {/if}{$noti.text} <span class="blueName">{$noti.ltext}</span>
					</div>
				</a>
		  	</div>
	 	</div>
	 	<script>
	 		showNotification(`{if $noti.total == 1}{$noti.user}{else}{$tsConfig.titulo}{/if}`, `{$noti.text} {$noti.ltext}`, ZCodeApp.images.assets + `/favicon/logo-128.webp`, '{$noti.link}');
	 	</script>
	{/foreach}
	{foreach from=$tsMensajes.data item=mp key=id}
	 	<div class="UIBeeper_Full" id="beep_m{$id}">
			<div class="UIBeep UIBeep--message">
				<a href="{$tsConfig.url}/mensajes/leer/{$mp.mp_id}" class="UIBeep_NonIntentional">
					<div class="UIBeep_Icon"><img src="{$tsConfig.url}/files/avatar/{$mp.mp_from}_50.jpg"/></div>
					<span class="beeper_x" bid="m{$id}">&times;</span>
					<div class="UIBeep_Title">
						<strong>Nuevo mensaje</strong>                 
						<span class="blueName">{$mp.user_name|verificado}</span> {$mp.mp_preview}
					</div>
				</a>
			</div>
	 	</div>
	 	<script>
	 		showNotification(`Nuevo mensaje`, `{$mp.mp_preview}`, ZCodeApp.images.assets + `/favicon/logo-128.webp`, '{$tsConfig.url}/mensajes/leer/{$mp.mp_id}');
	 	</script>
	{/foreach}
</div>