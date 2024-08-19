<div class="modalForm">
{if $tsDo == 'aviso'}
	 <div class="m-col1">Para:</div>
	 <div class="m-col2"><strong>{$tsUsername}</strong></div>
	 <br style="clear:both"/>
	 <div class="m-col1">Tipo:</div>
	 <div class="m-col2"><select name="mod_type" id="mod_type"><option value="0">Informaci&oacute;n</option><option value="1">Alerta</option><option value="2">Staff Message</option><option value="3">Prohibici&oacute;n</option><option value="4">Gif Message</option></select></div>
	 <br style="clear:both"/>
	 <div class="m-col1">Asunto:</div>
	 <div class="m-col2"><input type="text" name="mod_subject" id="mod_subject" size="50" tabindex="0" maxlength="24" value=""/></div>
	 <br /><br style="clear:both"/>
	 <div class="m-col1">Mensaje:</div>
	 <div class="m-col2"><textarea style="height:100px; width:350px" name="mod_body" id="mod_body" rows="10" tabindex="0"></textarea></div>
	 <br style="clear:both"/>
{elseif $tsDo == 'ban'}
	<span class="d-block h6">Suspender a: <strong>{$tsUsername}</strong></span>
	
	<div class="upform-group">
		<label class="upform-label" for="mod_time">Tiempo:</label>
		<div class="upform-group-input">
			<select class="upform-select" name="mod_time" id="mod_time" onchange="ban_time(this.value);">
				<option value="0">Indefinido</option>
				<option value="1">Permanente</option>
				<option value="2">Horas</option
				><option value="3">D&iacute;as</option>
			</select>
		</div>
	</div>	
	
	<div class="upform-group" id="ban_time" style="display:none">
		<label class="upform-label" for="mod_time">Cuantos:</label>
		<div class="upform-group-input">
			<input class="upform-input" type="text" name="mod_cant" id="mod_cant" placeholder="7">
		</div>
	</div>

	<div class="upform-group">
		<label class="upform-label" for="mod_causa">Causa:</label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">
				{uicon name="create"}
			</div>
			<textarea name="mod_causa" id="mod_causa" class="upform-textarea"></textarea>
		</div>
	</div>
	<script>
		function ban_time(option) {
			let opt23 = (parseInt(option) === 2 || parseInt(option) === 3);
			$('#ban_time')[(opt23 ? 'show' : 'hide')]();
			if(opt23) {
				let ao = (parseInt(option) === 2) ? 'a' : 'o';
				$('#ban_time label[for="mod_time"]').text('Cuant' + ao + 's');
			}
		}
	</script>
{/if}
</div>