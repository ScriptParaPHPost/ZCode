<div class="auth-header">
	<h2>Crear una cuenta</h2>
	<h4>Para continuar a {$tsConfig.titulo}</h4>
</div>

{include "Input.tpl" id="nick" label="Nick" type="text" name="nick" placeholder="JhonDoe" required=true icon="user-male"}
{include "Input.tpl" id="password" label="Contraseña" type="text" name="password" placeholder=$tsPass required=true icon="lock" showPassword=true html='<div id="password-strength"><span></span> <em></em></div><small id="generar">Generar contraseña</small>'}

{include "Input.tpl" id="email" label="Tu correo" type="email" name="email" placeholder="jhondoe@example.com" required=true icon="mail"}

<div class="sex">
	<label for="none" class="selected-sex">
		<input type="radio" name="sexo" id="none" value="none" checked>
		<span class="selected-sex--option">
			{uicon name="question-circle" size="1.625rem"}
			<span class="option--text">No decir</span>
		</span>
	</label>
	<label for="female" class="selected-sex">
		<input type="radio" name="sexo" id="female" value="female">
		<span class="selected-sex--option">
			{uicon name="user" size="1.625rem"}
			<span class="option--text">Femenino</span>
		</span>
	</label>
	<label for="male" class="selected-sex">
		<input type="radio" name="sexo" id="male" value="male">
		<span class="selected-sex--option">
			{uicon name="user-male" size="1.625rem"}
			<span class="option--text">Masculino</span>
		</span>
	</label>
	<small class="upform-status help"></small>
</div>

<div class="upform-check">
	<input type="checkbox" class="inp-cbx" name="terminos" id="terminos" value="true" title="Acepta los T&eacute;rminos y Condiciones?">
  	<label for="terminos" class="cbx">
  		<span>
      	<svg viewBox="0 0 12 10" height="10px" width="12px"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg>
      </span>
      <span>Acepto los <a class="fw-bold" href="{$tsConfig.url}/pages/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.</span>
  	</label>
	<small class="upform-status help"></small>
</div>

<div class="upform-buttons">
	<input type="hidden" name="response" id="response" class="g-recaptcha">
	<input type="submit" class="btn btn-block" value="Crear cuenta">
</div>

<div class="text-align-center">
	<p class="block">Ya tengo una cuenta? <a href="{$tsConfig.url}/login/" class="link">Iniciar sesión</a></p>
	{if $SocialMager}
		<hr>
		<div class="buttons-social">
			{foreach $SocialMager key=i item=social}
				<a class="social social--{$i} btn-active" href="{$social}">{uicon name="$i" folder="prime" class="btn--icon"} <span>Iniciar con {$i}</span></a>
			{/foreach}
		</div>
	{/if}
</div>
