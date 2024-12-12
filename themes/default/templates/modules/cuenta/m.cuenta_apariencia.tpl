<div class="content-tabs cuenta">
	<fieldset>
		<h4>Personalice el aspecto de la página <strong>{$tsConfig.titulo}</strong></h4>
		<h6 class="py-2 d-flex justify-content-between align-items-center my-3">Modo completo
			{include "m.switch.tpl" name="pagebox" id="pagebox" active=$tsPerfil.user_pagebox} 
		</h6>
		<h6 class="py-2 d-flex justify-content-between align-items-center my-3">Sincronizar con el sistema (dark/light)
			{include "m.switch.tpl" name="scheme" id="scheme" active=$tsPerfil.user_scheme}
		</h6>
		<hr>
		<h6 class="border-top pt-2 mt-2">Accesibilidad</h6>
		<p>Mejore la experiencia en {$tsConfig.titulo} adaptando la web a sus necesidades</p>

		<div class="upform-group d-block d-md-grid column-gap-3" style="grid-template-columns: 200px 1fr;">
			<label class="upform-label" for="font_family">Familia de la fuente</label>
			<div class="upform-group-input">
				<select class="upform-select" name="font_family" id="font_family">
					{foreach from=$tsFontFamily key=key item=use_family}
						<option data-font-family="{$key}" value="{$key}"{if $tsPerfil.user_font_family == $key} selected{/if}>{$use_family}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="upform-group d-block d-md-grid column-gap-3" style="grid-template-columns: 200px 1fr;">
			<label class="upform-label" for="font_size">Tamaño de la fuente</label>
			<div class="upform-group-input">
				<select class="upform-select" name="font_size" id="font_size">
					{foreach from=$tsFontSize key=size item=use_size}
						<option value="{$size}"{if $tsPerfil.user_font_size == $size} selected{/if}>{$use_size}</option>
					{/foreach}
				</select>
			</div>
		</div>
		
		<h6 class="border-top pt-2 mt-2">Color para el sitio</h6>
		<div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-4">
			{foreach $tsColoresValue key=id item=color}
				<div class="col">
					<label class="d-block mb-4 syncThemeColor" role="button" data-color="{$id}">
						<div data-theme-color="{$color|lower}" data-theme="{$tsSchemeColor.scheme}" class="tc{$id} rounded shadow-sm overflow-hidden d-grid{if $tsPerfil.user_color == $id} border{/if}" style="height:100px;grid-template-columns: 10% 90%;--border-color:var(--main-bg)!important;box-shadow:0 0 .5rem var(--main-bg);">
							<div class="main-bg h-100"></div>
							<div class="position-relative" style="--opacity:.5;background:var(--main-bg-rgb);">
								<div class="d-flex justify-content-between align-items-center position-absolute column-gap-2" style="top: 0.5rem;right: 0.5rem;">
									<div class="rounded-circle avatar avatar-2" style="background:var(--main-bg-hover);"></div>
									<div class="rounded-circle avatar avatar-2" style="background:var(--main-bg-active);"></div>
								</div>
							</div>
						</div>
						<h5 class="fs-6 d-block text-center">{$tsColoresTxt[$id]}</h5>
					</label>
				</div>
			{/foreach}
		</div>
	
		<div class="customizar_tema row{if $tsSchemeColor.color !== 'customizer'} d-none{/if}">
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

	</fieldset>
	
</div>