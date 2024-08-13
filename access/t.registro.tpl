<div class="panel-divider">

	<div class="panel overflow-hidden d-flex justify-content-center align-items-center flex-column">
		<div class="panel-background position-absolute" style="--image: url('{$tsConfig.assets}/images/favicon/logo-512.webp');"></div>
		<img class="rounded shadow" src="{$tsConfig.assets}/images/favicon/logo-128.webp" alt="logo del sitio {$tsConfig.titulo}">
		<strong class="text-uppercase h3 d-block mt-4">{$tsConfig.titulo}</strong>
	</div>

	<form method="POST" class="py-5 px-4" disabled>
		<h1 class="m-0 p-0 mb-4 h3 d-block text-center">Crear cuenta en <strong>{$tsConfig.titulo}</strong></h1>

		{if $SocialMager}
			<div class="form-line d-flex justify-content-center align-items-center gap-3 mb-3">
				{foreach $SocialMager key=i item=social}
					<a class="btn btn--{$i} btn-active" href="{$social}">
						{uicon name="$i" folder="prime" class="btn--icon"}
						<span class="btn--text">Crear con {$i}</span>
					</a>
				{/foreach}
			</div>
			<h4 class="text-center mb-2">o crea cuenta manualmente</h4>
		{/if}

		<div class="upform-group">
			<label class="upform-label" for="nick">Nick</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="user-male"}
				</div>
				<input class="upform-input" type="text" name="nick" id="nick" placeholder="JhonDoe" required>
			</div>
			<small class="upform-status help"></small>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="password">Contraseña</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="lock"}
				</div>
				<input class="upform-input" type="password" name="password" id="password" placeholder="{$tsPass}" required>
				<div class="upform-input-icon">
					<div id="IWantSeePassword" title="Ver contraseña!" class="iconify unlock"></div>
				</div>
			</div>
			<small class="upform-status help"></small>
			<div id="password-strength"><span></span> <em></em></div>
			<small id="generar">Generar contraseña</small>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="email">Tu correo</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="mail"}
				</div>
				<input class="upform-input" type="email" name="email" id="email" placeholder="jhondoe@example.com" required>
			</div>
			<small class="upform-status help"></small>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="nacimiento">Fecha de Nacimiento</label>
			<input type="hidden" id="max" value="{$tsMaxY}">
			<input type="hidden" id="end" value="{$tsEndY}">
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="calendar-date"}
				</div>
				<input class="upform-input" type="date" name="nacimiento" id="nacimiento" min="{$tsMaxY}-12-31" max="{$tsEndY}-12-31" required>
			</div>
			<small class="upform-status help"></small>
		</div>

		<div class="form-radio-icon d-flex justify-content-start align-items-center gap-3">
			<label for="none" class="position-relative rounded">
				<input type="radio" name="sexo" id="none" value="none" checked class="position-absolute w-100 h-100">
				<span class="d-flex justify-content-center align-items-center flex-column">
					{uicon name="question-circle" size="1.625rem"}
					<span class="fw-bold d-block px-2">No decir</span>
				</span>
			</label>
			<label for="female" class="position-relative rounded">
				<input type="radio" name="sexo" id="female" value="female" class="position-absolute w-100 h-100">
				<span class="d-flex justify-content-center align-items-center flex-column">
					{uicon name="user" size="1.625rem"}
					<span class="fw-bold d-block px-2">Femenino</span>
				</span>
			</label>
			<label for="male" class="position-relative rounded">
				<input type="radio" name="sexo" id="male" value="male" class="position-absolute w-100 h-100">
				<span class="d-flex justify-content-center align-items-center flex-column">
					{uicon name="user-male" size="1.625rem"}
					<span class="fw-bold d-block px-2">Masculino</span>
				</span>
			</label>
			<small class="upform-status help"></small>
		</div>

		<div class="upform-check">
			<input type="hidden" name="response" id="response" class="g-recaptcha">
			<label>
				<input type="checkbox" name="terminos" id="terminos" value="true" title="Acepta los T&eacute;rminos y Condiciones?">
				<span class="upform-check-icon"></span>
				<span>Acepto los <a class="fw-bold" href="{$tsConfig.url}/pages/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.</span>
			</label>
			<small class="upform-status help"></small>
		</div>


		<div class="upform-buttons">
			<input type="submit" class="btn btn-block" value="Crear cuenta">
		</div>

		<div class="text-center">
			<p class="p">Ya tengo una cuenta? <a href="{$tsConfig.url}/login/" class="fw-bold">Iniciar sesión</a></p>
		</div>

	</form>
</div>
