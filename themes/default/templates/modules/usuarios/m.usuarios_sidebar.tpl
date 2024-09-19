<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="funnel"}
		</div>
		<div class="up-header--title">
			<span>Filtrar</span>
		</div>
	</div>
	<div class="up-card--body">
		<form action="" method="get" class="box-body">
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="checkbox" name="online" value="true" class="up-checkbox"{if $tsFiltro.online == 'true'} checked{/if}/> 
				<span>En linea</span>
			</label>
			<hr class="separator">
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="radio" name="avatar" value="true" class="up-radio"{if $tsFiltro.avatar == 'true'} checked{/if}/> 
				<span>Con avatar</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="radio" name="avatar" value="false" class="up-radio"{if $tsFiltro.avatar == 'false'} checked{/if}/> 
				<span>Sin avatar</span>
			</label>
			<hr class="separator">
			<!-- RADIO -->
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="radio" name="sexo" value="none" class="up-radio"{if $tsFiltro.sex == 'none'} checked{/if}>
				<span>Sin definir</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="radio" name="sexo" value="male" class="up-radio"{if $tsFiltro.sex == 'male'} checked{/if}>
				<span>Hombre</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 py-2">
				<input type="radio" name="sexo" value="female" class="up-radio"{if $tsFiltro.sex == 'female'} checked{/if}>
				<span>Mujer</span>
			</label>
			<hr class="separator">
			<div class="upform-group">
				<div class="drop-select" id="drop-select-pais">
					<button type="button" class="drop-select--toggle">Selecciona un país</button>
					<div class="drop-select--menu">
						<div class="drop-select--item" data-value="">
							<span>Selecciona un país</span>
						</div>
						{foreach from=$tsPaises key=code item=pais}
							<div class="drop-select--item" data-value="{$code}"{if $code == $tsPerfil.user_pais} selected{/if}>
								{$tsPaisesSVG[$code|lower]}
								<span>{$pais}</span>
							</div>
						{/foreach}
					</div>
					<input type="hidden" name="pais" id="pais">
				</div>
			</div>

			<div class="upform-group">
				<div class="drop-select" id="drop-select-rango">
					<button type="button" class="drop-select--toggle">Selecciona un rango</button>
					<div class="drop-select--menu">
						<div class="drop-select--item" data-value="">
							<span>Selecciona un rango</span>
						</div>
						{foreach from=$tsRangos key=code item=r}
							<div class="drop-select--item" data-value="{$r.rango_id}"{if $code == $tsPerfil.user_pais} selected{/if}>
								<img class="avatar avatar-2" src="{$r.r_image}" alt="{$r.r_name}">
								<span>{$r.r_name}</span>
							</div>
						{/foreach}
					</div>
					<input type="hidden" name="rango" id="rango">
				</div>
			</div>
			<hr class="separator">
			<div class="form-button">
				<input type="submit" class="btn" value="Filtrar" />
				<a href="{$tsConfig.url}/usuarios/" class="btn btn-outline">Borrar Filtrar</a>
			</div>
		</form>
	</div>
</section>