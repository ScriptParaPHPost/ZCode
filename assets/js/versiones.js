// FEED SUPPORT
$.getJSON(ZCodeApp.url + "/feed-support.php?from=dashboard", response => {
	$('#ulitmas_noticias').html('<div class="empty">Obteniendo información...</div>');
	if(Array.isArray(response)) {
		$('#ulitmas_noticias').html('');
		response.map( data => {
			const { link, title, info, version } = data;
			let html = `<a href="${link}" target="_blank" class="feed:item">
			   <div class="feed:header">
			   	<h5>${title}</h5>
			   	<small class="feed:version">${version}</small>
			   </div>
			   <span class="feed:info">${info}</span>
			</a>`;
			$('#ulitmas_noticias').append(html);
		})
	} else $('#ulitmas_noticias').html(`<div class="empty">${response}</div>`)
});

//
$.getJSON(ZCodeApp.url + "/feed-version.php?from=dashboard", response => {
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

function changeBranch(branch = 'main') {
	$.post(ZCodeApp.url + '/github-api.php?from=dashboard', { branch }, response => {
		if(response === null) {
			$('#lastCommit').html('<div class="empty">No se puede cargar el último commit...</div>');
			return;
		}
		const { 
			sha: commit_sha_code, 
			html_url: link_last_commit,
			commit: { 
				message, 
				author: { date: AuthorDate },
				verification: { reason, verified }
			} 
		} = response;
		//
		$('#lastCommit').html('');
		// Creamos la plantilla para mostrar la infomación del mismo
		// Reemplazamos \n por saltos de línea con <br>
		let messageAlter = message.replace(/\n/g, '-');
		let contentNew = '';
		messageAlter.split('--').map( (msg, position) => {
			let bold = (position === 0) ? ' fw-semibold fs-5' : '';
			let verifiedCommit = (verified) ? 'verified' : reason;
			let AddVerified = (position === 0) ? `<small class="badge main-bg position-absolute small" style="top:1.125rem;right:.5rem;">${verifiedCommit}</small>` : '';
			contentNew += `<span class="d-block mb-2${bold}">${msg}${AddVerified}</span>`;
		});
	
		let toImageTemplate = `<div class="data-github py-3 position-relative">${contentNew}</div>
		<div class="d-flex justify-content-between align-items-center px-2 py-1 border-top translucent-bg">
			<span>Sha: <a href="${link_last_commit}" class="text-decoration-none text-primary" rel="noreferrer" target="_blank">${commit_sha_code.substring(0, 7)}...</a></span>
			<time class="fst-italic small">${$.timeago(AuthorDate)}</time>
		</div>`;

		// La añadimos al HTML
		$('#lastCommit').append(joypixels.toImage(toImageTemplate));
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