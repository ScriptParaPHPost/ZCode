<div class="row py-3">
	<div class="col-12 col-lg-6">
		<h4>No tienes el token de github para continuar</h4>
		<span class="d-block">Para continuar puedes obtener tu token <a href="https://github.com/settings/personal-access-tokens/new" target="_blank">desde aquí</a>, obviamente que deberás tener una cuenta en github para poder obtener las actualizaciones.</span>
		<span class="d-block">Si en el caso que no tengas y no quieras crear una cuenta en github puedes solicitar un token para acceder.</span>
		<span class="d-block mt-3">
			<small class="d-block">&bull; A mi perfil en <a href="https://phpost.es/user-23.html" target="_blank">PHPost.es</a> me envias un MP.</small>
			<small class="d-block">&bull; A mi perfil en <a href="https://discordapp.com/users/465203938900049920" target="_blank">Discord</a>.</small>
		</span>
	</div>
	<div class="col-12 col-lg-6">
		<div class="upform-group">
			<label for="TOKEN" class="upform-label">Agregar TOKEN</label>
			<input type="text" id="TOKEN" name="TOKEN" class="upform-input" placeholder="github_pat_xxxxxxxxxxxxxxxxxxxxxxx..." />
			<span role="button" class="btn btn-sm mt-3" onclick="token.create()">Agregar</span>
		</div>
		<span>En la página de github para crear token:</span>
		<ul>
			<li>Token name: Un nombre que deseen</li>
			<li>Expiration: Eligen el tiempo de caducidad</li>
			<li>Description: Si quieren agregan una descripción</li>
			<li>Una vez creado copian el token y lo agregan al input</li>
		</ul>
	</div>
</div>