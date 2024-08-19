<div class="only-avatar">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="camera-alt"}</div>
			<div class="up-header--title">
				<span>Mi Avatar</span>
			</div>
		</div>
		<div class="d-block d-lg-grid up-card--body webp-gif">
			<div class="avatar-content avatar-big-cont position-relative mx-auto my-3 overflow-hidden shadow-sm rounded avatar avatar-19">
				<div style="display:none;" class="avatar-loading">
					{uicon name="ring-resize" folder="spinner"}
				</div>
				<img src="{$tsUser->avatar['img']}" class="avatar-big avatar avatar-19" id="avatar-img" loading="lazy"/>
			</div>
			<div class="panel w-100">
				<div class="panel--pc">
					<div class="upform-group my-3">
						<div class="upform-file">
							<input type="file" name="desktop" aria-label="Archivo" class="browse">
							<span class="upform-file-text">{uicon name="file-upload"} Seleccionar Archivo</span>
						</div>
					</div>
				</div>
				<div class="panel--url">
					<div class="upform-group my-3">
						<div class="upform-group-input upform-icon upform-icon-2">
							<div class="upform-input-icon">{uicon name="link"}</div>
							<input class="upform-input browse" type="text" name="url" placeholder="Url de la imagen">
							<div class="upform-input-icon verify">{uicon name="check"}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center w-100" id="more_avatar">
			<h3 class="d-block my-2">O selecciona un avatar...</h3>
			<div class="d-flex justify-content-start align-items-center flex-wrap gap-3">
				{foreach $tsAvatarSelect item=avatar}
					<div data-avatar="{$avatar.id}" class="overflow-hidden avatar avatar-6 shadow">
						<img src="{$avatar.image}" alt="{$avatar.avatar}" class="w-100 h-100 object-fit-cover" loading="lazy">
					</div>
				{/foreach}
			</div>
		</div>
	</section>

	{if $tsConfig.c_avatar == 1}
		<section class="up-card">
			<div class="up-card--header" icon="true">
				<div class="up-header--icon">{uicon name="camera"}</div>
				<div class="up-header--title">
					<span>Mi Avatar GIF</span>
				</div>
			</div>
			<div class="up-card--body webp-gif d-block d-lg-grid column-gap-3 place-center">
				<div class="avatar-content position-relative mx-auto my-3 overflow-hidden shadow-sm rounded avatar avatar-19">
					<div style="display:none;" class="avatar-loading position-absolute place-center">{uicon name="ring-resize" folder="spinner" class="avatar avatar-7"}</div>
					<img src="{$tsConfig.logos.128}" data-src="{$tsUser->avatar['gif']}" class="avatar-big avatar avatar-19 object-fit-cover" loading="lazy"/>
				</div>
				<div class="w-100">
					<div class="upform-group">
						<div class="upform-group-input upform-icon">
							<div class="upform-input-icon">{uicon name="link"}</div>
							<input class="upform-input browse-gif" type="text" name="avatar_gif" placeholder="Url de la imagen gif" value="{$tsUser->avatar['gif']}">
						</div>
					</div>
					<div class="upform-check">
						<label>
							<input type="checkbox" name="avatar_active"{if $tsPerfil.user_gif_active} checked{/if}>
							<span class="upform-check-icon"></span>
							<span>Usar gif como avatar</span>
						</label>
					</div>
				</div>
			</div>
		</section>
	{/if}
</div>