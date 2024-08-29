<div class="content-tabs cambiar-clave">
	<fieldset>
		<div class="row">
			<div class="col-12 col-lg-7">
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
			</div>
			<div class="col-12 col-lg-5">
				<div class="mb-3">
					<h5 class="d-flex justify-content-between align-items-center">2FA: {if $tsG2FA === false}Desa{else}A{/if}ctivado{if $tsG2FA === false} <small id="countdown">30s</small>{/if}</h5>
					{if $tsG2FA === false}
						<div id="regenerate">
							{include "p.cuenta.regenerate.tpl"}
						</div>
					{else}
						<div class="text-center">
							{uicon name="lightning" size="4rem"}
						</div>
						<div class="row">
							<div class="col-12 col-lg-6">
								<span role="button" class="btn btn-sm d-block text-center remove_2fa">Desactivar 2FA</span>
							</div>
							<div class="col-12 col-lg-6">
								<span role="button" class="btn btn-sm d-block text-center regenerate_token">Generar Token</span>
							</div>
						</div>
						
					{/if}
				</div>
			</div>
		</div>
	</fieldset>
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
	</div>
</div>