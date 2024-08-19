<div class="px-2">
	{if $tsAction == 'denuncia-post' || $tsAction == 'denuncia-foto' || $tsAction == 'denuncia-usuario'}
		<strong class="d-block h6">{if $tsAction == 'denuncia-usuario'}{$tsData.obj_user}{else}{$tsData.obj_title}{/if}</strong>
		{if $tsAction == 'denuncia-post'}
			<div class="upform-group">
				<strong class="upform-label" for="nick">Creado por:</strong>
				<div class="upform-group-input">
					<a href="{$tsConfig.url}/perfil/{$tsData.obj_user}" target="_blank" class="text-decoration-none fw-semibold">{$tsData.obj_user}</a>
				</div>
			</div>
		{/if}
		<div class="upform-group">
			<label class="upform-label" for="razon">Raz&oacute;n de la denuncia:</label>
			<div class="upform-group-input">
				<select class="upform-select" name="razon" id="razon">
					{foreach from=$tsDenuncias key=i item=denuncia}
						{if $denuncia}<option value="{$i}">{$denuncia}</option>{/if}
					{/foreach}
				</select>
			</div>
		</div>		
		<div class="upform-group mb-0">
			<label class="upform-label" for="extras">Aclaraci&oacute;n y comentarios:</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">{uicon name="create"}</div>
				<textarea name="extras" id="extras" class="upform-textarea"></textarea>
			</div>
			<small class="fst-italic">En el caso de ser Re-post se debe indicar el link del post original.</small>
		</div>
	{elseif $tsAction == 'denuncia-mensaje'}
		<div class="empty">Si reportas este mensaje ser&aacute; eliminado de tu bandeja. &iquest;Realmente quieres denunciar este mensaje como correo no deseado?</div>
		<input type="hidden" name="razon" value="spam" />
	{/if}
</div>