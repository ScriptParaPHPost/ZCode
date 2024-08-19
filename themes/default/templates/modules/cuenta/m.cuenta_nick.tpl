<div class="content-tabs cambiar-nick">
	<fieldset>
		{if $tsUser->info.user_name_changes > 0}
			<div class="empty">Hola {$tsUser->nick}, le recordamos que dispone de {$tsUser->info.user_name_changes} cambios este a&ntilde;o. Recuerde que si su cambio no es aprobado, no se le devolver&aacute; la disponibilidad de otro cambio.</div>

		  	<div class="upform-group">
				<label class="upform-label" for="new_nick">Nombre de usuario</label>
				<div class="upform-group-input upform-icon">
					<div class="upform-input-icon">
						{uicon name="user-male"}
					</div>
					<input class="upform-input" type="text" name="new_nick" id="new_nick" value="{$tsUser->nick}" maxlength="15">
				</div>
			</div>

		  	<div class="upform-group">
				<label class="upform-label" for="password">Contrase&ntilde;a actual</label>
				<div class="upform-group-input upform-icon">
					<div class="upform-input-icon">
						{uicon name="lock"}
					</div>
					<input class="upform-input" type="password" name="password" id="password" maxlength="32">
				</div>
			</div>

			<div class="upform-group">
				<label class="upform-label" for="pemail">Recibir respuesta en</label>
				<div class="upform-group-input upform-icon">
					<div class="upform-input-icon">
						{uicon name="mail"}
					</div>
					<input class="upform-input" type="text" name="pemail" id="pemail" value="{$tsUser->info.user_email}">
				</div>
			</div>

	 	</fieldset>
	 	<div class="buttons">
		  	<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
	 	</div>
	{else}
		<p>Hola {$tsUser->nick}, lamentamos informarle de su nula disponibilidad de cambios, contacte con la administraci&oacute;n o espere un a&ntilde;o.
	{/if}
	<div class="clearfix"></div>
</div>