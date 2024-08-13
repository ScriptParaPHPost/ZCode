// FEED SUPPORT
$.getJSON(ZCodeApp.url + "/feed-support.php", response => {
	$('#ulitmas_noticias').html('<div class="empty">Obteniendo información...</div>');
	if(Array.isArray(response)) {
		$('#ulitmas_noticias').html('');
		response.map( data => {
			const { link, title, info, version } = data;
			let html = `<a href="${link}" target="_blank" class="feed-item">
			 	<div class="me-auto">
			   	<div class="fw-bold">${title}</div>
			   	<span class="text-secondary">${info}</span>
			 	</div>
    			<span class="up-badge">${version}</span>
			</a>`;
			$('#ulitmas_noticias').append(html);
		})
	} else $('#ulitmas_noticias').html(`<div class="empty">${response}</div>`)
});

//
$.getJSON(ZCodeApp.url + "/feed-version.php?v=risus", response => {
	const { version, status, color } = response;
	// Clonamos
  	let clonar = $('.list-clone').first().clone();
  	// Añadimos color
  	clonar.addClass(color)
  	// Modificar los datos dentro del clon
  	clonar.find('.fw-bold').text(version);
  	clonar.find('.text-body-secondary').text(status);
  	// Agregar el clon a la lista
	if(typeof version === 'undefined') {
		clonar.addClass('list-clone-danger')
		clonar.find('.fw-bold').text('No version');
  		clonar.find('.text-body-secondary').text(response);
	}
  	$('#ultima_version').append(clonar);
});

// ACTUALIZAR ARCHIVOS
$.getJSON(ZCodeApp.url + "/feed-system.php", response => {
	let { status, message } = response;
	if(parseInt(status) === 0) {
		message += `\n<span role="button" onclick="SystemUpdate()" class="btn d-block btn-sm main-color">Actualizar ahora...</span>`;
	}
	$('#status_pp strong').html(message);
});

function SystemUpdate() {
	$.get(ZCodeApp.url + "/feed-update.php", response => {
		let type = parseInt(response.charAt(0));
		if(type === 0) {
			UPModal.alert('Error', response.substring(3));
		} else {
			$('#status_pp strong').html(response.substring(3));
			setTimeout(() => location.reload(), 5000);
		}
	});
}

function changeBranch(branch = 'main') {
	$.post(ZCodeApp.url + '/github-api.php', { branch }, response => {
		const { sha, commit, files, html_url } = response;
		if(response.message) {
			let html = `<div class="data-github" style="line-height:1.3rem">${response.message}</div>`;
			$('#lastCommit').html(html);
			return;
		}
		let cookiename = "LastCommitSha";
		let expires = { expires: 7 }
		//
		$('#lastCommit').html('');
		// Reemplazamos \n por saltos de línea con <br>
		content = response.commit.message.replace(/\n/g, '<br>');
		// Si la pantalla es menor a 1120px solo tendrá 7 caracteres
		SHA = (window.width < 1120) ? response.sha.substring(0, 7) : sha;
		// Si la Cookie no existe la crearemos por 7 días
		if(cookie.get(cookiename) === null) cookie.create(cookiename, SHA, expires);
		// Obtenemos el valor de la cookie
		let getSHA = cookie.get(cookiename);
		// Comparamos
		if(SHA !== getSHA) {
			url = ZCodeApp.url + '/admin-update.php';
			$.post(url, 'update_now=false', r => $.cookie(cookiename, getSHA, expires))
		}
		let hace = $.timeago(commit.author.date)
		// Creamos la plantilla para mostrar la infomación del mismo
		let html = `<div class="data-github">${content}</div>`;

		$('.panel-info.last-commit .card-footer').html(`<span>Sha: <a href="${html_url}" class="text-decoration-none text-primary" rel="noreferrer" target="_blank">${SHA}</a></span><span>${hace}</span>`);

		// La añadimos al HTML
		let transform = joypixels.toImage(html);
		$('#lastCommit').append(transform);
	}, 'json')
}
// Autoejecutamos
changeBranch();

$('input[name=branch]').on('click', e => {
	$('#lastCommit').html('<div class="empty">Cargando...</div>');
	if ($('input[name=branch]').is(':checked')) {
    	const selectedOption = e.target.id;
    	changeBranch(selectedOption);
  	}
})