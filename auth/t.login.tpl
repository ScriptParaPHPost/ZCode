<div class="auth-header">
	<h2>Iniciar sesión</h2>
	<h4>Para continuar a {$tsConfig.titulo}</h4>
</div>
{if $SocialMager}
	<div class="buttons-social">
		{foreach $SocialMager key=i item=social}
			<a class="social social--{$i} btn-active" href="{$social}">{uicon name="$i" folder="prime" class="btn--icon"}</a>
		{/foreach}
	</div>
{/if}

<div class="upform-group">
	<label class="upform-label" for="nick">Dirección de correo electronico o nombre de usuario</label>
	<div class="upform-group-input upform-icon">
		<div class="upform-input-icon">
			{uicon name="user-male"}
		</div>
		<input class="upform-input" type="text" name="username" id="nick" placeholder="JhonDoe" required>
	</div>
	<small class="upform-status help"></small>
</div>

<div class="upform-group">
	<label class="upform-label" for="password">Contraseña</label>
	<div class="upform-group-input upform-icon upform-input-icon-2">
		<div class="upform-input-icon">
			{uicon name="lock"}
		</div>
		<input class="upform-input" type="password" name="password" id="password" placeholder="{$tsPass}" required>
		<div class="upform-input-icon">
			<div id="IWantSeePassword" title="Ver contraseña!" class="iconify unlock"></div>
		</div>
	</div>
	<small class="upform-status help"></small>
</div>

<div class="upform-check">
	<input type="checkbox" class="inp-cbx" name="rem" id="remember" value="true" checked />
	<label for="remember" class="cbx"><span>
		<svg viewBox="0 0 12 10" height="10px" width="12px"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
		<span>Mantener mi sesión iniciada...</span>
	</label>
</div>

<div class="upform-buttons">
	<input type="submit" class="btn btn-block" value="Iniciar sesion">
</div>

<div class="text-center">
	<p class="p">No tienes una cuenta? <a href="{$tsConfig.url}/registro/" class="link">Registrarme</a></p>
	<hr>
	<p class="p"><span class="block text-align-center" data-toggle="forget_password">¿Olvidaste tu contraseña?</span></p>
</div>