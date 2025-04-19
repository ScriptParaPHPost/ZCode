/* AFILIACION */
const afiliado = {
	endpoint(page, where) {
		const { url: endpoint } = ZCodeApp;
		return `${endpoint}/afiliado-${page}.php`;
	},
	modal(title, body, confirmTxt, confirmAction) {
		let buttons = {
			confirmTxt,
			confirmAction,
			cancelShow: true
		}
		if (confirmTxt === true && !confirmAction) {
			buttons = { confirmAction: true };
		}
		UPModal.setModal({ title, body, buttons });
	},
	nuevo(where) {
		$.get(this.endpoint('nuevo-form', where), function(form) {
			afiliado.modal('Nueva Afiliaci&oacute;n', form, `Enviar datos`, `afiliado.enviar(0, '${where}')`);
		})
	},
	enviar(b, where) {
		let data1 = verifyInput('#aurl', 'La url no puede estar vacío.');
		let data2 = verifyInput('#atitle', 'El titulo no puede estar vacío.');
		let data3 = verifyInput('#atxt', 'La descripcion no puede estar vacío.');
		if(data1 === false || data2 === false || data3 === false ) return;
		UPModal.proccess_start('Enviando los datos...');
		afiliado.enviando($('form[name="AFormInputs"]').serialize(), where);
	},
	enviando(params, where) {
		loading.start();
		$.post(this.endpoint('enviando', where), params, res => {
			UPModal.proccess_end();
			let numb = parseInt(res.charAt(0));
			if(numb !== 1) {
				$('#AFStatus > span').fadeOut().text((numb === 2 ? 'Faltan datos' : 'La URL es incorrecta')).fadeIn();
				return;
			} 
			UPModal.alert('Bien', res.substring(3), false);
			loading.end();
		})
	},
	detalles(ref, where) {
		loading.start() 
		$.post(afiliado.endpoint('detalles', where), { ref }, function(response) {
			afiliado.modal('Detalles', response, true);
			loading.end();
		}) 
	}
};