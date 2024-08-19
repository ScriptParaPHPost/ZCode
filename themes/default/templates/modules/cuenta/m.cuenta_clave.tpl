<div class="content-tabs cambiar-clave">
	<fieldset>
		<div class="upform-group">
			<label class="upform-label" for="passwd">Contrase&ntilde;a actual</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">{uicon name="lock"}</div>
				<input class="upform-input" type="password" name="passwd" id="passwd" maxlength="32" autocomplete="off">
			</div>
		</div>
		<div class="upform-group">
			<label class="upform-label" for="new_passwd">Contrase&ntilde;a nueva</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">{uicon name="lock"}</div>
				<input class="upform-input" type="password" name="new_passwd" id="new_passwd" maxlength="32" autocomplete="off">
			</div>
		</div>
		<div class="upform-group">
			<label class="upform-label" for="confirm_passwd">Repetir Contrase&ntilde;a</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">{uicon name="lock"}</div>
				<input class="upform-input" type="password" name="confirm_passwd" id="confirm_passwd" maxlength="32" autocomplete="off">
			</div>
		</div>

		<div class="upform-group">
			<span role="button" id="generar" class="btn" onclick="cuenta.generarContrasena()">Generar contraseña segura</span>
		</div>

	</fieldset>
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
	</div>
</div>