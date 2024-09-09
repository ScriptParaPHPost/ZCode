function string_random(random_char_size = 10) {
	const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	let resultado = '';
	const caracteresLength = caracteres.length;
	for (let i = 0; i < random_char_size; i++) {
		resultado += caracteres.charAt(Math.floor(Math.random() * caracteresLength));
	}
	return resultado;
}

/**
 * Imported v1.2
 * Lo que hace es cargar el código cuando es necesario
 * y que no se cargue cuando no se usa
 * Ahora verifica si ya fue importado
*/
const importedFiles = new Map();
async function imported(fileroute = '', execFunction = '', objects, from = 'theme') {
	let javascriptFile;
	try {
		const { theme, assets } = ZCodeApp;
		javascriptFile = `${ZCodeApp[from]}/js/${fileroute}?v` + string_random(4);
		if (importedFiles.has(javascriptFile)) {
			console.log(`Archivo ${javascriptFile} ya ha sido importado.`);
			return;
		}
		const module = await import(javascriptFile);
		importedFiles.set(javascriptFile, true);
		if (module[execFunction]) {
			module[execFunction](objects);
		} else {
			console.error(`Function ${execFunction} no se encontro en ${javascriptFile}`);
		}
	} catch (error) {
		console.error(`Error al importar ${javascriptFile}:`, error);
	}
}
/**
 * Plugins globales que utilizará el script.
 * Los plugins: (fueron obtenidos desde https://locutus.io/php/)
 * @link https://locutus.io/php/var/empty/ | empty
 * @link https://locutus.io/php/strings/htmlspecialchars_decode/ | htmlspecialchars_decode
 * @link https://locutus.io/php/strings/number_format/ | number_format
 * @link https://locutus.io/php/url/rawurlencode/ | rawurlencode
 * @link https://locutus.io/php/url/base64_encode/ | base64_encode
*/
empty = n => {let e,r,t;const f=[undefined,null,!1,0,"","0"];for(r=0,t=f.length;r<t;r++)if(n===f[r])return!0;if("object"==typeof n){for(e in n)if(n.hasOwnProperty(e))return!1;return!0}return!1}
htmlspecialchars_decode = (e,E) => {let T=0,_=0,t=!1;void 0===E&&(E=2),e=e.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");const c={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(0===E&&(t=!0),"number"!=typeof E){for(E=[].concat(E),_=0;_<E.length;_++)0===c[E[_]]?t=!0:c[E[_]]&&(T|=c[E[_]]);E=T}return E&c.ENT_HTML_QUOTE_SINGLE&&(e=e.replace(/&#0*39;/g,"'")),t||(e=e.replace(/&quot;/g,'"')),e=e.replace(/&amp;/g,"&")}
number_format = (e,t,n,i) => {e=(e+"").replace(/[^0-9+\-Ee.]/g,"");const r=isFinite(+e)?+e:0,o=isFinite(+t)?Math.abs(t):0,a=void 0===i?",":i,d=void 0===n?".":n;let l="";return l=(o?function(e,t){if(-1===(""+e).indexOf("e"))return+(Math.round(e+"e+"+t)+"e-"+t);{const n=(""+e).split("e");let i="";return+n[1]+t>0&&(i="+"),(+(Math.round(+n[0]+"e"+i+(+n[1]+t))+"e-"+t)).toFixed(t)}}(r,o).toString():""+Math.round(r)).split("."),l[0].length>3&&(l[0]=l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,a)),(l[1]||"").length<o&&(l[1]=l[1]||"",l[1]+=new Array(o-l[1].length+1).join("0")),l.join(d)}
rawurlencode = string => {string = string + '';return encodeURIComponent(string).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');}
base64_encode = string => {let type="undefined"!=typeof window&&window.btoa;return type?window.btoa(unescape(encodeURIComponent(string))):Buffer.from(string,"binary").toString("base64");}

function isYoutube(linkVideo) {
	const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/; 
	const match = linkVideo.match(regExp); 
	return (match && match[7].length === 11) ? match[7] : false;
}

const verifyInput = (selector, message) => {
	const input = $(selector);
	let verify = (input.val().trim() !== '');
	const INPUTSELECT = input.parent().parent().find('.upform-status');
   const actions = {
   	true: {
   		statusClass: 'addClass',
   		statusMessage: message
   	},
   	false: {
   		statusClass: 'removeClass',
   		statusMessage: ''
   	}
   }
   INPUTSELECT[actions[!verify].statusClass]('error');
   INPUTSELECT.html(actions[!verify].statusMessage);
   if (!verify) input.focus();
	return verify;
};

$('input').on('keyup', function() {
	$(this).parent().parent().find('.upform-status').removeClass('error ok loading info').html('')
});


const toast = {
	createContainer() {
		if ($('.toast').length === 0) $('body').append('<div class="toast"></div>');
	},
	generateID(length = 5) {
	  	return Math.random().toString(36).substr(2, length);
	},
	createToastBox(gid, { title, content, type, autoClose, duration }) {
	  	const toastBox = $(`<div class="toast-box" gid="${gid}">
	      ${title ? `<div class="toast--title">${title}</div>` : ''}
	      ${content ? `<div class="toast--body">${content}</div>` : ''}
	      <div class="toast--close" ${autoClose ? 'style="display:none;"' : ''}>
	      	<span role="button" data-close="${gid}">&times;</span>
	      </div>
	   </div>`);
	   if (type) {
	   	toastBox.addClass(`toast-box--${type}`);
	  	}
	  	return toastBox;
	},
	attachCloseHandler(gid) {
	  	$(`[data-close="${gid}"]`).on('click', function() {
	    	$(this).closest('.toast-box').remove();
	    	if ($('.toast').children().length === 0) $('.toast').remove();
	  	});
	},
	start({ title = '', content = '', type = 'default', autoClose = true, duration = 5 }) {
	  	this.createContainer();
	  	const gid = this.generateID(8);
	  	const toastBox = this.createToastBox(gid, { title, content, type, autoClose, duration });
	  	$('.toast').append(toastBox);
	  	if (!autoClose) {
	    	this.attachCloseHandler(gid);
	  	} else {
	    	setTimeout(() => {
	      	$(`[gid="${gid}"]`).remove();
	      	if ($('.toast').children().length === 0) $('.toast').remove();
	    	}, duration * 1000);
	  	}
	}
};

const cookie = {
	days: 90, /** 90 Días **/
	create(name, value, expire = '', days = this.days) {
   	if (days) {
   	   let date = new Date();
   	   date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
   	   expires = "; expires=" + date.toUTCString();
   	}
   	document.cookie = `${name}=${value}${expires}; path=/`;
	},
	get(nameEQ) {
		nameEQ += '=';
   	let ca = document.cookie.split(';');
   	for (let i = 0; i < ca.length; i++) {
   	   let c = ca[i];
   	   while (c.charAt(0) == ' ') c = c.substring(1, c.length);
   	   if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
   	}
   	return null;
	}
}

const loading = {
	timeout: 350,
	start() {
		imported('loadingStart.js', 'loadingStart', {}, 'assets');
	},
	end() {
		setTimeout(() => $('#loading_start').remove(), this.timeout);
	}
}

const UPPassword = {
	size: 18,
	charset: "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&_~|}{[]?-=",
	generate: function(length = this.size) {
		return Array.from({ length }, () => this.charset[Math.floor(Math.random() * this.charset.length)]).join('');
	}
}

/**
 * UltraModal v4.0.0 - jQuery 3.7.1
 * @author Miguel92 - 2024
 * @status => success | danger | warning | default
 * @optional => icons
 * @link https://icon-sets.iconify.design/system-uicons/
*/
const UPModal = {
	show: false,
	default: {
		status: {
			empty: '',
			success: 'check-circle',
			danger: 'cross-circle',
			warning: 'warning-triangle',
			info: 'info-circle',
			question: 'question-circle',
			password: 'lock',
			email: 'mail',
			opt: 'fingerprint'
		},
		size: 'default',
		mask: false,
		close: true,
		scrolleable: false,
		folder: 'system-uicons'
	},
	buttons: {
		confirmShow: true,
		confirmTxt: 'Continuar',
		confirmClass: 'UPModal-button UPModal-button-success',
		confirmAction: 'close',
		cancelShow: false,
		cancelTxt: 'Cancelar',
		cancelClass: 'UPModal-button UPModal-button-outline',
		cancelAction: 'close'
	},
	template: `<div class="UPModal">
		<div class="UPModal-mask"></div>
		<div class="UPModal-dialog">
			<div class="UPModal-header">
				<div class="UPModal-icon"></div>
				<div class="UPModal-title"></div>
			</div>
			<div class="UPModal-content">
				<div class="UPModal-message"></div>
				<div class="UPModal-body"></div>
			</div>
			<div class="UPModal-buttons"></div>
		<div>
	</div>`,
	setModal({ icon = false, status, close, mask, size, title, body, buttons, input }) {
		if(typeof input === 'object') {
			body = this.setInput(input);
		}
		this.setModalInit();
		this.setModalStatus(icon, status || this.default.status.empty);
		this.setModalMask(mask || this.default.mask);
		this.setModalButtonClose(close || this.default.mask);
		this.setModalTitle(title);
		this.setModalBody(body);
		this.setButtons(buttons);
		this.setModalCenter();
		//
		$(window).on('resize', this.setModalCenter);
		// Tamaño
		$('.UPModal-dialog').addClass(`UPModal-dialog-${size || this.default.size}`);
	},
	close() {
		this.show = false;
		this.default.size = '';
		this.default.mask = false;
		this.default.close = true;
		this.default.scrolleable = false;
	
		$('.loader_modal').remove();
		$('body').css({ overflow: 'auto' });
	},
	setModalInit() {
		if(this.show) return;
		this.show = true;
		// Creamos el ID para el modal
		$('<div class="loader_modal"></div>').prependTo('body');
		$('.loader_modal').html(this.template);
	   $('body').css({ overflow: (this.default.scrolleable ? 'auto' : 'hidden') });
	},
	setModalStatus(icon, status) {
		if (status !== '') {
			$('.UPModal').attr('data-modal-status', status);
			if(icon) {
				this.setIconCreate(status);
			} else {
				$('.UPModal-icon').remove();
			}
		}
	},
	setIconCreate(status) {
	   const { assets } = ZCodeApp;
	   let icon = this.default.status[status];
	   const folder = this.default.folder;
	   const image = (folder === 'spinner') ? icon : (typeof icon == 'undefined' ? '' : icon.replace(/-/g, '_'));
	   $.getJSON(`${assets}/icons/${folder}.json`, data => {
	   	$('.UPModal-dialog').attr({ 'data-modal-icon': true });
	   	$('.UPModal-icon').html(data[image]);
	   });
	},
	setModalMask(close_with_mask) {
		if(close_with_mask === true) {
			$('.UPModal-mask').off('click').on('click', () => this.close());
		}
	},
	setModalButtonClose(close) {
		if(close || this.default.close) {
			$('.UPModal-title').before(`<div role="button" onclick="UPModal.close()" class="UPModal-close" data-modal-close="true">&times;</div>`);
		}
	},
	setModalCenter() {
      $('.UPModal-dialog').css({
         position: 'fixed',
         top: '50%',
         left: '50%',
         transform: 'translate(-50%, -50%)'
      });
	},
	setModalTitle(string) {
		$('.UPModal-title').html(string);
	},
	setModalBody(string) {
		$('.UPModal-body').html(string);
	},
	setButtons(buttons) {
		if (buttons) {
			const { 
				confirmShow, confirmTxt, confirmClass, confirmAction, 
				cancelShow, cancelTxt, cancelClass, cancelAction
			} = this.buttons = { ...this.buttons, ...buttons };
			// Añadiendo botones
			let buttonsHTML = '';
			if(confirmShow) {
				let myActionOK = (confirmAction === 'close' || empty(confirmAction)) ? 'UPModal.close()' : confirmAction;
				buttonsHTML += `<input type="button" role="button" onclick="${myActionOK}" value="${confirmTxt}" class="${confirmClass}">`;
			}
			if(cancelShow) {
				let myActionDeny = (cancelAction === 'close' || empty(cancelAction)) ? 'UPModal.close()' : cancelAction;
				buttonsHTML += `<input type="button" role="button" onclick="${myActionDeny}" value="${cancelTxt}" class="${cancelClass}">`;
			}
			$('.UPModal-buttons').html(buttonsHTML);
			if(this.size !== 'small') $('.UPModal-dialog').addClass('UPModal-lg');
		} else $('.UPModal-buttons').remove();
	},
	setInput({ label, type, name, placeholder, maxlength, id, required, inputmode }) {
		let isRequired = required ? ' required' : '';
		let appendIconForm = '';
		let isMax = ' maxlength="'+maxlength+'"';
		let isInputMode = !empty(inputmode) ? ' inputmode="'+inputmode+'"' : '';
		let iconType = !empty(inputmode) ? 'opt' : type;

		return `<div class="upform-group"><label class="upform-label" for="${name}">${label}</label><div class="upform-group-input"><input class="upform-input" type="${type}" name="${name}" id="${name}" placeholder="${placeholder}"${isMax}${isInputMode}${isRequired}></div></div>`;
	},
	alert(...args) {
    	let title, body, icon, status, reload, button;
		if(typeof args[0] === 'object') {
			({ title, body, icon = false, status = '', redirect: reload, button: buttons } = args[0]);
		} else if(typeof args[0] === 'string') {
        	// Asignación directa para el caso de los argumentos como string
        	[title, body, reload, button] = args;
        	({ icon = false, status = '', buttons: button } = args[3] || {});
		}
		this.setModal({ icon, status, title,  body, 
			buttons: {
				confirmShow: button,
				confirmTxt: 'Aceptar',
				confirmAction: `UPModal.close();${reload ? 'location.reload();' : ''}`,
				confirmClass: 'UPModal-button UPModal-button-' + (empty(status) ? 'success' : status)
			}
		});
		$('.UPModal-dialog').addClass('UPModal-alert');
	},
	error_500(retryAction) {
		setTimeout(() => {
			this.proccess_end();
			this.setModal({
				title: 'Error', 
				body: 'Error al intentar procesar lo solicitado', 
				buttons: {
					confirmShow: true,
					confirmTxt: (retryAction ? 'Reintentar' : 'Cerrar'),
					confirmAction: `UPModal.close();${retryAction || ''}`,
					cancelShow: true
				}
			});
		}, 300);
	},
	proccess_start(content = 'Espere, por favor', title = '') {
		if(!this.isShow) {
			this.setModal({
				status: 'default',
				title: '', 
				body: '', 
				buttons: { confirmShow: false }
			});
		}
		if(!empty(title)) {
			this.setModalTitle(title);
		}
		const { assets } = ZCodeApp;
	   $.getJSON(`${assets}/icons/spinner.json`, data => {
	   	$('.UPModal-proccess').append(`<div id="loaderstart">${data['3-dots-scale-middle']}</div>`);
	   });
		const processTemplate = `<div class="UPModal-proccess">${content}</div>`;
		$('.UPModal-message').append(processTemplate).fadeIn('fast');
		$('.UPModal-body, .UPModal-buttons').fadeOut();
	},
	proccess_end(timeout = 1){
		setTimeout(() => {
			$('.UPModal-body, .UPModal-buttons').fadeIn('fast');
			$('.UPModal-message').fadeOut('fast');
		}, timeout * 1000);
	}
};

$(document).on('keyup keydown', function(event) {
   if(event.type === 'keydown') {
   	// Tecla Esc
   	if(event.keyCode === 27 && event.code === 'Escape') {
   	 	UPModal.close();
   	}
   }
});

// Solicitar permiso para mostrar notificaciones
function requestNotificationPermission() {
	imported('notification.js', 'permission', {}, 'assets');
}
// Función para mostrar la notificación
function showNotification(title, body, icon = '', url = '') {
   if (Notification.permission === 'granted') {
      const notification = new Notification(title, {
         body: body,
         icon: icon
      });
      notification.onclick = function(event) {
         event.preventDefault(); // Previene el comportamiento predeterminado
         if (url) {
            window.open(url, '_self'); // Abre la URL en una nueva pestaña
         }
      };
   }
}

function decoded_email_protected() {
	const PM = $('#protected_mail');
	if ($('#protected_mail').length > 0) {
		const PMailkey = PM.data('key');
		const PMailpublic = PM.data('public');
		const PMOrder = PMailkey.split("").sort().join("");
		const keyMap = {};
		// Crear un mapa de búsqueda para mejorar la eficiencia
		for (let i = 0; i < PMailkey.length; i++) keyMap[PMailkey[i]] = PMOrder[i];
		// Decodificar el correo usando el mapa
		const EmailDecode = PMailpublic.split("").map(char => keyMap[char]).join("");
		PM.html(`<a href="mailto:${EmailDecode}">${EmailDecode}</a>`);
	}
}

$(() => {
	
	requestNotificationPermission();
	decoded_email_protected();
	
	if($('lite-youtube').length > 0) {
		imported('lite-youtube.js', 'liteYt', {}, 'assets');
	}
	// Ejecutamos LazyLoad - by Miguel92
	if (typeof LazyLoad !== 'undefined') {
	   const lazyLoadSelectors = ['img[src]', '[data-src]', '[data-bg]'];
	   const { images: { assets: publicImagesPath } } = ZCodeApp;

	   lazyLoadSelectors.forEach(selector => {
	      const commonLazyLoadOptions = {
	         elements_selector: selector,
	         class_loading: 'lazy-loading',
	         callback_error: element => {
	            $(element).attr("src", publicImagesPath + "/500-error.png");
	         }
	      };

	      let lazyLoadOptions = { ...commonLazyLoadOptions };

	      if (selector === '[data-bg]') {
	         lazyLoadOptions.class_loaded = 'lazy-loaded';
	         delete lazyLoadOptions.use_native; // Remove use_native for [data-bg]
	      } else {
	         lazyLoadOptions.use_native = true;
	      }
	      $(selector).removeClass('placeholder placeholder-wave');
	      new LazyLoad(lazyLoadOptions);
	   });
	}
	
   // Una nueva forma de guardar...
   $(document).on('keydown', function(event) {
      if (event.ctrlKey && event.key === 's') {
         event.preventDefault(); // Evita que el navegador guarde la página
         const SAVE = $('input[type="submit"]');
         if(SAVE.length === 1) SAVE.click();
      }
   });

   if($('a[data-encode="true"]').length > 0) {
	   $('a[data-encode="true"]').each(function(){
	      let url = $(this).attr('href');
	      $(this).attr({ href: `${ZCodeApp.url}/saliendo/?p=`  + base64_encode(url) });
	   });
   }
	
});