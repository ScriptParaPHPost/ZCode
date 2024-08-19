<div class="content-tabs privacidad">
	<fieldset>
		<h2 class="active">&iquest;Qui&eacute;n puede...</h2>
		<div class="upform-group">
			<label class="upform-label" for="muro">ver tu muro?</label>
			<div class="upform-group-input">
				<select class="upform-select" name="muro" id="muro">
					{foreach from=$tsPrivacidad item=option key=value}
						<option value="{$value}"{if $tsPerfil.p_configs.m == $value} selected{/if}>{$option}</option>
					{/foreach}
				</select>
			</div>
		</div>

		{$tsPerfil.p_configs.muro}
		<div class="upform-group">
			<label class="upform-label" for="muro_firm">firmar tu muro?</label>
			<div class="upform-group-input">
				<select class="upform-select" name="muro_firm" id="muro_firm">
					{foreach from=$tsPrivacidad item=option key=value}
						{if $i != 6}
							<option value="{$value}"{if $tsPerfil.p_configs.mf == $value} selected{/if}>{$option}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="last_hits">ver &uacute;ltimas visitas?</label>
			<div class="upform-group-input">
				<select class="upform-select" name="last_hits" id="last_hits">
					{foreach from=$tsPrivacidad item=option key=value}
						{if $i != 1 && $i != 2}
							<option value="{$value}"{if $tsPerfil.p_configs.hits == $value} selected{/if}>{$option}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</div>

		{if !$tsUser->is_admod}
			{if $tsPerfil.p_configs.rmp != 8}
				<div class="upform-group">
					<label class="upform-label" for="rec_mps">enviarte MPs?</label>
					<div class="upform-group-input">
						<select class="upform-select" name="rec_mps" id="rec_mps">
							{foreach from=$tsPrivacidad item=option key=value}
								{if $i != 6}
									<option value="{$value}"{if $tsPerfil.p_configs.rmp == $value} selected{/if}>{$option}</option>
								{/if}
							{/foreach}
						</select>
					</div>
				</div>
			{else}
				<div class="empty empty-danger">Algunas opciones de su privacidad han sido deshabilitadas, contacte con la administraci&oacute;n.</div>
			{/if}
		{/if}  

		<div class="upform-group">
			<label class="upform-label" for="outtime_type">Eliminar mi cuenta</label>
			<div class="upform-group-input">
				<select class="upform-select" name="outtime_type" id="outtime_type" onchange="cuenta.eliminar_cuenta(this);">
					<option value="0"{if $tsPerfil.user_outtime_type == 0} selected{/if}>Nunca</option>
					<option value="1"{if $tsPerfil.user_outtime_type == 1} selected{/if}>3 Meses</option>
					<option value="2"{if $tsPerfil.user_outtime_type == 2} selected{/if}>6 Meses</option>
					<option value="3"{if $tsPerfil.user_outtime_type == 3} selected{/if}>9 Meses</option>
					<option value="4"{if $tsPerfil.user_outtime_type == 4} selected{/if}>12 Meses</option>
				</select>
				<small>Si no estoy en línea al menos una vez durante este período, tu cuenta se eliminará junto con todos tus posts, mensajes, etc.</small>
			</div>
		</div>
	</fieldset>
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
		{if !$tsUser->is_admod}
			<input type="button" value="Desactivar Cuenta" onclick="desactivate(0)" class="btn btn-outline">
		{/if}
	</div> 
	<div class="clearfix"></div>
</div>