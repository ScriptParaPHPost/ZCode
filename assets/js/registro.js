const registro = (() => {
	'use strict';

	// Variable para almacenar el mensaje de validación
	let message = '';

	// Clases CSS utilizadas para el manejo de mensajes de validación
	const classRemove = 'error ok loading info';

	const iWantPassword = $("#IWantSeePassword");
	const inputPassword = $('input[type="password"]');

	// Función para construir la URL para las solicitudes AJAX
	const up = {
		post: async function(page, data) {
			return await $.post(`${ZCodeApp.url}/registro-${page}.php?ajax=true`, data);
		}
	}

	// Actualice el texto y el color según la seguridad de la contraseña
	let colorLevelPass = { 
		0: 'gray', 
		1: 'green', 
		2: '#E5B91E', 
		3: '#E5521E', 
		4: '#CD1B1B'
	}
	let textLevelPass = { 
		0: 'Muy fácil', 
		1: 'Fácil', 
		2: 'Medio', 
		3: 'Difícil', 
		4: 'Extremadamente difícil' 
	}

	// Patrones de expresiones regulares para validar campos
	let expresiones = {
		nick: /^[a-zA-Z0-9\_\-]{4,20}$/,
		password: /^.{4,32}$/,
		email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
	}

	// Estado de aprobación de cada campo después de la validación
	let Approved = {
		nick: false,
		password: false,
		email: false,
		terminos: false
	}

	// Función para mostrar mensajes de validación
	function displayMessage(object, msg, type) {
      // Separar las clases CSS
		let status = classRemove.split(' ');
      // Obtener la clase correspondiente según el tipo
		let appendClass = status[parseInt(type)];
      // Actualizar el mensaje y la clase del elemento padre
		$(object).parent().parent().find('.help').removeClass(classRemove).addClass(appendClass).html(msg);
		// Devolver true si el tipo es 1, de lo contrario, false
		return (parseInt(type) === 1) ? true : false;
	}

	// Función para validar campos
	function validateField(nameEl, response) {
	   let valueOfText = $('#' + nameEl).val();
	   let verifyRegex = expresiones[(nameEl === 'password2' ? 'password' : nameEl)].test(valueOfText);
	   let number = response.charAt(0);
	   // Mostrar mensaje de validación y devolver true si el tipo es 1, de lo contrario, false
	   return verifyRegex ? displayMessage('#' + nameEl, response.substring(3), number) : false;
	}

	function checkStrength(password, nameEl) {
	   let strength = 0;
	   // Comprobar la longitud de la contraseña
	   if (password.length > 8) strength += 1;
	   // Verifique si hay casos mixtos
	   if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
	   // Comprobar los números
	   if (password.match(/\d/)) strength += 1;
	   // Comprobar caracteres especiales
	   if (password.match(/[^a-zA-Z\d]/)) strength += 1;
	   // Limpiamos el css
	   $('#password-strength span').removeAttr('style')
	  	$('#password-strength span').css({ backgroundColor: colorLevelPass[strength],  })
	  	$('#password-strength em').html(textLevelPass[strength]);
	}

	// Función para validar el campo Nick
   function validarNick(inputNameElement, inputValue, inputIDElement) {
      if(inputNameElement === 'nick' && inputValue.length <= 4) {
			displayMessage(inputIDElement, `Debes ser mayor a 4 caracteres`, 3);
		} else if(inputNameElement === 'nick' && inputValue.length >= 20) {
			displayMessage(inputIDElement, `Debes ser menor a 20 caracteres`, 3);
		} else displayMessage(inputIDElement, `Comprobando ${inputNameElement}...`, 2);
		up.post('check-nick', { nick: inputValue }).then(response => {
			Approved[inputNameElement] = validateField(inputNameElement, response)
		});
   };

   // Función para validar el campo Email
   function validarEmail(inputNameElement, inputValue, inputIDElement) {
      displayMessage(inputIDElement, `Comprobando ${inputNameElement}...`, 2);
		up.post('check-email', { email: inputValue }).then(response => {
			Approved[inputNameElement] = validateField(inputNameElement, response)
		});
   };

   // Función para validar el campo Contraseña
   function validarContrasena(inputNameElement, inputIDElement) {
      displayMessage(inputIDElement, `Comprobando constraseña...`, 2);
		const valueOfPassword = $("#password").val();
		const valueOfNick = $("#nick").val();
		if(inputNameElement === 'password') {
			checkStrength(valueOfPassword, inputNameElement)
			message = (valueOfPassword === valueOfNick) ? '0: No puede ser igual al Nick' : '1: ';
		}
		Approved[inputNameElement] = validateField(inputNameElement, message)
   };

	function verificarCampo(element) {
		console.log(element)
		const inputNameElement = $(element)[0].name;
		const inputIDElement = `#${$(element)[0].id}`;
		let inputValue = $(inputIDElement).val();
		// Realizamos las comprobaciones con la Base de datos
		switch (inputNameElement) {
			case 'nick':
            validarNick(inputNameElement, inputValue, inputIDElement);
			break;
			case 'email':
				validarEmail(inputNameElement, inputValue, inputIDElement);
			break;
			case 'password':
				validarContrasena(inputNameElement, inputIDElement);
			break;
			case 'terminos':
				let isChecked = $(inputIDElement).prop('checked');
				displayMessage(inputIDElement, (isChecked ? '' : 'Debes aceptar los terminos'), !isChecked);
				Approved[inputNameElement] = isChecked;
			break;
		}
	}

	function sonTodosVerdaderos(obj) {
	  	for (var prop in obj) {
	    	if (!obj[prop]) return false;
	  	}
	  	return true;
	}

	function btnLoad(action = false) {
		const TXT_ACTION = action ? 'Creando nueva cuenta..' : 'Crear cuenta';
		$('.upform-buttons input[type="submit"]').attr({ value: TXT_ACTION });
	}

	function crearCuenta() {
		// No continua si no esta todo aprovado (excepto el sexo)
		if(sonTodosVerdaderos(Approved)) {
			btnLoad(true);
			const formulario = $('form').serialize();
			UPModal.proccess_start('Estamos procesando...');
			up.post('nuevo', formulario).then(response => {
				let numberAction = parseInt(response.charAt(0));
				let messageAction = response.substring(3);
		      UPModal.proccess_end();
				if(numberAction === 0) {
					UPModal.alert('Error', messageAction, false);
					btnLoad();
				}
				if(numberAction === 1 || numberAction === 2) {
					UPModal.setModal({
			      	status: 'success',
			      	title: 'Registro completado',
			      	body: messageAction,
			      	buttons: {
			      		confirmShow: true,
			      		confirmAction: `registro.redireccionar(${numberAction})`,
			      		cancelShow: false
			      	}
			      });
				}
			});
		}
		
	}
	function redireccionar(type = 0) {
	   location.href = ZCodeApp.url + '/' + (parseInt(type) === 2 ? 'cuenta/' : '');
	}

	function showhidePassword() {
		iWantPassword.on('click', () => {
			let set = iWantPassword.attr('class');
			const compare = 'iconify unlock';
			iWantPassword.removeClass(set).addClass((set === compare ? 'iconify lock' : compare) );
			inputPassword.attr({ type: (set === compare ? 'text' : 'password') })
		})
	}

	function generarContrasena() {
		iWantPassword.removeClass('iconify unlock').addClass('iconify lock');
		inputPassword.attr({ type: 'text' }).val(UPPassword.generate(12));
		inputPassword.focus();
		checkStrength(inputPassword.val(), 'password')
	}

	// Exportamos para la utilizarlo
	return {
		crearCuenta: crearCuenta,
		verificar: verificarCampo,
		constrasena: showhidePassword,
		redireccionar: redireccionar,
		generar: generarContrasena
	}

})();

// Asignar evento blur y keyup al formulario de registro
$('form').on('blur keyup', 'input', function() {
   registro.verificar(this);
});

// Asignar evento change para inputs tipo radio y checkbox
$('form').on('change', 'input[type="checkbox"]', function() {
   registro.verificar(this);
});

// Asignar evento submit al formulario de registro
$('form').submit(function(e) {
   e.preventDefault();
   registro.crearCuenta();
});

// Para generar contraseña aleatoria
$('#generar').on('click', () => registro.generar())

// Auto-ejecutar nivel de seguridad en contraseña
registro.constrasena();