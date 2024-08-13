const live = {
	update: 60000, /** 1Min - 60 s - 60.000 ms **/
	hide: 60000, /** 1Min - 60 s - 60.000 ms **/
	focus: true,
	notifications: 0,
	messages: 0,
	status: {
		notifications: 'ON', 
		messages: 'ON', 
		sound: 'ON'
	},
	activate: 'ON',
	getTotalParam(attribute) {
		return parseInt($('#live-stream').attr(attribute));
	},
	setCookie(name, value = this.activate) {
		cookie.create(`live_${name}`, value);
	},
	getCookie(name) {
		cookie.get(`live_${name}`);
	},
	initialize() {
		const listCookies = ['notifications', 'messages', 'sound'];
		listCookies.forEach(cookie => {
			let cookieValue = live.getCookie(cookie);
			if (cookieValue === null) live.setCookie(cookie);
			live.status[cookie] = (cookieValue === null) ? live.activate : cookieValue;
		});
		// SI NO MOSTRAREMOS NADA PARA QUE GASTAMOS RECURSOS :D
		if(live.status['notifications'] == 'OFF' && live.status['messages'] == 'OFF') {
			return true;
		// EN 2 MINUTOS HACE UPDATE POR AJAX
		} else {
			setTimeout(() => {
				live.updateStatus();
			}, live.update);
		}
	},
	updateStatus() {
		loading.start()
		let notifications = live.status['notifications'];
		let messages = live.status['messages'];
		$.post(`${ZCodeApp.url}/live-stream.php`, { notifications, messages }, req => {
			live.print(req);
		})
		.done(() => {
			setTimeout(() => live.updateStatus(), live.update);
			loading.end()
		});
	},
	hideStatus() {
		let divs = $('.UIBeeper_Full')
		let total = divs.length;
		// ALGO RECURSIVO xD
		setTimeout(function() { 
			if(total > 0){
				if($(divs[0]).hasClass('UIBeep_Paused') == false) {
					$(divs[0]).fadeOut().remove();
					live.hideStatus();
				}
			}
		}, 8000);
	},
	// CARGAR NOTIFICACIONES
	print(response) {
		// CARGAMOS EL CONTENIDO, PARA OBTENER INFORMACION
		$('#BeeperBox').html(response);
		// OBTENEMOS TOTALES
		live.notifications = parseInt(live.notifications + live.getTotalParam('notifications'));
		live.messages = parseInt(live.messages + live.getTotalParam('messages'));
		let total_notis = live.notifications + live.messages;
		if(total_notis > 0) {
			const stream = $('#live-stream').html();
			// CARGAMOS
			$('#BeeperBox').html(stream);
			// MOSTRAMOS
			$('.UIBeeper_Full').fadeIn(1200);
			$('#BeeperBox').slideToggle(1000);
			// EVENTOS 
			live.events();
			// SI ESTAMOS EN LA PAGINA VIENDO...
			if(live.focus == true) {
				// OCULTO LAS NOTIFICACIONES
			   setTimeout(() => {
			   	live.hideStatus();
			   }, live.hide);
			} else {
				// TITULO
				const { titulo, slogan } = ZCodeApp;
				$(document).attr('title', `(${total_notis}) ${titulo} - ${slogan}`);
				// Añadimos el sonido
				live.soundStatus(total_notis);
				// GLOBITOS
				notifica.popup(live.notifications);
				mensaje.popup(live.messages);
			}     
		}
	},
	events() {
		$('.UIBeep').on('mouseover', function() {
			$(this).addClass('UIBeep_Selected');
			$(this).parent().addClass('UIBeep_Paused');
		}).on('mouseout', function() {
			$(this).removeClass('UIBeep_Selected');
			$(this).parent().removeClass('UIBeep_Paused')
			live.hideStatus();
		})
	},
	soundStatus(total_notis) {
		let sound_type = (live.m_total > 0) ? 'Message' : 'Alert';
		if(live.status['sound'] == 'ON') {
			let audioElement = $('<audio>');
			audioElement.attr({
				src: `${ZCodeApp.url}/inc/ext/new${sound_type}.mp3`,
				autoplay: 'autoplay'
			});
			$('body').append(audioElement);
		}
	},
	// SIN SONIDO
	sounds(cookie) {
		live.status[cookie] = (live.getCookie(cookie) == 'ON') ? 'OFF' : 'ON';
		// Actualizamos
		live.setCookie(cookie, live.status[cookie]);
	}
}

// READY
$(document).ready(function() {
	$('.beeper_x').on("click", function() {
		let bid = $(this).attr('bid');
		$('#beep_' + bid).fadeOut().remove();
		return false;
	});
	//
	live.initialize();
	// NOS DICE SI MOSTRAR O NO :D
	$(window).focus(() => live.focus = true).blur(() => live.focus = false)
});