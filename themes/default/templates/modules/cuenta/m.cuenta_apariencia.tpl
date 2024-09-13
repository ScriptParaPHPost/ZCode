<div class="content-tabs cuenta">
	<fieldset>
		<h4>Personalice el aspecto de la página <strong>{$tsConfig.titulo}</strong></h4>
		<h5 class="border-top border-bottom py-2 d-flex justify-content-between align-items-center my-3">Sincronizar con el sistema 
			<label class="switch d-inline-block">
    			<input type="checkbox" class="d-none" name="scheme" id="scheme"{if $tsPerfil.user_scheme == '1'} checked{/if}>
    			<div class="slider d-flex align-items-center position-relative">
        			<div class="circle position-relative z-1 d-flex justify-content-center align-items-center">
            		<svg class="position-absolute h-auto cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg"><g><path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path></g></svg>
            		<svg class="position-absolute h-auto checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg"><g><path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path></g></svg>
            	</div>
            </div>
         </label>
		</h5>

		<div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
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
		<hr>
		<h3>Accesibilidad</h3>
		<p>Mejore la experiencia en {$tsConfig.titulo} adaptando la web a sus necesidades</p>

		<div class="upform-group d-grid column-gap-3" style="grid-template-columns: 200px 1fr;">
			<label class="upform-label" for="font_family">Familia de la fuente</label>
			<div class="upform-group-input">
				<select class="upform-select" name="font_family" id="font_family">
					{foreach from=$tsFontFamily key=key item=use_family}
						<option data-font-family="{$key}" value="{$key}"{if $tsPerfil.user_font_family == $key} selected{/if}>{$use_family}</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="upform-group d-grid column-gap-3" style="grid-template-columns: 200px 1fr;">
			<label class="upform-label" for="font_size">Tamaño de la fuente</label>
			<div class="upform-group-input">
				<select class="upform-select" name="font_size" id="font_size">
					{foreach from=$tsFontSize key=size item=use_size}
						<option value="{$size}"{if $tsPerfil.user_font_size == $size} selected{/if}>{$use_size}</option>
					{/foreach}
				</select>
			</div>
		</div>

	</fieldset>
	
</div>