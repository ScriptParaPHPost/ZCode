<div class="upform-group">
	<label class="upform-label" for="razon">Raz&oacute;n para borrar este post:</label>
	<div class="upform-group-input">
		<select class="upform-select" name="razon" id="razon" onchange="if(parseInt($(this).val()) === 13) $('#n13').slideDown();">
			{foreach from=$tsDenuncias item=denuncia key=i}
				{if $denuncia}<option value="{$i}">{$denuncia}</option>{/if}
			{/foreach}
		</select>
	</div>
</div>

<div class="upform-group" id="n13" style="display: none;">
	<label class="upform-label" for="razon_desc">Aclaraci&oacute;n:</label>
	<div class="upform-group-input upform-icon">
		<div class="upform-input-icon">{uicon name="create"}</div>
		<input class="upform-input" type="text" name="razon_desc" id="razon_desc" maxlength="150" size="35">
	</div>
	<small class="fst-italic">En el caso de ser Re-post se debe indicar el link del post original.</small>
</div>

<div class="upform-check mb-0">
	<label>
		<input type="checkbox" name="send_b" id="send_b" value="1">
		<span class="upform-check-icon"></span>
		<span>Enviar al borrador del usuario...</span>
	</label>
</div>