const database = {
	table_action(action, table, id = 0) {
		loading.start();
		$.post(`${ZCodeApp.url}/database-${action}.php?from=dashboard`, { table }, req => {
			let type = (parseInt(req.charAt(0)) === 0) ? 'Error' : 'Bien';
			let msg = req.substring(3);
			if(action === 'optimize') {
				$(`td[data-cache="${id}"]`).html('Vacio');
				$(`span[data-remove="${id}"]`).remove();
			}
			if(id != 0) $(`td[data-update="${id}"]`).html('Hace instantes');
			UPModal.alert(type, msg, false);
			loading.end();
		});
	},
	tablas() {
		let tablas = {};
		$('input[type="checkbox"]').each((i, inpt) => {
			const ic = $(inpt).val();
			if($(inpt).prop('checked') && $(inpt).val() != 'all') tablas[i] = ic;
		});
		return tablas;
	},
	table_all(action) {
		var tablas = this.tablas();
		if($.isEmptyObject(tablas)) {
			UPModal.alert('Espera', 'Debes seleccionar por lo menos una tabla.', false);
		} else {
			$.post(`${ZCodeApp.url}/database-all.php?from=dashboard`, { action, tablas }, req => {
				let type = (parseInt(req.charAt(0)) === 1);
				let msg = req.substring(3);
				if(type) {
					Object.values(tablas).forEach((id, n) => {
						let number_id = Object.keys(tablas)[n];
						if(action === 'optimize') {
							$(`td[data-cache="${number_id}"]`).html('Vacio');
							$(`span[data-remove="${number_id}"]`).remove();
						}
						if(number_id != 0) $(`td[data-update="${number_id}"]`).html('Hace instantes');
					});
					UPModal.alert('Bien', msg, false);
				} else {
					UPModal.alert('Error', msg, false);
				}
			});
		}
	},
	create_backup() {
		loading.start();
		var tablas = $('input#todos').prop('checked') ? '*' : this.tablas();
		$.post(`${ZCodeApp.url}/database-backup.php?from=dashboard`, { tablas }, req => {
			let type = (parseInt(req.charAt(0)) === 0) ? 'Error' : 'Bien';
			let msg = req.substring(3);
			UPModal.alert(type, msg, false);
			loading.end();
		});
	}
}

const AllDropOtions = $('.drop-options .actions');
AllDropOtions.map((i, option) => {
	$(option).on('click', function() {
		let panel = $(this).data('target');
		if ($(panel).is(':visible')) {
			$(panel).hide(); 
		} else {
			$('.drop-box').hide(); // Cerramos todos los demás
			$(panel).show(); // Mostramos el seleccionado
		}
	});
});