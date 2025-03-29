<div class="auth-header">
	<h2>Iniciar sesión</h2>
	<h4>Para continuar a {$tsConfig.titulo}</h4>
</div>

{include "Input.tpl" id="nick" label="Dirección de correo o nombre de usuario" type="text" name="username" placeholder="JhonDoe" required=true icon="user-male"}

{include "Input.tpl" id="password" label="Contraseña" type="password" name="password" placeholder=$tsPass required=true icon="lock" showPassword=true}
<small class="block text-align-center" data-toggle="forget_password" onclick="login.multiOptions('password', false);return false;" style="float:right;">¿Olvidaste tu contraseña?</small>
{include "Checkbox.tpl" id="remember" name="rem" value="true" checked=true label="Mantener mi sesión iniciada..."}


<div class="upform-buttons">
	<input type="submit" class="btn btn-block w-100" value="Iniciar sesion">
</div>

<div class="text-center">
	<p style="text-align:center;">No tienes una cuenta? <a href="{$tsConfig.url}/registro/" class="link">Registrarme</a></p>
	{if $SocialMager}
		<hr>
		<div class="buttons-social">
			{foreach $SocialMager key=i item=social}
				<a class="social social--{$i} btn-active" href="{$social}">{uicon name="$i" folder="prime" class="btn--icon"} <span>Iniciar con {$i}</span></a>
			{/foreach}
		</div>
	{/if}
</div>