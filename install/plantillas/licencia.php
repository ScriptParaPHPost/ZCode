<form method="POST">
	<p class="lead">Para utilizar <strong><?= $FN->generate_name_version() ?></strong> debes estar de acuerdo con nuestra licencia de uso.</p>
	<pre rows="15"><?= $LICENSE ?></pre>
	<label class="form-check form-switch">
    	<input class="form-check-input" type="checkbox" name="agree" value="true" />
    	<span class="form-check-label">Acepto los términos y condiciones de la licencia.</span>
  	</label>
	
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
	<script>
		$('input[name="agree"]').on('click', function(e) {
			let checked = $(this).prop('checked');
			$('button[type="submit"]').attr({ disabled: !checked });
		});
	</script>
	<div class="text-center py-3">
		<button class="btn btn-dark" type="submit" disabled>Aceptar y Continuar</button>
	</div>
</form>