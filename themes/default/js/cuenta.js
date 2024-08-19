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
	guardar_datos() {
		loading.start();
		$.post(ZCodeApp.url + '/cuenta-guardar.php', $("form[name=editarcuenta]").serialize(), response => {
			toast.start({ content: response, type: 'warning' });
			loading.end();
		})
	},
	eliminar_cuenta(obj) {
		let outtime_type = parseInt($(obj).val());
		$.post(`${ZCodeApp.url}/cuenta-eliminar-tiempo.php`, { outtime_type }, req => {
			toast.start({ content: req.substring(3), type: 'warning' });
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

function startCountdown() {
   let countdown = 30; // Duración del contador en segundos
   let interval = setInterval(function() {
      countdown--; // Decrementa el contador
      $("#countdown").text(`${countdown}s`); // Actualiza el texto del contador

      if (countdown <= 0) {
         clearInterval(interval); // Detiene el contador cuando llega a 0
         $('#regenerate').html(''); // Limpiamos
         // Realiza una llamada AJAX usando $.post
         $.post(`${ZCodeApp.url}/cuenta-qr-regenerate.php`, response => $('#regenerate').html(response));
         // Reinicia el contador
         startCountdown();
      }
   }, 1000); // Intervalo de 1 segundo (1000 ms)
}

$(document).ready(() => {
   // Event listener for avatar gif
   if ($('input[name="avatar_active"]').length > 0) {
      $('input[name="avatar_active"]').on('click', () => {
         imported('cuenta/avatar-gif-active.js', 'updateAvatarGif', {});
      });
   }
   // Event listeners for color and scheme inputs
   ['color', 'scheme'].forEach(name => {
      const select = $(`select[name="${name}"]`);
      if (select.length > 0) {
         select.on('change', function() {
         	const selected = parseInt($(this).val());
         	imported('cuenta/apariencia.js', 'ColorScheme', { name, selected });
         });
      }
   });
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
	if($('.remove_2fa').length > 0) imported('cuenta/TFactorAuth.js', 'twoFactorAuthRemove', {});
	if($('.regenerate_token').length > 0) {
		$('.regenerate_token').on('click', () => imported('cuenta/TFactorAuth.js', 'tokenRegenerate', {}));
	}
	if($('small#countdown').length > 0) {
      startCountdown();
		$.post(`${ZCodeApp.url}/cuenta-qr-regenerate.php`, function(response) {
         $('#regenerate').html(response); // Mostramos
      });
	}
	//
	if ($('input[name="pagina"]').val() === 'apariencia') {
    	imported('cuenta/apariencia.js', 'changeAvatar', {});

    	const selectedValue = $('select[name="color"]');
    	const toggleCustomizerTheme = value => $('.customizar_tema').toggleClass('d-none', parseInt(value) !== 0);
    	selectedValue.on('change', function() {
        	toggleCustomizerTheme($(this).val());
    	});
    	toggleCustomizerTheme(selectedValue.val());
    	imported('cuenta/customizar.js', 'handleChangeColor', {});
	}

});