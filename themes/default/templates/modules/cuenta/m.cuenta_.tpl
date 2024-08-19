<div class="content-tabs cuenta">
	<fieldset>

		<div class="upform-group">
			<label class="upform-label" for="email">E-Mail</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="mail"}
				</div>
				<input class="upform-input" type="text" name="email" id="email" value="{$tsUser->info.user_email}">
			</div>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="pais">Pa&iacute;s</label>
			<div class="upform-group-input">
				<select onchange="cuenta.paisProvicias()" class="upform-select" name="pais" id="pais">
					<option value="">Pa&iacute;s</option>
					{foreach from=$tsPaises key=code item=pais}
						<option value="{$code}"{if $code == $tsPerfil.user_pais} selected{/if}>{$pais}</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="estados">Estado/Provincia:</label>
			<div class="upform-group-input">
				<select name="estado" id="estados" class="upform-select">
					{foreach from=$tsEstados key=code item=estado}
						<option value="{$code+1}"{if $code+1 == $tsPerfil.user_estado} selected{/if}>{$estado}</option>
					{/foreach}
				</select>
			</div>
		</div>


		<div class="up-form--cuenta d-flex justify-content-start align-items-center gap-3">
			<label class="position-relative rounded">
				<input type="radio" name="sexo" value="none"{if $tsPerfil.user_sexo == 'none'} checked{/if}>
				<span class="up-cuenta--icon d-flex justify-content-start align-items-center rounded gap-2">
					{uicon name="question-circle"}
					<span>No decir</span>
				</span>
			</label>
			<label class="position-relative rounded">
				<input type="radio" name="sexo" value="female"{if $tsPerfil.user_sexo == 'female'} checked{/if}>
				<span class="up-cuenta--icon d-flex justify-content-start align-items-center rounded gap-2">
					{uicon name="user"}
					<span>Femenino</span>
				</span>
			</label>
			<label class="position-relative rounded">
				<input type="radio" name="sexo" value="male"{if $tsPerfil.user_sexo == 'male'} checked{/if}>
				<span class="up-cuenta--icon d-flex justify-content-start align-items-center rounded gap-2">
					{uicon name="user-male"}
					<span>Masculino</span>
				</span>
			</label>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="nacimiento">Nacimiento:</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="calendar-date"}
				</div>
				<input class="upform-input" name="nacimiento" type="date" id="nacimiento" min="{$tsMaxY}-12-31" max="{$tsEndY}-12-31" value="{$tsPerfil.nacimiento}" /> 
			</div>
		</div>


	{if $tsConfig.c_allow_firma}
		<div class="upform-group">
			<label class="upform-label" for="firma">Firma:</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="create"}
				</div>
				<textarea name="firma" id="firma" class="upform-textarea">{$tsPerfil.user_firma}</textarea>
			</div>
			<small class="upform-status">(Acepta BBCode) Max. 300 car.</small>
		</div>
	{/if}
	
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
	</div>
	<div class="clearfix"></div>
</div>