/**
 * Plugins globales que utilizará el script.
 * Los plugins: (fueron obtenidos desde https://locutus.io/php/)
 *  # Empty 
 *  # Htmlspecialchars_decode 
 *  # Number_format 
 *  # base64_encode
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

function generarCadenaAleatoria(longitud) {
	const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	let resultado = '';
	const caracteresLength = caracteres.length;
	for (let i = 0; i < longitud; i++) {
		resultado += caracteres.charAt(Math.floor(Math.random() * caracteresLength));
	}
	return resultado;
}

const verifyInput = (selector, errorMessage) => {
   const input = $(selector);
   const helpText = input.next('.upform-status');
   if (input.val().trim() === '') {
      input.parent().parent().find('.upform-status').addClass('error ok loading info');
      helpText.text(errorMessage);
      input.focus();
      return false;
   } else {
      input.parent().parent().find('.upform-status').removeClass('error ok loading info');
      helpText.text('');
      return true;
   }
};

$('input').on('keyup', function() {
	$(this).parent().parent().find('.upform-status').removeClass('error ok loading info').html('')
});

/**
 * Nuevas funciones creadas para zCode
 * Toast v1.0
 * Cookie v1.0
 * Loading v1.0
 * UPPassword v1.1
 * UPModal v3.4.2
*/
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
	create() {
		const { images: { assets: pathImages } } = ZCodeApp;
		const optionLoading = {
			position: 'fixed',
			top: '1rem',
			left: '1rem',
			padding: '.325rem 1rem .325rem .5rem',
			zIndex: 9999,
			background: 'var(--main-bg)',
			borderRadius: 'var(--border-radius)'
		}
		const optionContent = {
			display: 'flex',
			justifyContent: 'center',
			alignItems: 'center',
			gap: '.5rem',
			fontWeight: '700',
			color: 'var(--main-color)'
		}
		$('body').append(`<div id="loading_start"><div><img src="${pathImages}/spinner.gif" width="14" height="14" alt="Cargando"> Procesando</div></div>`);
		$('#loading_start').css(optionLoading);
		$('#loading_start').find('div').css(optionContent);
	},
	delete() {
		$('#loading_start').remove();
	},
	start() {
		this.create();
	},
	end() {
		this.delete();
	}
}

/**
 * UltraTheme Setting v1.1 - jQuery 3.7.1
 * @author Miguel92 - 2024
 * Generador de contraseñas
*/
const UPPassword = {
	size: 18,
	charset: "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&_~|}{[]?-=",
	generate: function(length = this.size) {
		return Array.from({ length }, () => this.charset[Math.floor(Math.random() * this.charset.length)]).join('');
	}
}


/**
 * UltraModal v3.4.2 - jQuery 3.7.1
 * @author Miguel92 - 2024
 * @status => success | danger | warning | default
 * @optional => icons
 * @link https://icon-sets.iconify.design/system-uicons/
*/
const UPModal = {
	status: '',
	size: 'default',
	close_mask: true,
	close_button: true,
	scrolleable: false,
	isShow: false,
	folder: 'system-uicons',
	buttons: {
		confirmShow: true,
		confirmTxt: 'Continuar',
		confirmClass: 'UPModal-button UPModal-button-success',
		confirmAction: 'UPModal.close()',
		cancelShow: false,
		cancelTxt: 'Cancelar',
		cancelClass: 'UPModal-button UPModal-button-outline',
		cancelAction: 'UPModal.close()'
	},
	statusIcons: {
		success: 'check-circle',
		danger: 'cross-circle',
		warning: 'warning-triangle',
		info: 'info-circle',
		question: 'question-circle',
		password: 'lock',
		email: 'mail',
		opt: 'fingerprint'
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
	searchIcon(name = '', folder = this.folder) {
		const { url: linkOfSite } = ZCodeApp;
		image = (folder === 'spinner') ? name : name.replace(/-/g, '_');
		return `${linkOfSite}/assets/icons/${folder}/${image}.svg`;
	},
	createOneIcon({ name, folder, html }) {
      const url = this.searchIcon(name, folder);
      const data = $.ajax({
         url: url,
         async: false,
         dataType: 'xml'
      }).responseXML;
      const svg = $(data).find('svg');
      return svg[0].outerHTML;
	},
	show() {
		if(this.isShow) return;
		this.isShow = true;
		//
		if ($('.UPModal').length === 0) $('body').append(this.template);
		// Para los tamaños del modal
		$('.UPModal-dialog').addClass(`UPModal-dialog-${this.size}`);
		// Cerramos modal con la mascará
		if (this.close_mask) $('.UPModal-mask').off('click').on('click', () => this.close());
		// Añadimos el botón para cerrar el modal
		this.addCloseButton();
		// Añadimos algunas configuraciones
		this.applyStatus();
		$('body').css({ overflow: (this.scrolleable ? 'auto' : 'hidden') });
		// Centrar modal
		this.centerModal();
		$(window).on('resize', this.centerModal);
	},
	applyStatus() {
		if (this.status) {
			$('.UPModal').attr('data-modal-status', this.status);
			this.setIcon(this.status);
		}
	},
	setIcon( status ) {
		let icon = this.statusIcons[status] || status;
		if (status) {
			$('.UPModal-icon').html(this.createOneIcon({ name: icon }));
			$('.UPModal-dialog').attr('data-modal-icon', true);
		} else {
			$('.UPModal-icon').remove();
		}
	},
	addCloseButton() {
		if(this.close_button) {
			$('.UPModal-title').before(`<div role="button" onclick="UPModal.close()" class="UPModal-close" data-modal-close="true">&times;</div>`);
		}
	},
	centerModal() {
      $('.UPModal-dialog').css({
         position: 'fixed',
         top: '50%',
         left: '50%',
         transform: 'translate(-50%, -50%)'
      });
	},
	close(){
		this.isShow = false;
		$('.UPModal').remove();
		$('body').css({ overflow: 'auto' });
	},
	setTitle(title) {
		$('.UPModal-title').html(title);
	},
	setBody(body) {
		$('.UPModal-body').html(body);
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
	setupModal({ title, body, buttons }) {
		this.show(true);
		this.setTitle(title);
		this.setBody(body);
		this.setButtons(buttons);
	},
	alert(title, body, reload = false) {
		this.setupModal({ title,  body, 
			buttons: {
				confirmShow: true,
				confirmTxt: 'Aceptar',
				confirmAction: `UPModal.close();${reload ? 'location.reload();' : ''}`,
				confirmClass: 'UPModal-button UPModal-button-' + (!empty(this.status) ? this.status : 'outline')
			}
		});
		$('.UPModal-dialog').addClass('UPModal-alert');
	},
	error_500(retryAction) {
		setTimeout(() => {
			this.proccess_end();
			this.setupModal({
				title: 'Error', 
				body: 'Error al intentar procesar lo solicitado', 
				buttons: {
					confirmShow: true,
					confirmTxt: (retryAction ? 'Reintentar' : 'Cerrar'),
					confirmAction: `UPModal.close();${retryAction || ''}`,
					cancelShow: true
				}
			});
		}, 200);
	},
	proccess_start(content = 'Espere, por favor'){
		if(!this.isShow) {
			this.setupModal({
				status: 'default',
				title: '', 
				body: '', 
				buttons: { confirmShow: false }
			});
		}
		const { images: { assets, tema } } = ZCodeApp;
		const processTemplate = `<div class="UPModal-proccess"><span>${content}</span><img src="${assets}/loading_bar.gif" /></div>`;
		$('.UPModal-message').append(processTemplate).fadeIn('fast');
		$('.UPModal-body, .UPModal-buttons').fadeOut();
	},
	proccess_end(timeout = 1){
		setTimeout(() => {
			$('.UPModal-body, .UPModal-buttons').fadeIn('fast');
			$('.UPModal-message').fadeOut('fast');
		}, timeout * 1000);
	},
	setInput({ label, type, name, placeholder, maxlength, id, required, inputmode }) {
		let isRequired = required ? ' required' : '';
		let appendIconForm = '';
		let isMax = ' maxlength="'+maxlength+'"';
		let isInputMode = !empty(inputmode) ? ' inputmode="'+inputmode+'"' : '';
		let iconType = !empty(inputmode) ? 'opt' : type;
		if(this.statusIcons[iconType]) {
			let inputicon = this.createOneIcon({ name: this.statusIcons[iconType] })
			appendIconForm += `<div class="upform-input-icon">${inputicon}</div>`;
		}
		return `<div class="upform-group"><label class="upform-label" for="${name}">${label}</label><div class="upform-group-input upform-icon">${appendIconForm}<input class="upform-input" type="${type}" name="${name}" id="${name}" placeholder="${placeholder}"${isMax}${isInputMode}${isRequired}></div></div>`;
	},
	setModal({ icon, status, close, mask, size, title, body, buttons, input }) {
		this.status = status ?? '';
		this.size = size ?? 'default'; // small | normal | big
		this.close_mask = mask ?? true;
		this.close_button = close ?? false;
		body = body ?? this.setInput(input);
		this.setupModal({ title, body, buttons });
		this.setIcon(icon);
		this.applyStatus();
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


/**
 * Imported v1.2
 * Lo que hace es cargar el código cuando es necesario
 * y que no se cargue cuando no se usa
 * Ahora verifica si ya fue importado
*/
// Mapa para almacenar los archivos ya importados
const importedFiles = new Map();
async function imported(fileroute = '', execFunction = '', objects, from = 'theme') {
	try {
		const { theme, assets } = ZCodeApp;
		javascriptFile = `${ZCodeApp[from]}/js/${fileroute}?v` + generarCadenaAleatoria(4);

		// Verificar si el archivo ya ha sido importado
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

$(() => {
	if($('lite-youtube').length > 0) {
		/**
		 * Solo cargará cuando sea necesario
		*/
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
	
	$('.drop-select--toggle').on('click', function() {
      var $menu = $(this).siblings('.drop-select--menu');
      $('.drop-select--menu').not($menu).hide(); // Hide other menus
      $menu.toggle();
   });

   // Select dropdown item
   $('.drop-select').on('click', '.drop-select--item', function() {
      var $this = $(this);
      var selectedText = $this.find('span').text();
      var selectedValue = $this.data('value');
      var $select = $this.closest('.drop-select');
      
      $select.find('.drop-select--toggle').text(selectedText);
      $select.find('input[type="hidden"]').val(selectedValue);
      $select.find('.drop-select--menu').hide();
   });

   // Close dropdown if clicked outside
   $(document).on('click', function(event) {
      if (!$(event.target).closest('.drop-select').length) {
         $('.drop-select--menu').hide();
      }
   });

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
	      $(this).attr({
	         href: `${ZCodeApp.url}/saliendo/?p=`  + base64_encode(url)
	      });
	   });
   }

});