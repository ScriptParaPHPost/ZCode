1:
<form id="RegistroForm">
	<div class="mensajeAviso">
		<span>Obteniendo código de reCAPTCHA...</span>
	</div>
	<div class="form-line">
		{foreach $OAuth key=i item=social}
			<a class="btn-social btn-active btn-icon-only btn-{$i}" href="{$social}">
				<span class="btn-iconify btn-icon-{$i}"></span> Crear cuenta con {$i|ucfirst}
			</a>
		{/foreach}
	</div>
	<div class="form-line">
		<label for="nick">Ingresa tu usuario*</label>
		<input name="nick" type="text" id="nick" tabindex="1" placeholder="Ingrese un nombre de usuario &uacute;nico" autocomplete="off" /> 
		<div class="help"></div>
		<small>Puede contener {if $tsConfig.c_upperkey === '1'}<em>Mayúsculas</em>, {/if}<em>Minúsculas</em>, <em>Números</em></small>
	</div>

	<div class="form-line">
		<label for="password">Contrase&ntilde;a deseada*</label>
		<input name="password" type="password" id="password" tabindex="2" placeholder="Ingresa una contrase&ntilde;a segura" autocomplete="off" /> 
		<div class="help"></div>
		<div id="password-strength"><span></span> <em>Nivel</em></div>
	</div>

	<div class="form-line">
		<label for="password2">Confirme contrase&ntilde;a*</label>
		<input name="password2" type="password" id="password2" tabindex="3" placeholder="Vuelve a ingresar la contrase&ntilde;a" autocomplete="off" /> 
		<div class="help"></div>
		<div id="password2-strength"><span></span> <em>Nivel</em></div>
	</div>

	<div class="form-line">
		<label for="email">E-mail*</label>
		<input name="email" type="text" id="email" tabindex="4" placeholder="Ingresa tu direcci&oacute;n de email" autocomplete="off" /> 
		<div class="help"></div>
	</div>

	<div class="form-line">
		<label>Fecha de Nacimiento*</label>
		<input type="hidden" id="max" value="{$tsMaxY}">
		<input type="hidden" id="end" value="{$tsEndY}">
		<input name="nacimiento" type="date" id="nacimiento" tabindex="5" min="{$tsMaxY}-12-31" max="{$tsEndY}-12-31" autocomplete="off" /> 
		<div class="help"></div>
	</div>
    
	<div class="form-line">
		<input type="hidden" name="response" id="response" class="g-recaptcha">
		<label class="list-label" for="terminos">
			<input type="checkbox" class="checkbox" id="terminos" name="terminos" tabindex="6" title="Acepta los T&eacute;rminos y Condiciones?" /> 
			<span>Acepto los <a href="{$tsConfig.url}/pages/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>*</span>
		</label> 
		<div class="help"></div>
	</div>
	<small>Todos los campos con * son requerido</small>
</form>

<script>
$(() => {
	const publicKey = '{$tsConfig.pkey}';

	function loadScript(url) {
	   return new Promise((resolve, reject) => $.getScript(url, resolve));
	}

	loadScript('https://www.google.com/recaptcha/api.js?render=' + publicKey)
   .then(() => {
     	grecaptcha.ready(() => {
         grecaptcha.execute(publicKey, { action: 'submit' }).then(token => {
            // Supongo que 'response' está definido en registro.js
            response.value = token;
            $(".mensajeAviso").hide()
				avanzar = true;
         });
     });
   })
   .then(() => loadScript('{$tsConfig.js}/registro.js'))
   .catch(error => console.error('Error cargando scripts:', error));
})
</script>