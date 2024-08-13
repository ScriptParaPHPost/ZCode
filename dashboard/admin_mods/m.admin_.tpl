<div class="boxy-title">
	<h3>Centro de Administraci&oacute;n</h3>
</div>

<div id="res" class="boxy-content">
	<div class="hero">
		<h4>Bienvenido(a), {$tsUser->nick}!</h4>
		<p>Este es tu &quot;<strong>Centro de Administraci&oacute;n de {$tsConfig.titulo}</strong>&quot;. Aqu&iacute; puedes modificar la configuraci&oacute;n de tu web, modificar usuarios, modificar posts, y muchas otras cosas.<br />Si tienes algun problema, por favor revisa la p&aacute;gina de &quot;<strong>Soporte y Cr&eacute;ditos</strong>&quot;.  Si esa informaci&oacute;n no te sirve, puedes <a href="https://phpost.es" target="_blank">visitarnos para solicitar ayuda</a> acerca de tu problema.</p>
	</div>

	<div class="row">
		<div class="col-12 col-lg-9">
			<div class="zcode up-card">
				<div class="up-card--header">
					<div class="up-header--title">
						<h4>{$tsConfig.titulo} en directo</h4>
					</div>
				</div>
				<ul id="ulitmas_noticias" class="pp_list up-card--body list-unstyled">
					<div class="empty">Cargando...</div>
				</ul>
			</div>
			<div class="zcode up-card">
				<div class="up-card--header up-card--tabs">
					<div class="up-header--title">
						<h4>Último commit en Github</h4>
					</div>
					<div class="up-tabs">
						<label for="main"><input type="radio" name="branch" id="main" checked><span>Main</span></label>
						<label for="dev"><input type="radio" name="branch" id="dev"><span>Dev</span></label>
					</div>
				</div>
				<ul id="lastCommit" class="pp_list up-card--body list-unstyled">
					<div class="empty">Cargando...</div>
				</ul>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="zcode up-card version">
				<div class="up-card--header" data-icon="true">
					<div class="up-header--icon">
						<span uicon="clipboard-check"></span>
					</div>
					<div class="up-header--title">
						<span>Estado</span>
					</div>
				</div>
				<ul id="status_pp" class="pp_list up-card--body list-unstyled">
					<li>
						<div class="title">Actualizar archivos!</div>
						<div class="body">
							<strong>Verificando...</strong>
						</div>
					</li>
				</ul>
			</div>
			<div class="zcode up-card">
				<div class="up-card--header" data-icon="true">
					<div class="up-header--icon">
						<span uicon="cube"></span>
					</div>
					<div class="up-header--title">
						<span>zCode</span>
					</div>
				</div>
				<ul id="ultima_version" class="pp_list up-card--body list-unstyled">
					<li class="list-clone">
						<div class="title text-body-secondary">Versi&oacute;n instalada</div>
						<div class="body fw-bold">{$tsConfig.version}</div>
					</li>
				</ul>
			</div>
			<div class="zcode up-card">
				<div class="up-card--header" data-icon="true">
					<div class="up-header--icon">
						<span uicon="users"></span>
					</div>
					<div class="up-header--title">
						<span>Administradores</span>
					</div>
				</div>
				<ul class="pp_list up-card--body list-unstyled">
					{foreach from=$tsAdmins item=admin}
						<li><div class="title"><a href="{$tsConfig.url}/perfil/{$admin.user_name}">{$admin.user_name}</a></div></li>
					{/foreach}
				</ul>
			</div>
			<div class="zcode up-card">
				<div class="up-card--header" data-icon="true">
					<div class="up-header--icon">
						<span uicon="file-download"></span>
					</div>
					<div class="up-header--title">
						<span>Instalaciones</span>
					</div>
				</div>
				<ul class="pp_list stats up-card--body list-unstyled">
				 	<li class="d-flex justify-content-between align-items-center py-1"><span>Fundaci&oacute;n</span><span title="{$tsInst.0|fecha}">{$tsInst.0|hace:true}</span></li>
				 	<li class="d-flex justify-content-between align-items-center py-1"><span>Actualizado</span><span title="{$tsInst.1|fecha}">{$tsInst.1|hace:true}</span></li>
				</ul>
			</div>
		</div>
	</div>

</div>