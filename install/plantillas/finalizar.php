<?php if(!empty($message)): ?>
	<div class="alert alert-important alert-danger alert-dismissible" role="alert">
		<div><?= $message ?></div>
	</div>
<?php endif; ?>
<form method="POST">
	<div class="box end">

		<div class="alert alert-important alert-danger alert-dismissible" role="alert">
			<div>Ingresa a tu FTP y borra el archivo <strong>../app/extras/<?= pathinfo(__FILE__, PATHINFO_BASENAME) ?></strong> antes de usar el script.</div>
		</div>

		<p class="lead">Gracias por instalar <strong><?= $_SESSION['script'] ?></strong>. Tu nueva comunidad <strong>Link Sharing System</strong> está lista para ser utilizada. Inicia sesión con tus credenciales para comenzar a disfrutar de todas las funcionalidades.</p>
		<div class="hr-text">
	  		<span>En discord</span>
		</div>
		<p>Te invito a unirte al servidor en <a href="https://discord.gg/mx25MxAwRe" rel="external" target="_blank">discord</a> regularmente para mantenerte al tanto de futuras actualizaciones.</p>
		<input type="hidden" name="key" value="<?= $key ?>" />
	</div>
	<div class="text-center py-3">
		<button class="btn btn-dark" type="submit">Finalizar</button>
		<small class="text-muted d-block mt-3 fst-italic">Si encuentras algún error, por favor repórtalo; tu colaboración es fundamental para mejorar el sistema para todos.</small>
	</div>
</form>