<div class="empty" id="AFStatus">
	<span>Ingresa los datos de tu web para afiliarte.</span>
</div>
<form name="AFormInputs">

	<div class="upform-group">
		<label class="upform-label" for="atitle">T&iacute;tulo</label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">{uicon name="pen"}</div>
			<input class="upform-input required" type="text" name="atitle" id="atitle" required>
		</div>
		<small class="upform-status help"></small>
	</div>
	
	<div class="upform-group">
		<label class="upform-label" for="aurl">Direcci&oacute;n</label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">{uicon name="link"}</div>
			<input class="upform-input required" type="url" name="aurl" id="aurl" pattern="https://.*" placeholder="Direcci&oacute;n" required>
		</div>
		<small class="upform-status help"></small>
	</div>
	
	<div class="upform-group">
		<label class="upform-label" for="aimg">Banner <small>(216x42px)</small></label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">{uicon name="picture"}</div>
			<input class="upform-input required" type="url" name="aimg" id="aimg" pattern="https://.*" placeholder="Banner">
		</div>
	</div>

	<div class="upform-group">
		<label class="upform-label" for="atxt">Descripci&oacute;n</label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">{uicon name="create"}</div>
			<textarea name="atxt" id="atxt" class="upform-textarea" rows="10">{$tsPerfil.user_firma}</textarea>
		</div>
		<small class="upform-status">(Acepta BBCode) Max. 300 car.</small>
	</div>

</form>