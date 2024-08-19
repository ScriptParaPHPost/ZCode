export function twoFactorAuthRemove() {		
	$('.remove_2fa').on('click', function() {
		$.post(`${ZCodeApp.url}/cuenta-delete-2fa.php`, req => {
			const status = parseInt(req.charAt(0));
			const message = req.substring(3);
			UPModal.alert((status ? 'Bien' : 'Error'), message, status);
		});
	});
};

export function tokenRegenerate() {
	$.post(`${ZCodeApp.url}/cuenta-token-regenerate.php`, req => {
		const { status, message } = req;
	   if (status) {
	      let codes = message.split(',');
	      let app = '<div style="display:grid;grid-template-columns:repeat(3, 1fr)">';
	      codes.map(code => app += `<span>${code}</span>`);
	      app += '</div>';
	      UPModal.setModal({
	         title: '2FA - Activado',
	         body: 'Guarda estos códigos en un lugar seguro, ya que si el código OPT(2FA) no funciona o lo has perdido, puedes usar estos códigos para acceder.<br><h4>Nuevos Tokens:</h4>' + app,
	         buttons: {
	            confirmTxt: 'Listo',
	            confirmAction: 'close',
	         }
	      });
	   } else {
	      UPModal.alert('Lo siento', message, false);
	   }
	}, 'json')
};