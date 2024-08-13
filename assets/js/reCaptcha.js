$(() => {

   // Desestructuramos las propiedades `pkey` y `url` del objeto `ZCodeApp`
	const { pkey, url } = ZCodeApp;

	// Clave de la API reCAPTCHA
	const API_RECAPTCHA = pkey;

	// URL para cargar el script de reCAPTCHA de Google con la clave de la API
	const API_RECAPTCHA_GOOGLE = `https://www.google.com/recaptcha/api.js?render=${API_RECAPTCHA}`;

	// Opciones para la ejecución de reCAPTCHA
	const RECAPTCHA_OPTION = { action: 'submit' }

	// URL del archivo de registro
	const FILE_REGISTER_WEB = `${url}/assets/js/registro.js`;

	// Función para cargar un script externo
	function loadScript(url) {
		return new Promise((resolve, reject) => $.getScript(url).done(resolve).fail(reject));
	}

	// Función para manejar errores al cargar scripts
	function reCaptchaError(error) {
		console.error('Error cargando scripts:', error)
	}

	// Función que prepara reCAPTCHA
	async function reCaptchaReady() {
		return await grecaptcha.ready(async () => await reCaptchaExecute());
	}

	// Función que ejecuta reCAPTCHA y obtiene el token
	async function reCaptchaExecute() {
		const token = await grecaptcha.execute(API_RECAPTCHA, RECAPTCHA_OPTION);
		// Añadimos el token al campo response = response.value = token;
		$('input[name="response"]').val(token);
      // Quitamos el disabled
      $('form').removeAttr('disabled');
	}

	// Función autoinvocada para cargar y ejecutar los scripts necesarios
   try {
      // Cargar el script de reCAPTCHA
      loadScript(API_RECAPTCHA_GOOGLE).then(() => {
      	// Preparar y ejecutar reCAPTCHA
      	reCaptchaReady();
      	// Cargar el script de registro
      	//loadScript(FILE_REGISTER_WEB);
      });
   } catch (error) {
      // Manejar errores de carga
      reCaptchaError(error);
   }

});