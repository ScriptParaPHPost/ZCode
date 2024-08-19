<div class="content-tabs perfil">
	<fieldset>

		<div class="upform-group">
			<label class="upform-label" for="nombre">Nombre completo</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="user-male-circle"}
				</div>
				<input class="upform-input" type="text" name="nombre" id="nombre" value="{$tsPerfil.p_nombre}">
			</div>
		</div>
		
		<div class="upform-group">
			<label class="upform-label" for="mensaje">Mensaje Personal <small>Se puede usar bbcode</small></label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">
					{uicon name="thread"}
				</div>
				<textarea value="" maxlength="60" name="mensaje" id="mensaje" class="upform-textarea">{$tsPerfil.p_mensaje}</textarea>
			</div>
		</div>

		<div class="upform-group">
			<label class="upform-label" for="sitio">Sitio Web</label>
			<div class="upform-group-input upform-icon">
				<div class="upform-input-icon">{uicon name="link-horizontal"}</div>
				<input class="upform-input" type="text" name="sitio" id="sitio" value="{$tsPerfil.p_sitio}" placeholder="https://example.com">
			</div>
		</div>


	<div class="field field-social">
		<span class="upform-label h2 d-block py-2">Redes sociales</span>
		<div class="d-block d-lg-grid gap-3" style="grid-template-columns: repeat(2, 1fr);">
         {foreach $tsRedes key=name item=red}
	         <div class="upform-group">
					<div class="upform-group-input upform-icon">
						<div class="upform-input-icon">
							{uicon name="{$red.icon}" folder="{$red.folder}"}
						</div>
						<input class="upform-input" type="text" name="red[{$name}]" id="red[{$name}]" value="{$tsPerfil.p_socials.$name}" placeholder="{$red.nombre}">
					</div>
				</div>
         {/foreach}
       </div>
	</div>
	 <div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="btn">
	 </div>
</fieldset>
</div>