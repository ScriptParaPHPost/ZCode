// Algunas variables
const loadWYSIBB = $('textarea[name="cuerpo"]');
// borrador
let draftSetTime;
let draftLastMessage = '';
let draftIsEnabled = true;
// Post
let confirm = true;
let tags = false;
let currentTime = new Date();
// Obtenemos hora
const hours = [
	currentTime.getHours(),
	currentTime.getMinutes(),
	currentTime.getSeconds()
].join(':');

function countUpperCase(string) {
	var len = string.length, 
	strip = string.replace(/([A-Z])+/g, '').length, 
	strip2 = string.replace(/([a-zA-Z])+/g, '').length, 
	percent = (len  - strip) / (len - strip2) * 100;
	return percent;
}
// Mostramos el error
function error(objeto, mensaje, tipo){
	let addRemove = tipo ? 'addClass' : 'removeClass';
	let showHide = tipo ? 'show' : 'hide';
	objeto.parent().parent().children('.upform-status')[addRemove]('error').html(mensaje)[showHide]();
}
//
function guardar() {
	let replace_body = 'cuerpo=' + encodeURIComponent(loadWYSIBB.bbcode());
	let params = $("form[name=newpost]").serialize().replace('cuerpo=', replace_body);
	const borrador_id = $('input[name="borrador_id"]').val()
	$('div#borrador-guardado').html('Guardando...');

	draftSetTime = setTimeout('draftSave()', 6000);
	draftSave(false);

	if(!empty(borrador_id)) params += '&borrador_id=' + encodeURIComponent(borrador_id);
	let page = 'borradores-' + (!empty(borrador_id) ? 'guardar' : 'agregar');
	$.post(`${ZCodeApp.url}/${page}.php`, params, req => {
		let reqType = parseInt(req.charAt(0));
		if(reqType === 0) {
			clearTimeout(draftSetTime);
			draftSetTime = setTimeout('draftSave()', 5000);
		} else {
			if(!empty(borrador_id)) $('input[name="borrador_id"]').val(message);
		}
		draftLastMessage = (reqType === 0) ? `Guardado a las ${hours} hs.` : req.substring(3);
		$('div#borrador-guardado').html(draftLastMessage);
	}).fail(() => UPModal.error_500('save_borrador()'))
}

function draftSave(enable = true) {
	const draftButton = $('input#borrador-save');
	if (draftButton.length) {
		draftButton.toggleClass('disabled', !enable).prop('disabled', (enable ? false : true));
	}
	draftIsEnabled = enable;
}

// Preguntar antes de cerrar
var confirmar = true;
window.onbeforeunload = () => {
	if (confirmar && ($("input[name=titulo]").val() || $('textarea[load="wysibb"]').bbcode())) 
		return "Este post no fue publicado y se perdera.";
}
//
function preliminar() {
	//COMPROBAR CONTENIDO
	if (loadWYSIBB.bbcode().length < 1) {
		error($('.wysibb'), 'Ingresa contenido para el post', true);
		loadWYSIBB.focus();
		window.scrollTo(0, 50)
		return false;
	}
	UPModal.setModal({
		title: 'Vista preliminar',
		body: '<div class="carf"><p>Cargando vista previa</p></div>',
		buttons: {
			confirmShow: false,
			cancelShow: false
		}
	});
	// PREVIEW
	const data = 'cuerpo=' + encodeURIComponent(loadWYSIBB.bbcode());
	$.post(ZCodeApp.url + '/posts-preview.php?ts=true', data, req => {
		UPModal.setModal({
			title: $('input[name=titulo]').val(),
			body: req,
			buttons: {
				confirmShow: false,
				cancelShow: true,
				cancelTxt: 'Cerrar'
			}
		});
		window.scrollTo(0, 50)
	})	
}

// FUNCION PARA PUBLICAR
function publicar() {
	// Comprobamos que tengo contenido
	if ($('input[name=titulo]').val().length < 5) {
		error($('input[name=titulo]'), 'Debes ingresar un titulo para el post', true);
		$('input[name=titulo]').focus();
		return false;
	}
	//COMPROBAR CONTENIDO
	if (loadWYSIBB.bbcode().length < 1) {
		error(loadWYSIBB, 'Ingresa contenido para el post', true);
		loadWYSIBB.focus();
		window.scrollTo(0, 50)
		return false;
	}
	//COMPROBAR CATEGORIA
	if (!$('select[name=categoria]').val()) {
		error($('select[name=categoria]'), 'Selecciona una categor&iacute;a', true);
		return false;
	}		
	//COMPROBAR TAGS
	let tags = $('input[name=tags]').val().split(',');
	let msg = 'Tienes que ingresar por lo menos 4 tags separados por coma.';
	if (tags.length < 4) {
		error($('input[name=tags]'), msg, true);
		return false;
	} else {
		for(let i = 0; i < tags.length; i++) {
			error($('input[name=tags]'), msg, (tags[i] == ''));
			if(tags[i] == '') return false;
		}
	}
	//GUARDAR POST DESPUES DE COMPROBAR CAMPOS
	createPostNow();
}

// Con esta función ya publicaremos el post
function createPostNow() {
	UPModal.alert('Publicando', 'Comprobando contenido...', false);
	confirmar = false;
	$('form[name="newpost"]').submit();
}
function replaceAccents(str) {
	// Normalizar la cadena para eliminar acentos
	let normalized = str.normalize("NFD");
	// Eliminar todos los caracteres que no sean letras o espacios
	let cleaned = normalized.replace(/[^a-zA-Z ]/g, '');
	return cleaned;
}
const portada = {
	input: $('input[name="portada"]'),
	image(src) {
		$('.loadimg .avatar').removeClass('placeholder placeholder-wave').html(`<img class="w-100 h-100 object-fit-cover" src="${src}">`);
	},
	type(type) {
		type = (type === 'pc') ? 'file' : 'url';
		let input = `<input class="upform-input" type="${type}" name="portada" id="portada" value="${portadaIMG}" placeholder="URL de la imagen">`;
		$('.load--field .upform-group-input').html('').html(input);
		if(type === 'url') {
			$('#portada').on('keyup keydown', () => portada.image($('#portada').val()));
		} else {
			$('#portada').on('change', function() {
		   	const file = this.files[0];
		   	if (file) {
		     		const reader = new FileReader();
		     		reader.onload = function(e) {
		     			portada.image(e.target.result);
		        	}
		        	reader.readAsDataURL(file);
		    	}
			});
		}
	},
	load() {
		const { images: { assets: pathImages } } = ZCodeApp;
		let showPortada = pathImages;
		if(empty(portadaIMG)) {
			showPortada += '/favicon/logo-128.webp';
		} else {
			showPortada += '/portadas/' + portadaIMG + '/image_lg.webp';
		}
		this.image(showPortada);
	}
}
// Ejecutamos el wysibb
$(document).ready(() => {
	portada.load();
	const $titulo = $('input[name=titulo]');
	const $tags = $('input[name=tags]');
	const $portada = $('input[name=portada]');
	// Cargamos el editor
	$('textarea[name="cuerpo"]').css({height: 400}).wysibb();
	// Chequeamos el titulo
	$titulo.on('keyup', () => {
		const titleVal = $titulo.val();
		let checkTitle = (titleVal.length >= 5 && countUpperCase(titleVal) > 59) 
		error($titulo, 'El t&iacute;tulo no debe estar en may&uacute;sculas', checkTitle);
		return false;
	});

	// Comprobamos que no este publicado o sea repetido
	$titulo.on('blur', () => {
		const titleVal = $titulo.val();
		if(!empty(titleVal)) {
			$.post(`${ZCodeApp.url}/posts-genbus.php?do=search`, { q: titleVal }, respuesta => $('#repost').html(respuesta))
		}
	});
	// Generamos etiquetas en base al titulo
	$tags.on('click', () => {
		if(tags) return;
		const titleVal = $titulo.val();
		if (!empty(titleVal)) {
			let generatedTags = titleVal.split(' ')
			.map(tag => replaceAccents(tag.trim().toLowerCase()) )
			.filter(tag => tag.length > 4);
			$tags.val(generatedTags.join(', '));
			tags = true;
		}
	});
	$('.portada [data-type]').on('click', function() {
		let selected = $(this).data('type');
		portada.type(selected)
	});
});