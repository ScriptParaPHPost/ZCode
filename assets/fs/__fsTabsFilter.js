let cache = {};
function loadTab(type) {
	let nameTab = `perfil_${type}`
	let status = $(`#${nameTab}`).attr('status');

	if(status == 'activo') {
		// LOADER/ STATUS
		$('#perfil_load').hide();
		$(`#${nameTab}`).fadeIn();
		return true;   
	} else {
		$.post(`${ZCodeApp.url}/${nameTab.replace('_','-')}.php`, { pid: $('#info').attr('pid') }, response => {
			const message = response.substring(3);
			const number = parseInt(response.charAt(0));
			if(number === 0) UPModal.alert('Error', message);
			if(number === 1) {
				if(typeof cache[type] === 'undefined') {
					$('#perfil_content').append(message);
					$(`#${nameTab}`).fadeIn();
					cache[type] = true;
				}
			}
			// LOADER/ STATUS
			$('#perfil_load').hide();
			loading.end(); 
		});
	}
}

export function loadTabs({ obj, classObj }) {
	const tab = obj.attr('tab');
	$(classObj).removeClass('selected');
	obj.addClass('selected');
	// Aplicamos algunos efectos
	$('#perfil_content > div').fadeOut();
	$('#perfil_load').fadeIn();
	// Cargamos contenido de dicho tab!
	loading.start();
	loadTab(tab);
}

export function handleLoadFilter(type) {
	const params = [
		'pid=' + $('#info').attr('pid'),
		'type=' + type
	].join('&');
	$('.filter-item').removeClass('active');
	$(`.filter-item:nth-child(${type})`).addClass('active');
	$.post(`${ZCodeApp.url}/muro-filtro.php`, params, req => {
		$('#wall-content').html(req.substring(3));
	})
}