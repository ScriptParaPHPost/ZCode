/* AFILIACION */
const afiliado = {
	nuevo() {
		$.get(ZCodeApp.url + '/afiliado-nuevo-form.php', form => {
			UPModal.setModal({
				title: 'Nueva Afiliaci&oacute;n',
				body: form,
				buttons: {
					confirmTxt: `Enviar datos`,
					confirmAction: `afiliado.enviar(0)`,
					cancelShow: true
				}
			});
		})
	},
	enviar() {
		verifyInput('#aurl', 'La url no puede estar vacío.');
		verifyInput('#atitle', 'El titulo no puede estar vacío.');
		verifyInput('#atxt', 'La descripcion no puede estar vacío.');
		UPModal.proccess_start('Enviando los datos...');
		afiliado.enviando($('form[name="AFormInputs"]').serialize());
	},
	enviando(params) {
		loading.start() 
		$.post(ZCodeApp.url + '/afiliado-enviando.php', params, h => {
			UPModal.proccess_end();
			switch(h.charAt(0)){
				case '0':
				case '2':
					let text = (h.charAt(0) == 2) ? 'Faltan datos' : 'La URL es incorrecta';
					$('#AFStatus > span').fadeOut().text(text).fadeIn();
				break;
				case '1':
					UPModal.alert('Bien', h.substring(3), false);
				break;
			}
			loading.end() 
		})
	},
	detalles(ref) {
		loading.start() 
		$.post(`${ZCodeApp.url}/afiliado-detalles.php`, { ref }, response => {
			UPModal.setModal({
				title: 'Detalles',
				body: response,
				buttons: {
					confirmShow: true
				}
			});
		}) 
	}
};