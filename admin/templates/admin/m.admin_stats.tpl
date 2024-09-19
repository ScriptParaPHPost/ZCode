<div class="boxy-title">
	<h3>Administrar Estad&iacute;sticas</h3>
</div>
<div id="res" class="boxy-content">
	<div class="stats-grid">
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Posts <span>{$tsAdminStats.posts_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Visibles</span><strong>{$tsAdminStats.posts_visibles}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>En revisi&oacute;n</span><strong>{$tsAdminStats.posts_revision}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Inactivos</span><strong>{$tsAdminStats.posts_ocultos}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Eliminados</span><strong>{$tsAdminStats.posts_eliminados}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Posts compartidos</span><strong>{$tsAdminStats.posts_compartidos}</strong></li>	
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Posts favoritos</span><strong>{$tsAdminStats.posts_favoritos}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Fotos <span>{$tsAdminStats.fotos_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Visibles</span><strong>{$tsAdminStats.fotos_visibles}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>En revisi&oacute;n</span><strong>{$tsAdminStats.fotos_ocultas}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Eliminadas</span><strong>{$tsAdminStats.fotos_eliminadas}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Comentarios en Posts <span>{$tsAdminStats.comentarios_posts_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Visibles</span><strong>{$tsAdminStats.comentarios_posts_visibles}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>En revisi&oacute;n</span><strong>{$tsAdminStats.comentarios_posts_ocultos}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Usuarios <span>{$tsAdminStats.usuarios_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Activos</span><strong>{$tsAdminStats.usuarios_activos}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Inactivos</span><strong>{$tsAdminStats.usuarios_inactivos}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Suspendidos</span><strong>{$tsAdminStats.usuarios_baneados}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Muro <span>{$tsAdminStats.muro_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Estados</span><strong>{$tsAdminStats.muro_estados}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Comentarios</span><strong>{$tsAdminStats.muro_comentarios}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Afiliados <span>{$tsAdminStats.afiliados_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Activos</span><strong>{$tsAdminStats.afiliados_activos}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Inactivos</span><strong>{$tsAdminStats.afiliados_inactivos}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Medallas <span>{$tsAdminStats.medallas_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Usuarios</span><strong>{$tsAdminStats.medallas_usuarios}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Posts</span><strong>{$tsAdminStats.medallas_posts}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Fotos</span><strong>{$tsAdminStats.medallas_fotos}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Asignadas</span><strong>{$tsAdminStats.medallas_asignadas}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Seguimiento <span>{$tsAdminStats.seguidos_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Usuarios</span><strong>{$tsAdminStats.usuarios_follows}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Posts</span><strong>{$tsAdminStats.posts_follows}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Mensajes <span>{$tsAdminStats.mensajes_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Eliminados por receptor</span><strong>{$tsAdminStats.mensajes_para_eliminados}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Eliminados por autor</span><strong>{$tsAdminStats.mensajes_de_eliminados}</strong></li>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Respuestas</span><strong>{$tsAdminStats.usuarios_respuestas}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList border rounded shadow-sm">
			<h4 class="d-flex justify-content-between align-items-center fs-5 m-0 p-2">Comentarios en Fotos <span>{$tsAdminStats.comentarios_fotos_total}</span></h4>
			<ul>
				<li class="d-flex justify-content-between align-items-center border-bottom p-2"><span>Visibles</span><strong>{$tsAdminStats.comentarios_fotos_total}</strong></li>
			</ul>
		</div>
	</div>
</div>