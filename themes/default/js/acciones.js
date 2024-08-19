function gget(data, sin_amp) {
	var req = data + '=';
	if(!sin_amp) req = '&' + req;
	obj = (data === 'key') ? 'user_key' : data;
	if(ZCodeApp[obj] !== '') return req + ZCodeApp[obj];
	return '';
}
/**
 * Solo cargará la función completa al ejecutarlo
*/
function bloquear(user, bloqueado, lugar, aceptar) {
	imported('acciones/bloquear.js', 'bloquear', { user, bloqueado, lugar, aceptar });
}

/* DENUNCIAS */
var denuncia = {
	nueva(type, obj_id, obj_title, obj_user) {
		// PLANTILLA
		loading.start();
		$.post(`${ZCodeApp.url}/denuncia-${type}.php`, { obj_id, obj_title, obj_user }, req => {
			denuncia.set_dialog({ req, obj_id, type});
			loading.end();
		})
	},
	set_dialog({ req, obj_id, type}) {
		UPModal.setModal({
			title: `Denunciar ${type}`,
			body: req,
			buttons: {
				confirmTxt: `Enviar denuncia`,
				confirmAction: `denuncia.enviar(${obj_id}, '${type}')`,
				cancelShow: true
			}
		});
	},
	enviar(obj_id, type) {
		let razon = $('select[name=razon]').val();
		let extras = $('textarea[name=extras]').val();
		  //
		loading.start();
		$.post(`${ZCodeApp.url}/denuncia-${type}.php`, { obj_id, razon, extras }, req => {
			let action = parseInt(req.charAt(0));
			let message = req.substring(3);
			UPModal.alert((action === 0 ? 'Error' : 'Bien'), `<div class="empty">${message}</div>`, false);
			loading.end();
		});
	}
}

function login_modal() {
	$.post(`${ZCodeApp.url}/login-form.php`, req => {
		UPModal.setModal({
			title: 'Bienvenidos a ' + ZCodeApp.titulo,
			body: req,
			buttons: {
				confirmTxt: 'Iniciar sesión',
				confirmAction: `login.iniciarSesion()`,
				cancelShow: true
			}
		});
	});		
}

/* Notificaciones */
var notifica = {
   cache: {},
   retry: [],
   handleNumber(parse, format = false) {
   	let parsear = parseInt(parse);
   	return format ? parsear : number_format(parsear);
   },
   handleResponse(response, successCallback) {
      let handleRes = response.split('-');
      if (handleRes.length == 3 && handleRes[0] == 0) {
         successCallback(handleRes);
      } else if (handleRes.length == 4) {
         UPModal.alert('Notificaciones', handleRes[3]);
      }
   },
	userMenuHandle(response) {
		notifica.handleResponse(response, req => {
			let cache_id = 'following_' + req[1];
			notifica.cache[cache_id] = notifica.handleNumber(req[0]);
			$('div.avatar-box').children('ul').hide();
		});
	},
	userInPostHandle(response) {
		notifica.handleResponse(response, req => {
			let text = (parseInt(req[2]) === 0) ? 'Seguir usuario' : 'Dejar de seguir';
			$('[user-follow]').html(text);
			$('.user_follow_count').html(notifica.handleNumber(req[2], true));
			notifica.userMenuHandle(response);
		});
	},
	inPostHandle(response) {
		notifica.handleResponse(response, req => {
			$('.btn.follow_post, .btn.unfollow_post').parent().toggle();
			$('#seguidores_post').html(notifica.handleNumber(req[2], true));
		});
	},
	inComunidadHandle(response) {
		notifica.handleResponse(response, req => {
			$('.follow_comunidad, .unfollow_comunidad').toggle();
			$('.comunidad_seguidores').html(notifica.handleNumber(req[2], true) + ' Seguidores');
		});
	},
	temaInComunidadHandle(response) {
		notifica.handleResponse(response, req => {
			$('.follow_tema, .unfollow_tema').toggle();
			$('.tema_notifica_count').html(notifica.handleNumber(req[2], true) + ' Seguidores');
		});
	},
	ruserInAdminHandle(response) {
		notifica.handleResponse(response, req => $('.ruser' + notifica.handleNumber(req[1])).toggle());
	},
	listInAdminHandle(response) {
      notifica.handleResponse(response, req => {
      	let lNumb = notifica.handleNumber(req[1]);
      	let firstElement = $(`.list${lNumb}:first`);
         $(`.list${lNumb}`).toggle();
         firstElement.parent('div').parent('li').children('div:first').fadeTo(0, firstElement.css('display') == 'none' ? 0.5 : 1);
      });
	},
	spamHandle(response) {
		var req = response.split('-');
		if (req.length == 2) UPModal.alert('Notificaciones', req[1]);
		else UPModal.close();
	},
	ajax(param, func, obj) {
		if ($(obj).hasClass('spinner iconify')) return;
		notifica.retry.push(param);
		notifica.retry.push(func);
		var error = param[0] != 'action=count';
		$(obj).addClass('spinner iconify');
		loading.start();
		$.post(`${ZCodeApp.url}/notificaciones-ajax.php`, [...param, gget('key')].join('&'), response => {
			$(obj).removeClass('spinner iconify');
			func(response, obj);
			loading.end()
		}).fail(error => {
			if (error) UPModal.error_500('notifica.ajax(notifica.retry[0], notifica.retry[1])');
			loading.end()   
		});
	},
	// action = follow | action = unfollow
	followed(action, type, id, func, obj, where = '') {
		this.ajax(['action=' + action, 'type='+type, 'obj='+id], func, obj);
		if(where === 'perfil') {
			$.post(`${ZCodeApp.url}/perfil-seguidores-sidebar.php`, { pid: id }, req => $('.reload_followed').html(req));
		}		
	},
	share(type, id) {
		let actionNot = (type === 'post') ? 'spam' : 'c_spam';
		UPModal.setModal({
			title: 'Recomendar',
			body: `¿Quieres recomendar este ${type} a tus seguidores?`,
			buttons: {
				confirmTxt: 'Recomendar',
				confirmAction: `notifica.${actionNot}('${id}', notifica.spamHandle)`,
				cancelShow: true
			}
		});
	},
	spam(id, func) {
		this.ajax(['action=spam', 'postid='+id], func);
	},
	c_spam(id, func) {
		this.ajax(['action=c_spam', 'temaid='+id], func);
	},
	last() {
		let total = notifica.handleNumber($('a[name="Monitor"]').data('popup'));
		mensaje.close();
		if ($('#mon_list').css('display') != 'none') $('#mon_list').fadeOut();
		else {
			if (($('#mon_list').css('display') == 'none' && total > 0) || typeof notifica.cache.last == 'undefined') {
				$('a[name=Monitor]').addClass('spinner iconify');
				$('#mon_list').slideDown();
				notifica.ajax(['action=last'], function (req) {
					notifica.cache['last'] = req;
					notifica.show();
				});
			}
			else notifica.show();
		}
	},
	check: () => notifica.ajax(['action=count'], notifica.popup),
	popup(response) {
		let total = notifica.handleNumber($('a[name="Monitor"]').data('popup'));
		let withTitle = (response != total && response > 0);
		let title = withTitle ? total + ' notificaci' + (response != 1 ? 'ones' : '&oacute;n') : '';
		$('.monitor').attr({
			'data-badge': (response == 0 ? false : true)
		});
	},
	show() {
		if (typeof notifica.cache.last != 'undefined') {
			$('a[name=Monitor]').removeClass('spinner iconify');
			$('#mon_list').show().children('ul').html(notifica.cache.last);
		}
	},
	filter() {
		let fid = [];
		let inputs = $('.check-filter input');
		inputs.map( (pos, input) => {
			if($(input).prop('checked')) fid.push(input.id)
		})
		$.post(ZCodeApp.url + '/notificaciones-filtro.php', { fid });
	},
	close: () => {
		$('#mon_list').hide();
		$('a[name=Monitor]').parent('li').removeClass('monitor-notificaciones');   
	}
}

/* Mensajes */
var mensaje = {
	cache: {},
	vars: [],
	handleFormInput: [
		{ label: 'Para:', name: 'msg_to', value: 'to', placeholder: 'Ingrese el nombre de usuario' }, 
		{ label: 'Asunto:', name: 'msg_subject', value: 'sub', placeholder: 'Asunto del mensaje' }, 
		{ label: 'Mensaje:', name: 'msg_body', value: 'msg', placeholder: 'Mensaje' }
	],
	splitString(object, numb) {
		let getNumber = object.split(':');
		return getNumber[parseInt(numb)];
	},
	// CREAR HTML
	form() {
		let html = (this.vars['error']) ? `<div class="empty">${this.vars['error']}</div>` : '';
		html += this.handleFormInput.reduce((inputsAppend, { label, icono, name, value, placeholder }) => {
         const isInput = value === 'msg' ? 
            `<textarea name="${name}" id="${name}" class="upform-textarea" rows="2">${this.vars[value] || ''}</textarea>` :
            `<input class="upform-input" type="text" name="${name}" id="${name}" placeholder="${placeholder}" value="${this.vars[value] || ''}">`;
         return inputsAppend + `
            <div class="upform-group">
               <label class="upform-label" for="${name}">${label}</label>
               <div class="upform-group-input">${isInput}</div>
            </div>`;
      }, '');
		return html;                          
	},
	// FUNCIONES AUX
	checkform(req){
		if(parseInt(req) == 0) mensaje.enviar(1);
		else if(parseInt(req) == 1) {
			mensaje.nuevo(mensaje.vars['to'], mensaje.vars['sub'], mensaje.vars['msg'], 'No es posible enviarse mensajes a s&iacute; mismo.');
		} else if(parseInt(req) == 2) {
			mensaje.nuevo(mensaje.vars['to'], mensaje.vars['sub'], mensaje.vars['msg'], 'Este usuario no existe. Por favor, verif&iacute;calo.');
		}    
	},
	alert(req) {
		UPModal.proccess_end();
		UPModal.alert('Aviso', `<div class="empty">${req}</div>`);  
	},
	eliminar: function(id,type){
		mensaje.ajax('editar', `ids=${id}&act=delete`, () => {
			if(type == 1){
				$('#mp_' + mensaje.splitString(id, 0)).remove();
			} else if(type == 2) {
				location.href = ZCodeApp.url + '/mensajes/';
			}
		});
	},
	marcar: function(id, a, type, obj){
		let actRead = (a == 0) ? 'read' : 'unread';
		let showRead = (actRead == 'read') ? 'unread' : 'read';
		mensaje.ajax('editar', `ids=${id}&act=${actRead}`, function(r){
			// CAMBIAR ENTRE LEIDO Y NO LEIDO
			if(type == 1) {
				$('#mp_' + mensaje.splitString(id, 0))[(actRead == 'read' ? 'removeClass' : 'addClass')]('unread');
				$(obj).hide();
				$(obj).parent().find(`.${showRead}`).show();
			} else {
				location.href = ZCodeApp.url + '/mensajes/';
			}
		});
	},
	// POST
	ajax: function(action, params, fn){
		UPModal.proccess_end();
		loading.start();
		$.post(`${ZCodeApp.url}/mensajes-${action}.php`, params, req => {
			fn(req);
			loading.end();
		});
	},
	// PREPARAR EL ENVIO
	nuevo: function (para, asunto = '', body = '', error = '') {
		if(empty(ZCodeApp.user_key)) location.href = ZCodeApp.url + '/registro/';
		// GUARDAR
		this.vars['to'] = para;
		this.vars['sub'] = asunto;
		this.vars['msg'] = body;
		this.vars['error'] = error;
		//
		UPModal.proccess_end();
		UPModal.setModal({
			title: 'Nuevo mensaje',
			body: this.form(),
			buttons: {
				confirmTxt: 'Enviar mensaje',
				confirmAction: `mensaje.enviar(0)`,
				cancelShow: true
			}
		});
	},
	// ENVIAR...
	enviar: function (enviar){
		// DATOS
		this.vars['to'] = $('#msg_to').val();
		this.vars['sub'] = encodeURIComponent($('#msg_subject').val());
		this.vars['msg'] = encodeURIComponent($('#msg_body').val());
		// COMPROBAR
		if(enviar == 0){ // VERIFICAR...
			if(this.vars['to'] == '')
				mensaje.nuevo(mensaje.vars['to'], mensaje.vars['sub'], mensaje.vars['msg'], 'Por favor, especific&aacute; el destinatario.');
			if(this.vars['msg'] == '')
				mensaje.nuevo(mensaje.vars['to'], mensaje.vars['sub'], mensaje.vars['msg'], 'El mensaje esta vac&iacute;o.');
			//
			UPModal.proccess_start('Verificando...', 'Nuevo Mensaje');
			this.ajax('validar', 'para=' + this.vars['to'], mensaje.checkform);
		} else if(enviar == 1) {
			UPModal.proccess_start('Enviando...', 'Nuevo Mensaje');
			// ENVIAR
			const paramsSend = `para=${mensaje.vars['to']}&asunto=${mensaje.vars['sub']}&mensaje=${mensaje.vars['msg']}`;
			this.ajax('enviar', paramsSend, mensaje.alert);
		}
	},
	// RESPONDER
	responder(mp_id) {
	  	this.vars['mp_id'] = $('#mp_id').val();
	  	this.vars['mp_body'] = encodeURIComponent($('#respuesta').bbcode());
	  	if(this.vars['mp_body'] == '') {
			$('#respuesta').focus();
			return;
	  	}
	  //
	  this.ajax('respuesta','id=' + this.vars['mp_id'] + '&body=' + this.vars['mp_body'], req => {
			$('#respuesta').val(''); // LIMPIAMOS
			$('.wysibb-body').html('');
			switch(req.charAt(0)){
				case '0':
					UPModal.alert("Error", req.substring(3));
				break;
				case '1':
					$('#historial').append($(req.substring(3)).fadeIn('slow'));
				break;
			}
			$('#respuesta').focus();
	  	});
	},
	last: function () {
		let total = parseInt($('a[name="Mensajes"]').data('popup'));
		notifica.close();
		  //
		if ($('#mp_list').css('display') != 'none') $('#mp_list').hide();
		else {
			if (($('#mp_list').css('display') == 'none' && total > 0) || typeof mensaje.cache.last == 'undefined') {
				$('a[name=Mensajes]').addClass('spinner iconify');
				$('#mp_list').show();
				mensaje.ajax('lista', '', function (r) {
					mensaje.cache['last'] = r;
					mensaje.show();
				});
			} else mensaje.show();
		}
	},
	popup: function (response) {
		let total = parseInt($('a[name="Mensajes"]').data('popup'));
		let withTitle = (response != total && response > 0);
		let title = withTitle ? total + ' mensaje' + (response != 1 ? 's' : '') : '';
		$('.menu-list-user .mensajes').attr({
			'data-badge': (response == 0 ? false : true),
			'data-title': title
		});
	},
	show: function () {
		if (typeof mensaje.cache.last != 'undefined') {
			$('a[name=Mensajes]').removeClass('spinner iconify');
			$('#mp_list').show().children('ul').html(mensaje.cache.last);
		}
	},
	close: () => $('#mp_list').slideUp()
}

function decodeEmail() {
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

// NEWS
const news = {
	total: 0,
	count: 1,
	time: 7, // segundos
	countdownTime: 0,
	slider() {
		if(news.total > 1){
			if(news.count < news.total) news.count++;
			else news.count = 1;
			//
			$('#top_news .news--item').hide();
			$('#new_' + news.count).fadeIn();
			news.startCountdown();
			// INFINITO :D
			setTimeout("news.slider()", news.time * 1000);
		}
	},
	startCountdown() {
		news.countdownTime = news.time - 1;
		const countdownElement = $('#new_' + news.count + ' .countdown');
		const countdownInterval = setInterval(() => {
			if(news.countdownTime > 0) {
				countdownElement.text(`${news.countdownTime}s`);
				news.countdownTime--;
			} else {
				clearInterval(countdownInterval);
			}
		}, 1000);
	}
}

// READY
$(document).ready(() => {
	/* NOTICIAS */
	news.total = $('#top_news .news--item').length;
	news.slider();

	const BrandDay = $('#brandday');
	const StickyMSG = $('#stickymsg');
	StickyMSG.on({
   	mouseover: () => BrandDay.css('opacity', 0.5),
   	mouseout:  () => BrandDay.css('opacity', 1),
   	click:     () => location.href = `${ZCodeApp.url}/moderacion/`
	});

	$('a[data-dropopen]').on('click', function(event) {
		event.preventDefault();
		const dropopen = $(this).data('dropopen');
		const dropdownElement = $(`.up-dropdown[data-dropname="${dropopen}"]`);
		let isTrue = dropdownElement.attr('data-dropdown') === 'true';
		// Cerramos todos los dropdown abiertos
		$('.up-dropdown').attr('data-dropdown', false);
		// Alternar el estado del dropdown específico
		if (!isTrue) dropdownElement.attr('data-dropdown', true);
	});

	$('[data-dropaction]').on('click', function(event) {
		let dropAction = $(this).data('dropaction');
		$('.up-subdropdown')[(dropAction ? 'addClass' : 'removeClass')]('show');
		/**
		 *  Añadir height automatico
		*/
		let totalItems = $('.up-subdropdown .subitem-drop').length;
		let firstHeight = $('.up-subdropdown .subitem-drop').first().height();
    	let Height = (Math.ceil(firstHeight) * totalItems) + ((0.5 * 16) * totalItems) + 'px'; /* 16px root */
  		const style = {
  			height: dropAction ? Height : 'auto',
			transition: 'height .4s ease-in-out'
  		}
		$('.up-dropdown--secondary').css(style);
	});

	const displayDropdown = [
		{id: '#mon_list', attrName: 'Monitor', callFunction: notifica.last},
		{id: '#mp_list', attrName: 'Mensajes', callFunction: mensaje.last}
	];
	$('body').on('click', function(e) {
		displayDropdown.forEach(({ id, attrName, callFunction }) => {
			const $element = $(id);
			if ($element.is(':visible') && !$(e.target).closest(id).length && !$(e.target).closest(`a[name=${attrName}]`).length) {
				callFunction();
			}
		});
	});

	decodeEmail();

	if( $('#respuesta').length ) {
		$('#respuesta').css({ height: 40 }).html('').wysibb({ buttons: "smilebox,|,bold,italic,underline,strike" });
	}
	
});