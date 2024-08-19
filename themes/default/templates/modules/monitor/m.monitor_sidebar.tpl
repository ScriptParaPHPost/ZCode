<section class="up-card" id="post-izquierda">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="funnel"}</div>
		<div class="up-header--title">
			<span>Filtrar Actividad</span>
		</div>
	</div>
	<div class="up-card--body">
		<div class="empty">Elige que notificaciones recibir y cuales no.</div>
		<div class="mb-3">
			<div class="fw-bold">Mis Posts</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_star"></span>
				<input type="checkbox" id="1"{if $tsData.filtro.f1 == true} checked{/if} onclick="notifica.filter()"/> <span>Favoritos</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_comment_post"></span>
				<input type="checkbox" id="2"{if $tsData.filtro.f2 == true} checked{/if} onclick="notifica.filter()"/> <span>Comentarios</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_points"></span>
				<input type="checkbox" id="3"{if $tsData.filtro.f3 == true} checked{/if} onclick="notifica.filter()"/> <span>Puntos Recibidos</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Mis Comentarios</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_voto"></span>
				<input type="checkbox" id="8"{if $tsData.filtro.f8 == true} checked{/if} onclick="notifica.filter()"/> <span>Votos</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_comment_resp"></span>
				<input type="checkbox" id="9"{if $tsData.filtro.f9 == true} checked{/if} onclick="notifica.filter()"/> <span>Respuestas</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Usuarios que sigo</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_follow"></span>
				<input type="checkbox" id="4"{if $tsData.filtro.f4 == true} checked{/if} onclick="notifica.filter()"/> <span>Nuevos</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_post"></span>
				<input type="checkbox" id="5"{if $tsData.filtro.f5 == true} checked{/if} onclick="notifica.filter()"/> <span>Posts</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_photo"></span>
				<input type="checkbox" id="10"{if $tsData.filtro.f10 == true} checked{/if} onclick="notifica.filter()"/> <span>Fotos</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_share"></span>
				<input type="checkbox" id="6"{if $tsData.filtro.f6 == true} checked{/if} onclick="notifica.filter()"/> <span>Recomendaciones</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Posts que sigo</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_blue_ball"></span>
				<input type="checkbox" id="7"{if $tsData.filtro.f7 == true} checked{/if} onclick="notifica.filter()"/> <span>Comentarios</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Estados</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_status"></span>
				<input type="checkbox" id="18"{if $tsData.filtro.f18 == true} checked{/if} onclick="notifica.filter()"/> <span>Actualizaci&oacute;n de estado</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Mis Fotos</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_comment_post"></span>
				<input type="checkbox" id="11"{if $tsData.filtro.f11 == true} checked{/if} onclick="notifica.filter()"/> <span>Comentarios</span>
			</label>
		</div>

		<div class="mb-3">
			<div class="fw-bold">Perfil</div>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_status"></span>
				<input type="checkbox" id="12"{if $tsData.filtro.f12 == true} checked{/if} onclick="notifica.filter()"/> <span>Publicaciones</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_w_comment"></span>
				<input type="checkbox" id="13"{if $tsData.filtro.f13 == true} checked{/if} onclick="notifica.filter()"/> <span>Comentarios</span>
			</label>
			<label class="d-flex justify-content-start align-items-center gap-2 p-2 check-filter rounded mb-2">
				<span class="d-inline-block monac_icons ma_w_like"></span>
				<input type="checkbox" id="14"{if $tsData.filtro.f14 == true} checked{/if} onclick="notifica.filter()"/> <span>Likes</span>
			</label>
		</div>
	</div>
</section>

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="graph-increase"}
		</div>
		<div class="up-header--title">
			<span>Estad&iacute;sticas</span>
		</div>
	</div>
	<div class="up-card--body d-grid gap-2" style="grid-template-columns: repeat(3, 1fr);">
		<a href="{$tsConfig.url}/monitor/seguidores" class="text-decoration-none py-2 text-center h2 m-0">
			<span class="fw-bold">{$tsData.stats.seguidores}</span>
			<span class="small text-uppercase d-block">Seguidores</span>
		</a>
		<a href="{$tsConfig.url}/monitor/siguiendo" class="text-decoration-none py-2 text-center h2 m-0">
			<span class="fw-bold">{$tsData.stats.siguiendo}</span>
			<span class="small text-uppercase d-block">Siguiendo</span>
		</a>
		<a href="{$tsConfig.url}/monitor/posts" class="text-decoration-none py-2 text-center h2 m-0">
			<span class="fw-bold">{$tsData.stats.posts}</span>
			<span class="small text-uppercase d-block">Posts</span>
		</a>
	</div>
</section>

{if $tsConfig.c_allow_live == 1}
<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">
			{uicon name="notification"}
		</div>
		<div class="up-header--title">
			<span>Notificaciones Live</span>
		</div>
	</div>
	<div class="up-card--body">
		<label class="d-flex justify-content-start align-items-center gap-2 py-2">
			<input type="checkbox" class="up-checkbox" onclick="live.sounds('notifications');"{if $tsStatus.live_notifications == 'ON'} checked{/if}/> 
			<span>Mostrar notificaciones</span>
		</label>
		<label class="d-flex justify-content-start align-items-center gap-2 py-2">
			<input type="checkbox" class="up-checkbox" onclick="live.sounds('messages');"{if $tsStatus.live_messages == 'ON'} checked{/if}/> 
			<span>Mostrar mensajes nuevos</span>
		</label>
		<label class="d-flex justify-content-start align-items-center gap-2 py-2">
			<input type="checkbox" class="up-checkbox" onclick="live.sounds('sound');"{if $tsStatus.live_sound == 'ON'} checked{/if}/>
			<span>Reproducir sonidos</span>
		</label>
	</div>
</section>
{/if}