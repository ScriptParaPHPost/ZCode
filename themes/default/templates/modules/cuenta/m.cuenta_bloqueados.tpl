<div class="content-tabs bloqueados">
	<h4>Usuarios bloqueados</h4>
	<fieldset>
		{if $tsBlocks}
			<div class="bloqueadosList ">
				{foreach from=$tsBlocks item=b}
					<div class="rounded p-3 border mb-3 d-flex justify-content-between align-items-center">
						<a class="fw-semibold text-decoration-none" href="{$tsConfig.url}/perfil/{$b.user_name}">{$b.user_name|verificado}</a>
						<span role="button" title="Desbloquear Usuario" onclick="bloquear('{$b.b_auser}', false, 'mis_bloqueados')" class="desbloqueadosU btn btn-sm bloquear_usuario_{$b.b_auser}">Desbloquear</span>
					</div>
				{/foreach}
			</div>
		{else}
			<div class="empty">No hay usuarios bloqueados</div>
		{/if}
	</fieldset>
</div>