const login = (() => {
	'use strict';

	const TYPE_OF_DATA = {
		password: 'Recuperar Contrase&ntilde;a',
		validation: 'Reenviar validaci&oacute;n'
	}

	function multiOptions(type_from_action = '', type_status = false) {
		if(!type_status) {
			UPModal.setModal({
				title: TYPE_OF_DATA[type_from_action],
				input: {
					label: 'Correo electr&oacute;nico',
					type: 'email',
					name: 'r_email',
					maxlength: 35,
					placeholder: 'jhondoe@example.com',
					required: true
				},
				buttons: {
					confirmAction: `javascript:login.multiOptions('${type_from_action}', true)`,
					cancelShow: true
				}
			});
		} else {
			const page = (type_from_action === 'password') ? 'pass' : 'validation';
			const r_email = $('#r_email').val();
			UPModal.proccess_start();
			$.post(ZCodeApp.url + '/recover-'+page+'.php', { r_email }, receive => {
				UPModal.proccess_end(2);
				UPModal.setModal({
					status: (receive.charAt(0) == '0' ? 'danger' : 'success'),
					title: (receive.charAt(0) == '0' ? 'Opps!' : 'Hecho'),
					body: receive.substring(3),
					buttons: {
						confirmAction: `close`,
						cancelShow: false
					}
				});
			})
		}
	}

	const comprobar = (VERIFY_ID, VERIFY_ENCODE = false) => {
		const GET_VERIFY_INPUT = $('input#' + VERIFY_ID);
    	if (GET_VERIFY_INPUT.val() === '') {
        	GET_VERIFY_INPUT.focus();
       	return true;
    	}
		GET_VERIFY_INPUT.on('keyup', () => GET_VERIFY_INPUT.parent().parent().find('small.help').html(''));
		return VERIFY_ENCODE ? encodeURIComponent(GET_VERIFY_INPUT.val()) : GET_VERIFY_INPUT;
	}

	function mostrarError(SHOW_ERROR_ID, SHOW_ERROR_MSG) {
		$(`#${SHOW_ERROR_ID}`).parent().parent().find('small.help').addClass('error').html(SHOW_ERROR_MSG);
	}

	function btnLoad(action = false) {
		const TXT_ACTION = action ? 'Iniciando sesión...' : 'Iniciar sesión';
		$('.upform-buttons input[type="submit"]').attr({ value: TXT_ACTION });
	}

	function iniciarSesionFail() {
		UPModal.alert('Error', 'Error al intentar procesar lo solicitado', false);
	}

	function comprobarOPT(params) {
		params += '&code=' + $('input[name="one_password_time"]').val();
		$.post(`${ZCodeApp.url}/login-validar.php`, params, req => {
			let INPUT_NUMBER = parseInt(req.charAt(0));
			const CONTENT_SHOW = req.substring(3);
			let CONTENT_TITLE = (INPUT_NUMBER === 1) ? 'Bien' : 'Oops';
			UPModal.alert(CONTENT_TITLE, CONTENT_SHOW, (INPUT_NUMBER === 1));
		});
	}

	const iniciarSesion = () => {
	
		let params = [
			'nick=' + comprobar('nick', true),
			'pass=' + comprobar('password', true),
			'rem=' + $('#remember').is(':checked'),
			
		].join('&');
		btnLoad(true);
		
		loading.start()
		$.post(ZCodeApp.url + '/login-user.php', params, response => {
			let INPUT_NUMBER = parseInt(response.charAt(0));
			if(INPUT_NUMBER === 0 || INPUT_NUMBER === 2 || INPUT_NUMBER === 3) {
				const CONTENT_SHOW = response.substring(3);
				if(INPUT_NUMBER === 3) {
					UPModal.alert('Ups!', CONTENT_SHOW, false);
				} else {
					let TYPE_INPUT_EXECUTE = (INPUT_NUMBER === 0) ? 'nick' : 'password';
					mostrarError(TYPE_INPUT_EXECUTE, CONTENT_SHOW);
					$(`#${TYPE_INPUT_EXECUTE}`).focus();
				}
				btnLoad();
			} else if (INPUT_NUMBER === 4) {
				UPModal.proccess_end(2);
				UPModal.setModal({
					input: {
						label: 'Código 2FA (OPT)',
						type: 'text',
						name: 'one_password_time',
						maxlength: 11,
						placeholder: '000000',
						required: true,
						inputmode: 'numeric'
					},
					buttons: {
						confirmAction: `login.comprobarOPT('${params}')`,
						cancelShow: false
					}
				});
			}
			if(INPUT_NUMBER === 1) location.reload();
			loading.end();
		})
		.fail(() => iniciarSesionFail())
		.done(() => $('#loading').fadeOut(350))
	}

	function showhidePassword() {
		const divID = $("#IWantSeePassword");
		const inputPassword = $('input[type="password"]');
		divID.on('click', () => {
			let set = divID.attr('class');
			const compare = 'iconify unlock';
			if(set === compare) {
				divID.removeClass(set).addClass('iconify lock').attr({ 'data-title': 'Ocultar contraseña' });
				inputPassword.attr({ type: 'text' })
			} else {
				divID.removeClass(set).addClass(compare).attr({ 'data-title': 'Ver contraseña' });
				inputPassword.attr({ type: 'password' })
			}
		})
	}

	return {
		comprobarOPT: comprobarOPT,
		multiOptions: multiOptions,
		constrasena: showhidePassword,
		iniciarSesion: iniciarSesion
	}

})();

// Asignar evento submit al formulario de login
$('form input[type="submit"]').on('click', function(e) {
   e.preventDefault();
   login.iniciarSesion();
});

$('span[data-toggle="forget_password"]').on('click', function() {
	login.multiOptions('password', false);
});

login.constrasena();