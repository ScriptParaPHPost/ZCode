<div class="content-tabs bloqueados">
	<fieldset>
		<div class="field">
			{if $tsBlocks}
				<ul class="bloqueadosList">
					{foreach from=$tsBlocks item=b}
						<li>
							<a href="{$tsConfig.url}/perfil/{$b.user_name}">{$b.user_name}</a>
							<span>
								<span role="button" title="Desbloquear Usuario" onclick="bloquear('{$b.b_auser}', false, 'mis_bloqueados')" class="desbloqueadosU bloquear_usuario_{$b.b_auser}">Desbloquear</span>
							</span>
						</li>
					{/foreach}
				</ul>
			{else}
				<div class="emptyData">No hay usuarios bloqueados</div>
			{/if}
		</div>
	</fieldset>
	<div class="clearfix"></div>
</div>