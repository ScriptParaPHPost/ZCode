{if !$tsCookieConsent}
<link rel="stylesheet" href="{$tsConfig.assets}/css/cookieconsent.min.css">
<script type="module">
	const { url } = ZCodeApp;
	import '{$tsConfig.assets}/js/cookieconsent.umd.min.js';
	//document.documentElement.classList.add('cc--darkmode');
	let options = {
		guiOptions: {
	      consentModal: {
	         layout: "bar inline",
	         position: "bottom left",
	         equalWeightButtons: true,
	         flipButtons: false
	      },
	      preferencesModal: {
	         layout: "bar wide",
	         position: "left",
	         equalWeightButtons: false,
	         flipButtons: true
	      }
	   }
	}

	let categories = {
		categories: {
	  	   necessary: {
	         enabled: true,
	  	      readOnly: true
	  	   },
	  	   functionality: {
	         enabled: true
	      }
	  	}
	}

	let boxModal = {
	   title: "Hola {$tsUser->nick}, es la hora de las galletas!",
	   description: "Este sitio web utiliza cookies estrictamente necesarias y opcionalmente de rendimiento y funcionalidad. No se utilizarán cookies de publicidad, marketing o de terceros.",
	   acceptAllBtn: "Aceptar todo",
	   acceptNecessaryBtn: "Rechazar todo",
	   showPreferencesBtn: "Gestionar preferencias",
	   footer: '<a href="'+url+'/pages/privacidad/">Política de privacidad</a><a href="'+url+'/pages/terminos-y-condiciones/">Términos y condiciones</a>'
	};

	let preferences = {
		title: "Preferencias de Consentimiento",
	   acceptAllBtn: "Aceptar todo",
	   acceptNecessaryBtn: "Rechazar todo",
	   savePreferencesBtn: "Guardar preferencias",
	   closeIconLabel: "Cerrar modal",
	   serviceCounterLabel: "Servicios",
	   sections: [
	      {
	         title: "Uso de Cookies",
	         description: "Las cookies consent se utilizan para cumplir con las normativas de privacidad, como el RGPD en la Unión Europea, que requiere el consentimiento explícito de los usuarios para almacenar cookies en sus dispositivos."
	      }, {
	         title: "Cookies Estrictamente Necesarias <span class=\"pm__badge\">Siempre Habilitado</span>",
	         description: "Permiten el funcionamiento básico del sitio web, como la navegación, el inicio de sesión, y el acceso a áreas seguras.<br>Son esenciales para el funcionamiento del sitio y no se puede cambiar.",
	         linkedCategory: "necessary"
	      }, {
	         title: "Cookies de Funcionalidad",
	         description: "Permiten recordar las preferencias del usuario (idioma, región, color, etc) y ofrecer funciones mejoradas.<br>Pero no son esenciales para el funcionamiento básico del sitio.",
	         linkedCategory: "functionality"
	      }, {
	         title: "Más información",
	         description: "Para cualquier consulta en relación con mi política de cookies y sus opciones, por favor <a class=\"cc__link\" href=\"mailto:{$tsConfig.email}\">contactame</a>."
	      }
	   ]
	};

	let language = {
		language: {
	      default: "es",
	      translations: {
	         es: {
	            consentModal: boxModal,
	            preferencesModal: preferences
	         }
	      }
	   }
	};

	CookieConsent.run({
	   ...options,
	  	...categories,
	  	...language
	});
</script>
{/if}