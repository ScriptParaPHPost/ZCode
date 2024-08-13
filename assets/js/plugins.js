/**
 * Plugins globales que utilizará el script.
 * Los plugins: (fueron obtenidos desde https://locutus.io/php/)
 *  # Empty 
 *  # Htmlspecialchars_decode 
 *  # Number_format 
*/
empty = n => {let e,r,t;const f=[undefined,null,!1,0,"","0"];for(r=0,t=f.length;r<t;r++)if(n===f[r])return!0;if("object"==typeof n){for(e in n)if(n.hasOwnProperty(e))return!1;return!0}return!1}
htmlspecialchars_decode = (e,E) => {let T=0,_=0,t=!1;void 0===E&&(E=2),e=e.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");const c={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(0===E&&(t=!0),"number"!=typeof E){for(E=[].concat(E),_=0;_<E.length;_++)0===c[E[_]]?t=!0:c[E[_]]&&(T|=c[E[_]]);E=T}return E&c.ENT_HTML_QUOTE_SINGLE&&(e=e.replace(/&#0*39;/g,"'")),t||(e=e.replace(/&quot;/g,'"')),e=e.replace(/&amp;/g,"&")}
number_format = (e,t,n,i) => {e=(e+"").replace(/[^0-9+\-Ee.]/g,"");const r=isFinite(+e)?+e:0,o=isFinite(+t)?Math.abs(t):0,a=void 0===i?",":i,d=void 0===n?".":n;let l="";return l=(o?function(e,t){if(-1===(""+e).indexOf("e"))return+(Math.round(e+"e+"+t)+"e-"+t);{const n=(""+e).split("e");let i="";return+n[1]+t>0&&(i="+"),(+(Math.round(+n[0]+"e"+i+(+n[1]+t))+"e-"+t)).toFixed(t)}}(r,o).toString():""+Math.round(r)).split("."),l[0].length>3&&(l[0]=l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,a)),(l[1]||"").length<o&&(l[1]=l[1]||"",l[1]+=new Array(o-l[1].length+1).join("0")),l.join(d)}
rawurlencode = string => {string = string + '';return encodeURIComponent(string).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');}

function isYoutube(linkVideo) { 
	const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/; 
	const match = linkVideo.match(regExp); 
	return (match && match[7].length === 11) ? match[7] : false;
}
function loadScript(url) {
	return new Promise((resolve, reject) => $.getScript(url).done(resolve).fail(reject));
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

async function imported(fileroute = '', execFunction = '', objects) {
	try {
		const { theme } = ZCodeApp;
		javascriptFile = `${theme}/js/${fileroute}?v` + generarCadenaAleatoria(4);
	
		const module = await import(javascriptFile);
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
 * Nuevas funciones creadas para zCode
 * Toast v1.0
 * Cookie v1.0
 * Loading v1.0
 * UPPassword v1.1
 * UPIcons v1.1
 * UPModal v3.3
 * UPEffects v1
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
		console.log(title, content, type, autoClose, duration)
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
 * UltraIcons v1.1 - jQuery 3.7.1
 * @author Miguel92 - 2024
 * Agregar icono svg desde etiqueta
*/
const UIcons = {
	folder: 'system-uicons',
	searchIcon(name = '', folder = this.folder) {
		const { url: linkOfSite } = ZCodeApp;
		image = (folder === 'spinner') ? name : name.replace(/-/g, '_');
		return `${linkOfSite}/assets/icons/${folder}/${image}.svg`;
	},
	applyStyles(iconElement) {
		const color = iconElement.attr('var') ? `var(${iconElement.attr('var')})` : iconElement.attr('color') || 'inherit';
		iconElement.css({ color });
	},
	createIcons() {
		$('*[uicon]').each(function() {
			const iconElement = $(this);
			$.get(UIcons.searchIcon(iconElement.attr('uicon'), iconElement.attr('folder')), function(data) {
				const svg = $(data).find('svg');
				const size = iconElement.attr('size') ?? '1.5rem';
				iconElement.width(size).height(size);
				svg.attr({ width: size, height: size });
				UIcons.applyStyles(iconElement);
				iconElement.html(svg);
			}, 'xml');
		});
	},
	createOneIcon({ name, folder, html }) {
      const url = UIcons.searchIcon(name, folder);
      const data = $.ajax({
         url: url,
         async: false,
         dataType: 'xml'
      }).responseXML;

      const svg = $(data).find('svg');
      console.log(data)
      //return svg[0].outerHTML;
	}
}

/**
 * UltraModal v3.3 - jQuery 3.7.1
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
		email: 'mail'
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
		//
		$('[data-modal-close="true"]').on('click', () => this.close());
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
			$('.UPModal-icon').html(UIcons.createOneIcon({ name: icon }));
			$('.UPModal-dialog').attr('data-modal-icon', true);
		} else {
			$('.UPModal-icon').remove();
		}
	},
	addCloseButton() {
		if(this.close_button) {
			$('.UPModal-title').before(`<div class="UPModal-close" data-modal-close="true">
				<div uicon="system-uicons:cross"></div>
			</div>`);
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
	setInput({ label, type, name, placeholder, maxlength, id, required }) {
		let isRequired = required ? ' required' : '';
		let appendIconForm = '';
		if(this.statusIcons[type]) {
			appendIconForm += `<div class="upform-input-icon">
				<iconify-icon icon="${this.statusIcons[type]}"></iconify-icon>
			</div>`;
		}
		const temp = `<div class="upform-group">
			<label class="upform-label" for="${name}">${label}</label>
			<div class="upform-group-input">
				${appendIconForm}
				<input class="upform-input" type="${type}" name="${name}" id="${name}" placeholder="${placeholder}"${isRequired}>
			</div>
		</div>`;
		return temp;
	},
	setModal({ icon, status, close, mask, size, title, body, buttons, input }) {
		this.status = status ?? '';
		this.size = size ?? 'default'; // small | normal | big
		this.close_mask = mask ?? true;
		this.close_button = close ?? false;
		body = body ?? this.setInput(input);
		this.setupModal({
			title: title, 
			body: body, 
			buttons: buttons
		});
		this.setIcon(icon);
		this.applyStatus();
	}
};

/**
 * Abrevia un número grande usando sufijos como 'K' para miles, 'M' para millones, etc.
 * @param {number} value - El número a abreviar.
 * @returns {string} - El número abreviado como una cadena de texto.
*/
function UPAbbr(value) {
   let newValue = value;
   if (value >= 1000) {
      const suffixes = ["", "K", "M", "B", "T"];
      const suffixNum = Math.floor(("" + value).length / 3);
      let shortValue = '';
      for (let precision = 2; precision >= 1; precision--) {
         shortValue = parseFloat((suffixNum !== 0 ? (value / Math.pow(1000, suffixNum)) : value).toPrecision(precision));
         const dotLessShortValue = (shortValue + '').replace(/[^a-zA-Z 0-9]+/g, '');
         if (dotLessShortValue.length <= 2) break;
      }
      if (shortValue % 1 !== 0) shortValue = shortValue.toFixed(1);
      newValue = shortValue + suffixes[suffixNum];
   }
   return newValue;
}

/**
 * UltraEffects v1.0 - jQuery 3.7.1
 * @author Miguel92 - 2024
 * Añadiendo efectos practicos
*/
const UPEffects = {
	duration: 1000,
	easing: 'linear',
	element: '.up-effect.',
	frameRate: 30,
  	dataCount(object) {
   	return parseInt(object.attr('data-count'), 10);
  	},
  	mathFloor(countTo, random = false) {
  		let numberFloor = random ? Math.random() * countTo : countTo;
   	return Math.floor(numberFloor);
  	},
  	counter(object, changes = {}) {
   	const { duration, easing } = { ...this, ...changes };
   	const elements = $(this.element + object);
   	elements.each(function() {
   		const Counter = $(this);
   		const countTo = UPEffects.dataCount(Counter);
   		$({ countNum: Counter.text() }).animate({ countNum: countTo }, {
   			duration,
   			easing,
   			step() { Counter.text(UPEffects.mathFloor(this.countNum)); },
   			complete() { Counter.text(UPAbbr(this.countNum)); }
   		});
   	});
  	},
	decrypt(object, changes = {}) {
		const { duration, easing, frameRate } = { ...this, ...changes };
		const elements = $(this.element + object);
		const frameDuration = 1000 / frameRate;
		elements.each(function() {
			const Counter = $(this);
			const countTo = UPEffects.dataCount(Counter);
			const frames = duration / frameDuration;
			const increment = countTo / frames;
			let currentCount = 0;

			const interval = setInterval(() => {
				Counter.text(UPEffects.mathFloor(countTo, true));
				currentCount += increment;
				if (currentCount >= countTo) {
				  	clearInterval(interval);
				  	Counter.text(UPAbbr(countTo));
				}
			}, frameDuration);
		});
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

$(() => {
	UIcons.createIcons();
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

   const anchor = window.location.hash;
   if (anchor) {
      // Obtener el elemento por el id del ancla
      const element = $(anchor);
      if (element) {
         // Agregar la clase de resaltado
         element.addClass("highlight");
			// Eliminar el resaltado después de un tiempo (opcional)
         setTimeout(() => {
            element.removeClass("highlight");
         }, 3000); // Quitar el resaltado después de 3 segundos
      }
   }

});