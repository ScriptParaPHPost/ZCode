const login = (() => {
	'use strict';

	const TYPE_OF_DATA = {
		password: 'Recuperar Contraseña',
		validation: 'Reenviar validación'
	};

	const apiUrl = ZCodeApp.url;

	function multiOptions(actionType = '', confirmed = false) {
		if (!confirmed) {
			return UPModal.setModal({
				title: TYPE_OF_DATA[actionType],
				input: {
					label: 'Correo electrónico',
					type: 'email',
					name: 'r_email',
					maxlength: 35,
					placeholder: 'jhondoe@example.com',
					required: true
				},
				buttons: {
					confirmAction: `javascript:login.multiOptions('${actionType}', true)`,
					cancelShow: true
				}
			});
		}

		const page = actionType === 'password' ? 'pass' : 'validation';
		const email = $('#r_email').val();

		if (!email) return;

		UPModal.proccess_start();

		$.post(`${apiUrl}/recover-${page}.php`, { r_email: email }, response => {
			const success = response.charAt(0) !== '0';
			const message = response.substring(3);
			UPModal.proccess_end(2);
			UPModal.setModal({
				title: success ? 'Hecho' : 'Oops!',
				body: message,
				buttons: {
					confirmAction: 'close',
					cancelShow: false
				}
			});
		});
	}

	function comprobar(id, encode = false) {
		const input = $(`#${id}`);
		const value = input.val();

		if (!value) {
			input.focus();
			return '';
		}

		input.on('keyup', () => input.closest('div').find('small.help').html(''));
		return encode ? encodeURIComponent(value) : value;
	}

	function mostrarError(inputId, msg) {
		$(`#${inputId}`).closest('div').find('small.help').addClass('error').html(msg);
	}

	function btnLoad(loading = false) {
		$('.upform-buttons input[type="submit"]').val(loading ? 'Iniciando sesión...' : 'Iniciar sesión');
	}

	function iniciarSesionFail() {
		UPModal.alert('Error', 'Error al intentar procesar lo solicitado', false);
	}

	function comprobarOPT(params) {
		const code = $('input[name="one_password_time"]').val();
		const csrf = $('input[name=csrf_token]').val();

		if (!code) {
			UPModal.alert('Oops', 'No has ingresado el código', false);
			return;
		}

		params += `&code=${code}&csrf_token=${csrf}`;

		$.post(`${apiUrl}/login-validar.php`, params, response => {
			const code = parseInt(response.charAt(0));
			const message = response.substring(3);
			code === 1 ? location.reload() : UPModal.alert('Oops', message, false);
		});
	}

	function iniciarSesion() {
		const params = [
			`nick=${comprobar('nick', true)}`,
			`pass=${comprobar('password', true)}`,
			`rem=${$('#remember').is(':checked')}`,
			`csrf_token=${$('input[name=csrf_token]').val()}`
		].join('&');

		btnLoad(true);
		loading.start();

		$.post(`${apiUrl}/login-user.php`, params, response => {
			const status = parseInt(response.charAt(0));
			const message = response.substring(3);

			switch (status) {
				case 0:
				case 2:
					mostrarError('nick', message);
					$('#nick').focus();
					break;
				case 3:
					UPModal.alert('Ups!', message, false);
					break;
				case 4:
					UPModal.proccess_end(2);
					UPModal.setModal({
						input: {
							label: 'Código 2FA (OTP)',
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
					break;
				case 1:
					location.reload();
					break;
			}
			btnLoad(false);
			loading.end();
		})
		.fail(iniciarSesionFail)
		.done(() => $('#loading').fadeOut(350));
	}

	function togglePasswordVisibility() {
		const toggleBtn = $('#IWantSeePassword');
		const passwordInput = $('input[type="password"], input[type="text"]').first();

		toggleBtn.on('click', () => {
			const isVisible = passwordInput.attr('type') === 'text';
			passwordInput.attr('type', isVisible ? 'password' : 'text');
			toggleBtn
				.toggleClass('unlock lock')
				.attr('data-title', isVisible ? 'Ver contraseña' : 'Ocultar contraseña');
		});
	}

	// Exponer funciones públicas
	return {
		multiOptions,
		comprobarOPT,
		constrasena: togglePasswordVisibility,
		iniciarSesion
	};

})();

// Eventos
$(function () {
	$('form input[type="submit"]').on('click', e => {
		e.preventDefault();
		login.iniciarSesion();
	});

	$(document).on('keydown', e => {
		if (e.key === 'Enter' && typeof TYPE_LOAD !== 'undefined' && TYPE_LOAD === 'modal') {
			login.iniciarSesion();
		}
	});

	$('span[data-toggle="forget_password"]').on('click', () => login.multiOptions('password', false));

	login.constrasena();
});
