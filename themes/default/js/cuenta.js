function desactivate(start = 0) {
	imported('cuenta/desactivate.js', 'desactivate', { start });
}
const cuenta = {
	generarContrasena() {
		let inputPasswords = ['new_passwd', 'confirm_passwd'];
		let createNewPass = UPPassword.generate(16)
		inputPasswords.map( inp => $(`#${inp}`).attr({ type: 'text' }).val(createNewPass));
	},
	paisProvicias() {
		// Campo pais
		const pais_code = $("select[name=pais]").val();
		const estado = $("select[name=estado]");
		if (empty(pais_code)) estado.addClass('disabled').attr('disabled', 'disabled').val('');
		else {
			//Obtengo las estados
			$(estado).html('');
			loading.start();
			$.get(ZCodeApp.url + '/registro-geo.php', { pais_code }, req => {
				const pMsg = req.substring(3);
				let pNumb = parseInt(req.charAt(0)) === 1;
				if (pNumb) estado.append(pMsg).removeAttr('disabled').val('').focus();
				loading.end();
			})
		}
	},
	sameToast(page, param, fn) {
		$.post(`${ZCodeApp.url}/cuenta-${page}.php`, param, req => {
			let type_msg = parseInt(req.charAt(0));
			toast.start({ content: req.substring(3), type: (type_msg === 0 ? 'warning' : 'success') });
			loading.end();
			if(type_msg === 1 && typeof fn === 'function') {
				fn();
			}
		});
	},
	avatar(name) {
		loading.start();
		let active = (name === 'web') ? 0 : 1;
		this.sameToast('avatar-social', { name, active }, () => setTimeout(() => location.reload(), 1000));
	},
	guardar_datos() {
		loading.start();
		this.sameToast('guardar', $("form[name=editarcuenta]").serialize());
	},
	eliminar_cuenta(obj) {
		let outtime_type = parseInt($(obj).val());
		$.post(`${ZCodeApp.url}/cuenta-eliminar-tiempo.php`, { outtime_type }, req => {
			toast.start({ content: req.substring(3), type: (req.charAt(0) === '0' ? 'warning' : 'success') });
		});
	}
}

function desvincular(social) {
	$.post(`${ZCodeApp.url}/cuenta-desvincular.php`, { social }, req => {
		if(req) {
			UPModal.setModal({
				title: 'Bien',
				body: 'Ha sido desvinculado correctamente.',
				buttons: {
					confirmTxt: 'Listo',
					confirmAction: 'location.reload()',
				}
			});
		}
	});
}

$(document).ready(() => {
   // Event listener for avatar gif
	if ($('input[name="pagina"]').val() === 'avatar') {
      imported('cuenta/avatar.js', 'updateAvatarGif');
    	imported('cuenta/avatar.js', 'changeAvatar');
   }

   if ($('input[name="pagina"]').val() === 'apariencia') {
   	imported('cuenta/apariencia.js', 'syncThemeSystem');
   	imported('cuenta/apariencia.js', 'syncThemeColor');
   	imported('cuenta/apariencia.js', 'syncThemeFont');
   	if (!$('.customizar_tema').hasClass('d-none')) {
   		imported('cuenta/customizar.js', 'handleChangeColor');
   	}
   }

	// Tiene el mismo efecto que input[name="desktop"]
	$('.avatar-big-cont').on('click', () =>$('input.browse[name="desktop"]').click());
	//
	$('input.browse[name="desktop"]').on('change', function() {
		const $elemento = $(this);
		const tipo = $elemento.attr('name');
		if (this.files && this.files.length > 0) {
			const { name } = this.files[0];
			$elemento.next('.upform-file-text').html(name);
			$('#message_image').html('');
			avatar.subir(tipo);
			$('.avatar-loading').show();
		}
	});
	//
	if ($('.verify').length > 0) {
	  	$('.verify').on('click', function() {
	  		$('#message_image').html('');
	  		avatar.subir('url');
			$('.avatar-loading').show();
	  	});
	}
	//

	if ($('input[name="pagina"]').val() === 'seguridad') {
    	imported('cuenta/security.js', 'TFactorAuthSecurity');
    	if($('.remove_2fa').length > 0) imported('cuenta/TFactorAuth.js', 'twoFactorAuthRemove');
		if($('.regenerate_token').length > 0) {
			$('.regenerate_token').on('click', () => imported('cuenta/TFactorAuth.js', 'tokenRegenerate'));
		}
	}

});