<?php if(!empty($message)): ?>
	<div class="alert alert-important alert-danger alert-dismissible" role="alert">
		<div><?= $message ?></div>
	</div>
<?php endif; ?>
<form method="POST">
	<p class="lead">Agrega todos los datos para la instalación del script</p>
	<div class="hr-text">
  		<span>Base de datos</span>
	</div>
	<fieldset>
		<div class="mb-3 row">
			<div class="col-3"><label for="sql_host" class="form-label required">Servidor</label></div>
			<div class="col-9"><input type="text" class="form-control" id="sql_host" name="sql_host" value="<?= $FN->get_value($faster, 'sql_host') ?>" placeholder="localhost" required></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="sql_user" class="form-label required">Usuario</label></div>
			<div class="col-9"><input type="text" class="form-control" id="sql_user" name="sql_user" value="<?= $FN->get_value($faster, 'sql_user') ?>" placeholder="root" required></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="sql_pass" class="form-label">Contraseña</label></div>
			<div class="col-9"><input type="password" class="form-control" id="sql_pass" name="sql_pass" value="<?= $FN->get_value($faster, 'sql_pass') ?>" placeholder="password"></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="sql_name" class="form-label required">Base de datos</label></div>
			<div class="col-9"><input type="text" class="form-control" id="sql_name" name="sql_name" value="<?= $FN->get_value($faster, 'sql_name') ?>" placeholder="name_database" required></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="sql_prefix" class="form-label">Prefix</label></div>
			<div class="col-9"><input type="text" class="form-control" id="sql_prefix" name="sql_prefix" value="<?= $FN->get_value($faster, 'sql_prefix') ?>" placeholder="zcode_"></div>
		</div>
	</fieldset>
	<div class="hr-text">
  		<span>Datos del sitio</span>
	</div>
	<fieldset>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_titulo" class="form-label required">Nombre</label></div>
			<div class="col-9"><input type="text" class="form-control" id="site_titulo" name="site_titulo" value="<?= $FN->get_value($faster, 'site_titulo') ?>" placeholder="<?= $_SESSION['script'] ?>" required></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_slogan" class="form-label required">Slogan</label></div>
			<div class="col-9"><input type="text" class="form-control" id="site_slogan" name="site_slogan" value="<?= $FN->get_value($faster, 'site_slogan') ?>" placeholder="Actualizando tu mundo" required></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_url" class="form-label required">URL</label></div>
			<div class="col-9">
				<div class="input-group">
              <span class="input-group-text"><?= $FN->secure_url() ?></span>
              <input type="text" class="form-control" id="site_url" name="site_url" value="<?= $FN->get_value($faster, 'site_url', '', 'url') ?>" placeholder="Actualizando tu mundo" required>
            </div>
         </div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_email" class="form-label required">Email</label></div>
			<div class="col-9"><input type="email" class="form-control" id="site_email" name="site_email" value="<?= $FN->get_value($faster, 'site_email', '', 'email') ?>" placeholder="noreply@domain.com"></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_public" class="form-label">Clave pública</label></div>
			<div class="col-9"><input type="text" class="form-control" id="site_public" name="site_public" value="<?= $FN->get_value($faster, 'site_public') ?>" placeholder="6LfFFiMdAAAAAAQjDafWXZ0FeyesKYjVm4DSUoao"></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="site_secret" class="form-label">Clave secreta</label></div>
			<div class="col-9"><input type="text" class="form-control" id="site_secret" name="site_secret" value="<?= $FN->get_value($faster, 'site_secret') ?>" placeholder="6LfFFiMdAAAAAFIP4oNFLQx5Fo1FyorTzNps8ChE"></div>
		</div>
		<small class="help">Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank"><strong>google.com/recaptcha/admin</strong></a></small>
	</fieldset>
	<div class="hr-text">
  		<span>Datos del usuario</span>
	</div>
	<fieldset>
		<div class="mb-3 row">
			<div class="col-3"><label for="admin_username" class="form-label required">Nombre de usuario</label></div>
			<div class="col-9"><input type="text" class="form-control" id="admin_username" name="admin_username" maxlength="20" value="<?= $FN->get_value($faster, 'admin_username') ?>" placeholder="JhonDoe"></div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="admin_userpassword" class="form-label required">Contraseña</label></div>
			<div class="col-9">
				<div class="input-group input-group-flat">
					<input type="password" class="form-control" id="admin_userpassword" name="admin_userpassword" value="<?= $FN->get_value($faster, 'admin_userpassword') ?>" placeholder="password">
					<span class="input-group-text" data-target="admin_userpassword">
						<span class="input-group-link pe-none">Mostrar</span>
					</span>
				</div>
			</div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="admin_confirm" class="form-label required">Repetir contraseña</label></div>
			<div class="col-9">
				<div class="input-group input-group-flat">
					<input type="password" class="form-control" id="admin_confirm" name="admin_confirm" value="<?= $FN->get_value($faster, 'admin_confirm') ?>" placeholder="password">
					<span class="input-group-text" data-target="admin_confirm">
						<span class="input-group-link pe-none">Mostrar</span>
					</span>
				</div>
			</div>
		</div>
		<div class="mb-3 row">
			<div class="col-3"><label for="admin_email" class="form-label required">Email</label></div>
			<div class="col-9"><input type="email" class="form-control" id="admin_useremail" name="admin_useremail" value="<?= $FN->get_value($faster, 'admin_useremail', '', 'email') ?>" placeholder="jhondoe@domain.com"></div>
		</div>
	</fieldset>

	<input type="hidden" name="datos" value="true">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
	<script>
		$('.input-group-text').on('click', function() {
			const k = $(this).data('target');
		   const input = $(`#${k}`);
		   let type = input.attr('type') === 'password' ? 'text' : 'password';
		   let text = input.attr('type') === 'password' ? 'Ocultar' : 'Mostrar';
		   input.attr('type', type);
		   $(this).html(text);
		});
	</script>
	<div class="text-center py-3">
		<div class="form-group-empty">
			<strong>Nota:</strong> El proceso de instalación puede tomar algunos minutos.
		</div>
		<button class="btn btn-dark" type="submit">Instalar</button>
	</div>
</form>