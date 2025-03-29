function desactivate(start = 0) {
	iModule('DesactivarCuenta.js', 'desactivate', { start });
}

function generarContrasena() {
	iModule('Contrasena.js', 'generatePassword');
}

const cuenta = {
	paisProvicias() {
		// Campo pais
		const pais_code = $("select[name=pais]").val();
		const estado = $("select[name=estado]");
		if (empty(pais_code)) estado.addClass('disabled').attr('disabled', 'disabled').val('');
		else {
			//Obtengo las estados
			$(estado).html('');
			loading.start();
			$.get(basePath + '/registro-geo.php', { pais_code }, req => {
				const pMsg = req.substring(3);
				let pNumb = parseInt(req.charAt(0)) === 1;
				if (pNumb) estado.append(pMsg).removeAttr('disabled').val('').focus();
				loading.end();
			})
		}
	},
	sameToast(page, param, fn) {
		$.post(`${basePath}/cuenta-${page}.php`, param, req => {
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
		$.post(`${basePath}/cuenta-eliminar-tiempo.php`, { outtime_type }, req => {
			toast.start({ content: req.substring(3), type: (req.charAt(0) === '0' ? 'warning' : 'success') });
		});
	}
}

function desvincular(social) {
	$.post(`${basePath}/cuenta-desvincular.php`, { social }, req => {
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
      iModule('Avatar.js', ['updateAvatarGif', 'changeAvatar', 'deleteAvatar']);
   }

   if ($('input[name="pagina"]').val() === 'apariencia') {
   	iModule('Apariencia.js', ['syncThemeSystem', 'syncThemeColor', 'syncThemeFont', 'syncThemePageBox']);
   	if (!$('.customizar_tema').hasClass('d-none')) {
   		iModule('Customizar.js', 'handleChangeColor');
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
    	iModule('Seguridad.js', 'TFactorAuthSecurity');
    	if($('.remove_2fa').length > 0) iModule('Authenticator.js', 'twoFactorAuthRemove');
		if($('.regenerate_token').length > 0) {
			$('.regenerate_token').on('click', () => iModule('Authenticator.js', 'tokenRegenerate'));
		}
	}

});