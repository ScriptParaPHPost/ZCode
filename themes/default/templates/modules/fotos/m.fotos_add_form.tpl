{if ($tsAction == 'agregar' && ($tsUser->permisos.gopf || $tsUser->is_admod)) || ($tsAction == 'editar' && ($tsUser->permisos.moedfo || $tsUser->is_admod))}
	<section class="up-card">
		{include "CardHeader.tpl" iconName="button_add" label="{if $tsAction === 'agregar'}Agregar nueva{else}Editar{/if} foto"}
		<div class="up-card--body">
			<form name="add_foto" method="post" action="" enctype="multipart/form-data" id="foto_form" class="form-add-post position-relative" autocomplete="off">
				<div class="loader h-100 w-100 z-99" style="display: none;">
					<div class="d-flex justify-content-center align-items-center flex-column row-gap-3 py-5">
						<img src="{$tsConfig.assets}/images/loading_bar.gif" />
						<h2>Cargando foto, espere por favor....</h2>
					</div>
				</div>
				<div class="fade_out">

					<div class="upform-group">
						<label class="upform-label" for="titulo">T&iacute;tulo</label>
						<div class="upform-group-input upform-icon">
							<div class="upform-input-icon">{uicon name="pen"}</div>
							<input class="upform-input required" type="text" name="titulo" id="titulo" placeholder="T&iacute;tulo de la foto" value="{$tsFoto.f_title}" required>
						</div>
						<small class="upform-status help"></small>
					</div>

					{if $tsAction != 'editar'}
						{if $tsConfig.c_allow_upload == 1}
							<div class="upform-group">
								<label class="upform-label" for="ffile">Archivo</label>
								<div class="upform-group-input">
									<input class="upform-input" type="file" name="file" id="ffile">
								</div>
							</div>
						{else}
							<div class="upform-group">
								<label class="upform-label" for="furl">URL</label>
								<div class="upform-group-input upform-icon">
									<div class="upform-input-icon">{uicon name="chain"}</div>
									<input class="upform-input required" type="text" name="url" id="furl" placeholder="{$tsConfig.url}/image/something.png" value="{$tsFoto.f_url}">
								</div>
							</div>
						{/if}
					{/if}

					<div class="upform-group">
						<label class="upform-label" for="fdesc">Descripci&oacute;n</label>
						<div class="upform-group-input upform-icon">
							<div class="upform-input-icon">{uicon name="create"}</div>
							<textarea name="description" id="fdesc" class="upform-textarea">{$tsFoto.f_description}</textarea>
						</div>
						<small class="upform-status">Max. 500 car.</small>
					</div>

					<div class="upform-group">
					   <span class="fw-bold">Opciones</span>
					   <div class="upform-check mb-3">
					      <label>
					         <input type="checkbox" name="closed" id="sin_comentarios"{if $tsFoto.f_closed == 1} checked{/if}>
					         <span class="upform-check-icon"></span>
					         <span>Cerrar Comentarios <small class="d-block">Si no quieres recibir comentarios en tu foto.</small></span>
					      </label>
					   </div>
					   <div class="upform-check mb-3">
					      <label>
					         <input type="checkbox" name="visitas" id="visitas"{if $tsFoto.f_visitas == 1} checked{/if}>
					         <span class="upform-check-icon"></span>
					         <span>&Uacute;ltimos visitantes <small class="d-block">Se mostrar&aacute;n los &uacute;ltimos visitantes.</small></span>
					      </label>
					   </div>
					</div>

					{if $tsUser->is_admod > 0 && $tsAction == 'editar' && $tsFoto.f_user  != $tsUser->uid}
						<div class="upform-group">
							<label class="upform-label" for="razon">Raz&oacute;n</label>
							<div class="upform-group-input upform-icon">
								<div class="upform-input-icon">{uicon name="pen"}</div>
								<input class="upform-input" type="text" name="razon" id="razon" placeholder="Si has modificado el contenido de esta foto, ingresa la raz&oacute;n.">
							</div>
							<small class="upform-status help">Si has modificado el contenido de esta foto, ingresa la raz&oacute;n.</small>
						</div>
					{/if}
					<div class="end-form clearbeta">
						<input type="button" style="width: auto; margin-left: 5px;" class="btn btnGreen" name="new" value="{if $tsAction == 'agregar'}Agregar foto{else}Guardar cambios{/if}" onclick="fotos.agregar()"/>
					</div>
				</div>
			</form>
		</div>
	</section>
{else}
	<div class="empty clearfix">
		Lo sentimos, pero no puedes {if $tsAction == 'agregar'}agregar{else}editar{/if} una nueva foto.
	</div>
{/if}