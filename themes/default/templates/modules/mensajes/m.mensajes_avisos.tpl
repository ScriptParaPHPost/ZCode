{if $tsMensajes}
	<div id="mpList" class="row row-cols-2">
		{foreach from=$tsMensajes item=av}
			<div class="col">
				<div class="rounded shadow p-2{if $av.av_read == '0'} unread{/if}" id="av_{$av.av_id}">
					<div class="mb-1 pb-1 d-flex justify-content-between align-items-center">
						<div class="d-flex justify-content-start align-items-center column-gap-2">
							{uicon name="avtype_{$av.av_type}" folder="other" size="1.5rem"}
							<div class="autor h5 d-block m-0 fw-semibold">{$tsConfig.titulo}</div>
						</div>
						<a href="{$tsConfig.url}/mensajes/avisos/?did={$av.av_id}" class="h6" title="Eliminar">{uicon name="trash-alt" class="pe-none"}</a>
					</div>
					<a href="{$tsConfig.url}/mensajes/avisos/?aid={$av.av_id}" class="d-block text-decoration-none p-1">
						<div class="d-flex justify-content-between align-items-center">
							<div class="subject">{$av.av_subject}</div>
							<time class="fw-semibold small">{$av.av_date|hace:true}</time>
						</div>
						<div class="preview fst-italic small">{$av.av_preview}</div>
					</a>
				</div>
			</div>
		{/foreach}
	</div>
{elseif $tsMensaje.av_id > 0}
	<div class="mpRContent row">
		<div class="col-12 col-lg-9">
			<div class="p-0">
				<div class="mpHeader py-2 d-flex justify-content-start align-items-center column-gap-2">
					{uicon name="avtype_{$tsMensaje.av_type}" folder="other" size="3.325rem"}
					<div class="mpTitle">
						<span class="info small d-block"><a href="{$tsConfig.url}" class="text-decoration-none fw-semibold" rel="internal">{$tsConfig.titulo}</a> <span> - {$tsMensaje.av_date|hace:true}</span></span>
						<h2 class="m-0 h5">{$tsMensaje.av_subject}</h2>
					</div>
				</div>
				<div class="mpHistory p-1" id="historial">
					{$tsMensaje.av_body|nl2br}
				</div>
			</div>
		</div>
		<div class="mpOptions col-12 col-lg-3">
			<section class="up-card">
				<div class="up-card--header" icon="false">
					<div class="up-header--title">
						<span>Acciones</span>
					</div>
				</div>
				<div class="up-card--body">
					<a class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" href="{$tsConfig.url}/mensajes/avisos/?did={$tsMensaje.av_id}">Eliminar</a>
					<div class="div"></div>
					<a class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" href="{$tsConfig.url}/mensajes/avisos/">&laquo; Volver a avisos</a>
				</div>
			</section>
		</div>
{else}
	<div class="empty">{if $tsMensaje}{$tsMensaje}{else}No hay avisos o alertas{/if}</div>
{/if}