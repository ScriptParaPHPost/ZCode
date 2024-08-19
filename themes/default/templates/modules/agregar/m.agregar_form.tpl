<div class="upform-group">
	<label class="upform-label" for="titulo">T&iacute;tulo</label>
	<div class="upform-group-input upform-icon">
		<div class="upform-input-icon">{uicon name="pen"}</div>
		<input class="upform-input required" type="text" name="titulo" id="titulo" placeholder="T&iacute;tulo del post" value="{$tsDraft.b_title}" required>
	</div>
	<small class="upform-status help"></small>
	<div id="repost"></div>
</div>

<div class="upform-group">
	<a name="post"></a>
	<label class="upform-label" for="cuerpo">Contenido del Post</label>
	<div class="upform-group-input">
		<textarea id="cuerpo" load="wysibb" name="cuerpo" class="upform-textarea required" style="min-height: 500px">{$tsDraft.b_body}</textarea>
	</div>
	<small class="upform-status help"></small>
</div>

<div class="upform-group">
	<label class="upform-label" for="tags">Tags: <small>Una lista separada por comas, que describa el contenido.</small></label>
	<div class="upform-group-input upform-icon">
		<div class="upform-input-icon">{uicon name="tags"}</div>
		<input class="upform-input required" type="text" name="tags" id="tags" placeholder="{$tsConfig.titulo|replace:' ':', '}, PHP, Smarty, jQuery" value="{$tsDraft.b_tags}" required>
	</div>
	<small class="upform-status help"></small>
</div>
				
{if ($tsUser->is_admod > 0 || $tsUser->permisos.moedpo) && $tsDraft.b_title && $tsDraft.b_user != $tsUser->uid}
	<div class="upform-group">
		<label class="upform-label" for="razon">Raz&oacute;n: <small>Si has modificado el contenido de este post ingresa la raz&oacute;n por la cual lo modificaste.</small></label>
		<div class="upform-group-input upform-icon">
			<div class="upform-input-icon">{uicon name="inbox-alt"}</div>
			<input class="upform-input required" type="text" name="razon" id="razon" placeholder="Razón de la modificación." value="">
		</div>		
		<small class="upform-status help"></small>
	</div>
{/if}
		
<div class="end-form translucent-bg p-3 rounded d-flex justify-content-sm-between align-items-center">
	<div class="buttons">
		<span class="btn btn-outline btn-sm" id="borrador-save" onclick="guardar()">Guardar</span>
		<span class="btn btn-outline btn-sm" onclick="preliminar()">Previsualizar</span>
		<span class="btn btn-sm" onclick="publicar()">Publicar</span>
	</div>
	<div id="borrador-guardado" class="float-end fst-italic"></div>
</div>