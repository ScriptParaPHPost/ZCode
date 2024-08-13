<div class="boxy-title">
	<h3>Administrar Estad&iacute;sticas</h3>
</div>
<div id="res" class="boxy-content">
	<div class="grid">
		<div class="categoriaList estadisticasList">
			<h4>Posts <span>{$tsAdminStats.posts_total}</span></h4>
			<ul>
				<li><span>Visibles</span><strong>{$tsAdminStats.posts_visibles}</strong></li>
				<li><span>En revisi&oacute;n</span><strong>{$tsAdminStats.posts_revision}</strong></li>
				<li><span>Inactivos</span><strong>{$tsAdminStats.posts_ocultos}</strong></li>
				<li><span>Eliminados</span><strong>{$tsAdminStats.posts_eliminados}</strong></li>
				<li><span>Posts compartidos</span><strong>{$tsAdminStats.posts_compartidos}</strong></li>	
				<li><span>Posts favoritos</span><strong>{$tsAdminStats.posts_favoritos}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Fotos <span>{$tsAdminStats.fotos_total}</span></h4>
			<ul>
				<li><span>Visibles</span><strong>{$tsAdminStats.fotos_visibles}</strong></li>
				<li><span>En revisi&oacute;n</span><strong>{$tsAdminStats.fotos_ocultas}</strong></li>
				<li><span>Eliminadas</span><strong>{$tsAdminStats.fotos_eliminadas}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Comentarios en Posts <span>{$tsAdminStats.comentarios_posts_total}</span></h4>
			<ul>
				<li><span>Visibles</span><strong>{$tsAdminStats.comentarios_posts_visibles}</strong></li>
				<li><span>En revisi&oacute;n</span><strong>{$tsAdminStats.comentarios_posts_ocultos}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList">
			<h4>Usuarios <span>{$tsAdminStats.usuarios_total}</span></h4>
			<ul>
				<li><span>Activos</span><strong>{$tsAdminStats.usuarios_activos}</strong></li>
				<li><span>Inactivos</span><strong>{$tsAdminStats.usuarios_inactivos}</strong></li>
				<li><span>Suspendidos</span><strong>{$tsAdminStats.usuarios_baneados}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Muro <span>{$tsAdminStats.muro_total}</span></h4>
			<ul>
				<li><span>Estados</span><strong>{$tsAdminStats.muro_estados}</strong></li>
				<li><span>Comentarios</span><strong>{$tsAdminStats.muro_comentarios}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList">
			<h4>Afiliados <span>{$tsAdminStats.afiliados_total}</span></h4>
			<ul>
				<li><span>Activos</span><strong>{$tsAdminStats.afiliados_activos}</strong></li>
				<li><span>Inactivos</span><strong>{$tsAdminStats.afiliados_inactivos}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Medallas <span>{$tsAdminStats.medallas_total}</span></h4>
			<ul>
				<li><span>Usuarios</span><strong>{$tsAdminStats.medallas_usuarios}</strong></li>
				<li><span>Posts</span><strong>{$tsAdminStats.medallas_posts}</strong></li>
				<li><span>Fotos</span><strong>{$tsAdminStats.medallas_fotos}</strong></li>
				<li><span>Asignadas</span><strong>{$tsAdminStats.medallas_asignadas}</strong></li>
			</ul>
		</div>
		 <div class="categoriaList estadisticasList">
			<h4>Seguimiento <span>{$tsAdminStats.seguidos_total}</span></h4>
			<ul>
				<li><span>Usuarios</span><strong>{$tsAdminStats.usuarios_follows}</strong></li>
				<li><span>Posts</span><strong>{$tsAdminStats.posts_follows}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Mensajes <span>{$tsAdminStats.mensajes_total}</span></h4>
			<ul>
				<li><span>Eliminados por receptor</span><strong>{$tsAdminStats.mensajes_para_eliminados}</strong></li>
				<li><span>Eliminados por autor</span><strong>{$tsAdminStats.mensajes_de_eliminados}</strong></li>
				<li><span>Respuestas</span><strong>{$tsAdminStats.usuarios_respuestas}</strong></li>
			</ul>
		</div>
		<div class="categoriaList estadisticasList">
			<h4>Comentarios en Fotos <span>{$tsAdminStats.comentarios_fotos_total}</span></h4>
			<ul>
				<li><span>Visibles</span><strong>{$tsAdminStats.comentarios_fotos_total}</strong></li>
			</ul>
		</div>
	</div>
</div>
<style>
.estadisticasList { width: calc(calc(100% / 3) - 24px); }
</style>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script>
$('.grid').masonry({
  	// options
  	itemSelector: '.estadisticasList',
	columnWidth: 28
});
</script>