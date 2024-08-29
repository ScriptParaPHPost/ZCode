<div class="panel-divider">
	{if isset($pageLogin)}
		<div class="panel overflow-hidden d-flex justify-content-center align-items-center flex-column">
			<div class="panel-background position-absolute" style="--image: url('{$tsConfig.assets}/images/favicon/logo-512.webp');"></div>
			<img class="rounded shadow" src="{$tsConfig.assets}/images/favicon/logo-128.webp" alt="logo del sitio {$tsConfig.titulo}">
			<strong class="text-uppercase h3 d-block mt-4">{$tsConfig.titulo}</strong>
		</div>
	{/if}

	<form method="POST" class="py-{if isset($pageLogin)}5{else}2{/if} px-4">
		{if isset($pageLogin)}
			<h1 class="m-0 p-0 mb-4 h3 d-block text-center">Bienvenido a <strong>{$tsConfig.titulo}</strong></h1>
		{/if}
		{if $SocialMager}
			<div class="form-line d-{if isset($pageLogin)}block{else}flex justify-content-center align-items-center column-gap-2{/if} mb-3">
				{foreach $SocialMager key=i item=social}
					<a class="btn btn--{$i}{if !isset($pageLogin)} btn--only-icon{/if} btn-active" href="{$social}">
						{uicon name="$i" folder="prime" class="btn--icon"}
						<span class="btn--text">Con {$i}</span>
					</a>
				{/foreach}
			</div>
			<h4 class="text-center mb-2">o iniciar con tu nick/correo</h4>
		{/if}

		<div class="upform-group">
			<label class="upform-label" for="nick">Nick o email</label>
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
			<span data-toggle="forget_password" class="fw-bold color-phpost">¿Olvidaste tu contraseña?</span>
		</div>

		<div class="upform-check">
			<label>
				<input type="checkbox" name="rem" id="remember" value="true" checked>
				<span class="upform-check-icon"></span>
				<span>Mantener sesión iniciada...</span>
			</label>
		</div>
		{if isset($pageLogin)}
		<div class="upform-buttons">
			<input type="submit" class="btn btn-block" value="Iniciar sesion">
		</div>
		{/if}

		<div class="text-center">
			<p class="p">No tienes una cuenta? <a href="{$tsConfig.url}/registro/" class="color-phpost fw-bold">Registrarme</a></p>
		</div>

	</form>
</div>
{if !isset($pageLogin)}{zCode js="login.js"}{/if}