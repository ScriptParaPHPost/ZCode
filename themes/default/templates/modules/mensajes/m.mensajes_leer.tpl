<div class="row">
	<div class="col-12 col-lg-8">
		<div class="mpRContent">
			<div class="mpHeader">
				<h2 class="m-0 text-capitalize">{$tsMensajes.msg.mp_subject}</h2>
			</div>
			<div class="mpUser">
				<span class="small d-block">Entre <a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="text-decoration-none fw-semibold">T&uacute;</a> y <a href="{$tsConfig.url}/perfil/{$tsMensajes.ext.user}" class="text-decoration-none fw-semibold">{$tsMensajes.ext.user}</a></span>
			</div>
			<div class="mpHistory" id="historial">
				{foreach from=$tsMensajes.res item=mp}
					<div class="py-3 border-bottom d-grid column-gap-2" style="grid-template-columns:3rem 1fr;">
						<a href="{$tsConfig.url}/perfil/{$mp.user_name}" class="d-block avatar avatar-5 overflow-hidden autor-image">
							<img loading="lazy" src="{$mp.avatar}" class="w-100 h-100 object-fit-cover" />
						</a>
						<div class="mensaje">
							<div class="d-flex justify-content-between align-items-center">
								<span>
									<a href="{$tsConfig.url}/perfil/{$mp.user_name}" class="text-decoration-none fw-semibold autor-name">{$mp.user_name}</a> 
									{if $tsUser->is_admod}<a href="{$tsConfig.url}/moderacion/buscador/1/1/{$mp.mr_ip}">{$mp.mr_ip}</a>{/if} 
								</span>
								<time class="mp-date small fst-italic">{$mp.mr_date|hace:true}</time>
							</div>
							<span class="d-block">{$mp.mr_body|nl2br}</span>
						</div>
					</div>
				{foreachelse}
					<div class="empty">No se pudieron cargar los mensajes.</div>
				{/foreach}
			</div>
			{if $tsUser->is_admod || ($tsMensajes.msg.mp_del_to == 0 && $tsMensajes.msg.mp_del_from == 0 && $tsMensajes.ext.can_read == 1)}
				<div class="mpForm">
					<div class="form mt-3">
						<textarea id="respuesta" class="w-100" placeholder="Escribe una respuesta..."></textarea>
						<input type="hidden" id="mp_id" value="{$tsMensajes.msg.mp_id}" />
						<span role="button" class="btn resp" onclick="mensaje.responder(); return false;">Responder</span>
					</div>
				</div>
			{else}
				<div class="empty">Un participante abandon&oacute; la conversaci&oacute;n o no tienes permiso para responder</div>
			{/if}
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<section class="up-card">
			<div class="up-card--header" icon="false">
				<div class="up-header--title">
					<span>Acciones</span>
				</div>
			</div>
			<div class="up-card--body">
				<span role="button" class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" onclick="mensaje.marcar('{$tsMensajes.msg.mp_id}:{$tsMensajes.msg.mp_type}', 1, 2, this); return false;">Marcar como no le&iacute;do</span>
				<div class="div"></div>
				<span role="button" class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" onclick="mensaje.eliminar('{$tsMensajes.msg.mp_id}:{$tsMensajes.msg.mp_type}',2); return false;">Eliminar</span>
				<span role="button" class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" onclick="denuncia.nueva('mensaje',{$tsMensajes.msg.mp_id}, '', ''); return false;">Marcar como correo no deseado...</span>
				<div class="div"></div>
				<span role="button" class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" onclick="denuncia.nueva('usuario',{if $tsMensajes.msg.mp_from != $tsUser->uid}{$tsMensajes.msg.mp_from}{else}{$tsMensajes.msg.mp_to}{/if}, '', '{if $tsMensajes.msg.mp_from != $tsUser->uid}{$tsMensajes.msg.user_name}{else}{$tsUser->getUsername($tsMensajes.msg.mp_to)}{/if}'); return false">Denunciar a este usuario...</span>
				<span role="button" class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" onclick="bloquear({$tsMensajes.ext.uid}, {if $tsMensajes.ext.block}false{else}true{/if}, 'mensajes')" id="bloquear_cambiar">{if $tsMensajes.ext.block}Desbloquear{else}Bloquear{/if} a <u>{$tsMensajes.ext.user}</u>...</span>
		
				<a class="d-block py-2 px-3 text-decoration-none fw-semibold hover:main-bg hover:main-color active:main-bg active:main-color rounded" href="{$tsConfig.url}/mensajes/">&laquo; Volver a mensajes</a>
			</div>
		</section>
	</div>
</div>