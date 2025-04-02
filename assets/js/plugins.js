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
async function iModule(filePath = '', functionName, parameters) {
   let javascriptFile;
   try {
      // Construcción del nombre del archivo con una versión aleatoria para evitar cachés
      javascriptFile = `${ZCodeApp.assets}/fs/__fs${filePath}?v` + string_random(4);
      // Verificar si ya fue importado
      if (importedFiles.has(javascriptFile)) {
         console.log(`Archivo ${javascriptFile} ya ha sido importado.`);
         return;
      }
      // Intentar importar el archivo dinámicamente
      const module = await import(javascriptFile);
      importedFiles.set(javascriptFile, true); // Marcar como importado
      // Ejecutar las funciones especificadas
      const executeFunction = (fn) => {
         if (module[fn]) {
            module[fn](parameters);
         } else {
            console.error(`Function ${fn} no se encontró en ${javascriptFile}`);
         }
      };
      // Verificar si functionName es un array y recorrerlo, o ejecutar directamente si es único
      if (Array.isArray(functionName)) {
         functionName.forEach(executeFunction);
      } else {
         executeFunction(functionName);
      }
   } catch (error) {
      console.error(`Error al importar el archivo ${javascriptFile}:`, error);
      throw error;
   }
}


/**
 * Plugins globales que utilizará el script.
 * Los plugins: (fueron obtenidos desde https://locutus.io/php/)
*/
const empty=e=>{let n,r,t;const o=[void 0,null,!1,0,"","0"];for(r=0,t=o.length;r<t;r++)if(e===o[r])return!0;if("object"==typeof e){for(n in e)if(e.hasOwnProperty(n))return!1;return!0}return!1},htmlspecialchars_decode=(e,n)=>{let r=0,t=0,o=!1;void 0===n&&(n=2),e=e.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");const i={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(0===n&&(o=!0),"number"!=typeof n){for(n=[].concat(n),t=0;t<n.length;t++)0===i[n[t]]?o=!0:i[n[t]]&&(r|=i[n[t]]);n=r}return n&i.ENT_HTML_QUOTE_SINGLE&&(e=e.replace(/&#0*39;/g,"'")),o||(e=e.replace(/&quot;/g,'"')),e.replace(/&amp;/g,"&")},number_format=(e,n,r,t)=>{e=(e+"").replace(/[^0-9+\-Ee.]/g,"");const o=isFinite(+e)?+e:0,i=isFinite(+n)?Math.abs(n):0,a=void 0===t?",":t,l=void 0===r?".":r;let c="";return c=(i?function(e,n){if(-1===(""+e).indexOf("e"))return+(Math.round(e+"e+"+n)+"e-"+n);{const r=(""+e).split("e");let t="";return+r[1]+n>0&&(t="+"),(+(Math.round(+r[0]+"e"+t+(+r[1]+n))+"e-"+n)).toFixed(n)}}(o,i).toString():""+Math.round(o)).split("."),c[0].length>3&&(c[0]=c[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,a)),(c[1]||"").length<i&&(c[1]=c[1]||"",c[1]+=new Array(i-c[1].length+1).join("0")),c.join(l)},rawurlencode=e=>(e+="",encodeURIComponent(e).replace(/!/g,"%21").replace(/'/g,"%27").replace(/\(/g,"%28").replace(/\)/g,"%29").replace(/\*/g,"%2A")),base64_encode=e=>"undefined"!=typeof window&&window.btoa?window.btoa(unescape(encodeURIComponent(e))):Buffer.from(e,"binary").toString("base64"),in_array=(e,n,r=!1)=>{for(let t in n)if(r?n[t]===e:n[t]==e)return!0;return!1};

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
const loading = {
	timeout: 350,
	start() {
		iModule('Loading.js', 'loadingStart');
	},
	end() {
		setTimeout(() => $('#loading_start').remove(), this.timeout);
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
		size: 'md',
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
		cancelClass: 'UPModal-button UPModal-button-danger',
		cancelAction: 'close'
	},
	template: `<div class="UPModal">
		<div class="UPModal-mask"></div>
		<div class="UPModal-dialog" data-modal-icon="false">
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
		$('.UPModal-dialog').css({ 
			width: `var(--modal-size-` + (empty(size) ? this.default.size : size) + `)`
		});
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
		let typeStatus = (!empty(status) && status !== 'info' && status !== 'question');
		let styles = { 
			'background': `var(--modal-background-` + (typeStatus ? status : 'default') + `)`,
			'--color-icon': `var(--modal-icon-` + (typeStatus ? status : 'default') + `)`
		};
		if(!icon) delete styles['--color-icon'];
		$('.UPModal-dialog').css(styles);
		if (status !== '') {
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
				confirmShow: button||true,
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

$(() => {
	
	if($('lite-youtube').length > 0) {
		iModule('LiteYt.js', 'liteYt', {});
	}
	
   // Una nueva forma de guardar... CTRL + S
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

	// Observer
	const Observer = new IntersectionObserver((entries, self) => {
	   entries.forEach((entry) => {
	      if (entry.isIntersecting) {
	         const Target = entry.target;
	         const typeEntry = Target.localName === 'source' ? 'srcset' : 'src';
	         const dataValue = Target.getAttribute(`data-${typeEntry}`);
	         if (dataValue) {
	            Target[typeEntry] = dataValue;
	            Target.removeAttribute(`data-${typeEntry}`);
	         }
	         self.unobserve(Target);
	      }
	   });
	}, {
	   rootMargin: '50px',
	});

	// Seleccionar y observar imágenes con data-src o data-srcset
	const $images = document.querySelectorAll('[data-src], [data-srcset]');
	$images.forEach((image) => Observer.observe(image));

});