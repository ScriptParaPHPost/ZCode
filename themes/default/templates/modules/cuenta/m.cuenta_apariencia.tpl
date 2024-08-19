<div class="content-tabs cuenta">
	<fieldset>
		<div class="row">
			<div class="col-12 col-lg-6">
				<div class="upform-group">
					<label class="upform-label" for="scheme">Seleccione el modo</label>
					<div class="upform-group-input">
						<select class="upform-select" name="scheme" id="scheme">
							<option value="0"{if $tsPerfil.user_scheme == '0'} selected{/if}>Modo claro/light</option>
							<option value="1"{if $tsPerfil.user_scheme == '1'} selected{/if}>Modo oscuro/dark</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6">
				<div class="upform-group">
					<label class="upform-label" for="color">Seleccione el color</label>
					<div class="upform-group-input">
						<select class="upform-select" name="color" id="color">
							{foreach $tsColoresTxt key=id item=color}
								<option value="{$id}"{if $tsPerfil.user_color == $id} selected{/if}>{$color}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="customizar_tema row d-none">
				<div class="col-12 col-lg-6">
					<div class="example rounded shadow py-1 px-2 d-flex justify-content-start align-items-center column-gap-2 mb-3 mt-2" data-theme="light">
						<div class="box--example box-light avatar avatar-3 normal"></div>
						<div class="box--example box-light avatar avatar-3 hover"></div>
						<div class="box--example box-light avatar avatar-3 active"></div>
						<div class="box--example box-light avatar avatar-3 transparent"></div>
					</div>
					<div class="example rounded shadow py-1 px-2 d-flex justify-content-start align-items-center column-gap-2" data-theme="dark">
						<div class="box--example box-dark avatar avatar-3 normal"></div>
						<div class="box--example box-dark avatar avatar-3 hover"></div>
						<div class="box--example box-dark avatar avatar-3 active"></div>
						<div class="box--example box-dark avatar avatar-3 transparent"></div>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<span class="d-block fw-semibold">Color 'Light'</span>
					<input type="color" name="light" value="{$tsPerfil.custom.light}" class="w-100 rounded border-0">
					<span class="d-block fw-semibold">Color 'Dark'</span>
					<input type="color" name="dark" value="{$tsPerfil.custom.dark}" class="w-100 rounded border-0">
				</div>
			</div>
		</div>

		<hr class="separator">
	
		{if isset($gd_info)}
			<div class="empty">{$gd_info}</div>
		{/if}
		{include "m.cuenta_avatar.tpl"}

	</fieldset>
	
</div>