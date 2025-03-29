export function desactivate({ start = 0 }) {
	if (start === 0 || start === 1) {
		loadModalDesctivar(start);
	} else {
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

function loadModalDesctivar(paso = 0) {
	let message = (paso === 0) ? 'Si desactiva su cuenta, todo el contenido relacionado a usted dejar&aacute; de ser visible durante un tiempo. Pasado ese tiempo, la administraci&oacute;n borrar&aacute; todo su contenido y no podr&aacute; recuperarlo.' : '&#191;Seguro que quiere desativar su cuenta?';
	let init = ++paso;

	UPModal.setModal({
		title: 'Desactivar Cuenta',
		body: message,
		buttons: {
			confirmTxt: 'Lo s&eacute;',
			confirmAction: `desactivate(${init})`,
			cancelShow: true,
			cancelTxt: 'No desactivar',
			cancelAction: 'close'
		}
	});
}