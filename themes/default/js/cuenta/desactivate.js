export function desactivate({ start = 0 }) {	
	if (start == 0) {
		UPModal.setModal({
			title: 'Desactivar Cuenta',
			body: 'Si desactiva su cuenta, todo el contenido relacionado a usted dejar&aacute; de ser visible durante un tiempo. Pasado ese tiempo, la administraci&oacute;n borrar&aacute; todo su contenido y no podr&aacute; recuperarlo.',
			buttons: {
				confirmTxt: 'Lo s&eacute;',
				confirmAction: 'desactivate(1)',
				cancelShow: true,
				cancelTxt: 'No desactivar',
				cancelAction: 'close'
			}
		});
	} else if (start == 1) {
		UPModal.setModal({
			title: 'Desactivar Cuenta',
			body: '&#191;Seguro que quiere desativar su cuenta?',
			buttons: {
				confirmTxt: 'Desactivar cuenta',
				confirmAction: 'desactivate(2)',
				cancelShow: true,
				cancelTxt: 'No desactivar',
				cancelAction: 'close'
			}
		});
	} else {
		var pass = $('#passi');
		UPModal.proccess_start('Estamos procesando...');
		$.post(`${ZCodeApp.url}/cuenta.php?action=desactivate`, 'validar=ajaxcontinue', req => {
			let validation = parseInt(req.charAt(0)) === 0;
			toast.start({
			  title: (validation ? 'Opps!' : 'Hecho'),
			  content: (validation ? 'No se pudo desactivar' : 'Cuenta desactivada'),
			  type: (validation ? 'danger' : 'success')
			});
			UPModal.proccess_end();
		});
	}
};