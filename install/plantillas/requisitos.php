<?php if(!empty($message)): ?>
	<div class="alert alert-important alert-danger alert-dismissible" role="alert">
		<div><?= $message ?></div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Requisitos del sistema</h3>
			</div>
			<div class="list-group list-group-flush list-group-hoverable">
				<?php foreach ($system_status as $name => $info): ?>
					<div class="list-group-item">
						<div class="row align-items-center">
							<div class="col-auto"><span class="badge bg-<?= $info['class'] ?>"></span></div>
							<div class="col-auto">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-folder"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>
							</div>
							<div class="col text-truncate">
								<span class="text-reset d-block"><?= $info['text'] ?></span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Permisos de carpetas</h3>
			</div>
			<div class="list-group list-group-flush list-group-hoverable">
				<?php foreach ($folder_status as $folder => $info): ?>
					<div class="list-group-item">
						<div class="row align-items-center">
							<div class="col-auto"><span class="badge bg-<?= $info['class'] ?>" title="<?= $info['text'] ?>"></span></div>
							<div class="col-auto">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folder"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>
							</div>
							<div class="col text-truncate">
								<span class="text-reset d-block"><?= ucfirst($folder) ?></span>
								<div class="d-block text-secondary text-truncate mt-n1"><?= $info['route'] ?></div>
							</div>
							<div class="col-auto">
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<div class="text-center py-3">
	<form method="POST">
		<?php if(!$disabled && $continue): ?>
			<input type="hidden" name="comprobar" value="false">
			<button class="btn btn-dark" type="submit">Continuar la instalación</button>
		<?php else: ?>
			<input type="hidden" name="comprobar" value="true">
			<button class="btn btn-dark" type="submit">Volver a verificar</button>
		<?php endif; ?>
	</form>
</div>