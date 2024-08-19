function actualizar_comentarios(cat, nov) {
	loading.start()
	$('#ult_comm, #ult_comm > ol').slideUp(150);
	$.get(ZCodeApp.url + '/posts-last-comentarios.php', { cat, nov }, h => {
		$('#ult_comm').html(h);
		$('#ult_comm > ol').hide();
		$('#ult_comm, #ult_comm > ol:first').slideDown(1500, 'easeInOutElastic');
		loading.end()
	}).fail(() => {
		$('#ult_comm, #ult_comm > ol:first').slideDown({
			duration: 1000,
			easing: 'easeOutBounce'
		});
		loading.end()
	});
}

async function loadTabFilter({ category, period = 'historico' }) {
	const pagina = `tops-${category === 'topsPost' ? 'posts' : 'usuarios'}.php`;
	const $filterShow = $(`.up-card[category="${category}"] .filterShow`);

	$filterShow.html(`<div class="empty">Cargando ${period}...</div>`);

	try {
		const response = await fetch(`${ZCodeApp.url}/${pagina}`, {
			method: 'POST',
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({ period }),
		});

		const responseData = await response.text();
		$filterShow.html(responseData);
	} catch (error) {
	  	console.error('Error al cargar los datos:', error);
	  	$filterShow.html('<div class="empty">Error al cargar los datos</div>');
	}
}

$(document).ready(() => {
	$('.filter span').on('click', function(action) {
  		const $this = $(this);
  		const category = $this.data('category');
  		const period = $this.data('period');
		// Desactivamos el active
		$('.filter span').attr('data-active', false);
    	$(`.up-card[category="${category}"] .filter span[data-period="${period}"]`).attr('data-active', true);
    	// Cargamos...
    	loadTabFilter({ category, period });
	});
});